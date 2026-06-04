<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createVnpayPayment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('client.orders.success', $order->id)
                ->with('success', 'Don hang da duoc thanh toan.');
        }

        $tmnCode = config('services.vnpay.tmn_code');
        $hashSecret = config('services.vnpay.hash_secret');
        $paymentUrl = config('services.vnpay.url');

        if (!$tmnCode || !$hashSecret || !$paymentUrl) {
            return redirect()
                ->route('client.orders.success', $order->id)
                ->with('error', 'Chua cau hinh cong thanh toan VNPay.');
        }

        Payment::updateOrCreate(
            ['order_id' => $order->id, 'payment_method' => 'vnpay'],
            [
                'transaction_id' => null,
                'amount' => $order->total_amount,
                'status' => 'pending',
                'raw_response_data' => null,
            ]
        );

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => (int) round($order->total_amount * 100),
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang ' . $order->order_code,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => route('client.payment.vnpay.return'),
            'vnp_TxnRef' => $order->order_code,
            'vnp_BankCode' => 'VNBANK',
        ];

        $query = $this->buildVnpayHashData($inputData);
        $secureHash = hash_hmac('sha512', $query, $hashSecret);

        return redirect($paymentUrl . '?' . $query . '&vnp_SecureHash=' . $secureHash);
    }

    public function vnpayReturn(Request $request)
    {
        $order = Order::where('order_code', $request->query('vnp_TxnRef'))->first();

        if (!$order) {
            return redirect()
                ->route('client.orders.index')
                ->with('error', 'Khong tim thay don hang thanh toan.');
        }

        if (!$this->hasValidSignature($request->query())) {
            $this->markPaymentFailed($order, $request->query());

            return redirect()
                ->route('client.orders.success', $order->id)
                ->with('error', 'Chu ky thanh toan VNPay khong hop le.');
        }

        if (
            $request->query('vnp_ResponseCode') === '00' &&
            $request->query('vnp_TransactionStatus') === '00'
        ) {
            $this->markPaymentSuccess($order, $request->query());

            return redirect()
                ->route('client.orders.success', $order->id)
                ->with('success', 'Thanh toan VNPay thanh cong.');
        }

        $this->markPaymentFailed($order, $request->query());

        return redirect()
            ->route('client.orders.success', $order->id)
            ->with('error', 'Thanh toan VNPay khong thanh cong.');
    }

    public function vnpayIpn(Request $request)
    {
        $data = $request->query();
        $order = Order::where('order_code', $request->query('vnp_TxnRef'))->first();

        if (!$this->hasValidSignature($data)) {
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature',
            ]);
        }

        if (!$order) {
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found',
            ]);
        }

        if ((int) round($order->total_amount * 100) !== (int) $request->query('vnp_Amount')) {
            return response()->json([
                'RspCode' => '04',
                'Message' => 'Invalid amount',
            ]);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'RspCode' => '02',
                'Message' => 'Order already confirmed',
            ]);
        }

        if (
            $request->query('vnp_ResponseCode') === '00' &&
            $request->query('vnp_TransactionStatus') === '00'
        ) {
            $this->markPaymentSuccess($order, $data);

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm success',
            ]);
        }

        $this->markPaymentFailed($order, $data);

        return response()->json([
            'RspCode' => '00',
            'Message' => 'Confirm success',
        ]);
    }

    public function createMomoPayment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect()
            ->route('client.orders.success', $order->id)
            ->with('error', 'Chua cau hinh thong tin Merchant MoMo.');
    }

    private function hasValidSignature(array $data): bool
    {
        $hashSecret = config('services.vnpay.hash_secret');
        $secureHash = $data['vnp_SecureHash'] ?? null;

        $data = array_filter(
            $data,
            fn ($key) => str_starts_with($key, 'vnp_'),
            ARRAY_FILTER_USE_KEY
        );

        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);

        $hashData = $this->buildVnpayHashData($data);
        $calculatedHash = hash_hmac('sha512', $hashData, $hashSecret);

        return $secureHash && hash_equals($calculatedHash, $secureHash);
    }

    private function buildVnpayHashData(array $data): string
    {
        ksort($data);

        $hashData = [];

        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $hashData[] = urlencode($key) . '=' . urlencode($value);
            }
        }

        return implode('&', $hashData);
    }

    private function markPaymentSuccess(Order $order, array $data): void
    {
        DB::transaction(function () use ($order, $data) {
            $order->update([
                'payment_status' => 'paid',
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id, 'payment_method' => 'vnpay'],
                [
                    'transaction_id' => $data['vnp_TransactionNo'] ?? null,
                    'amount' => $order->total_amount,
                    'status' => 'success',
                    'raw_response_data' => $data,
                ]
            );
        });
    }

    private function markPaymentFailed(Order $order, array $data): void
    {
        Payment::updateOrCreate(
            ['order_id' => $order->id, 'payment_method' => 'vnpay'],
            [
                'transaction_id' => $data['vnp_TransactionNo'] ?? null,
                'amount' => $order->total_amount,
                'status' => 'failed',
                'raw_response_data' => $data,
            ]
        );
    }
}
