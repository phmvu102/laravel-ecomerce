<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Danh sách đơn hàng
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $orders = Order::with([
                'items.productVariant.product',
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Chi tiết đơn hàng
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $order = Order::with([
                'items.productVariant.product',
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('client.orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | Trang checkout
    |--------------------------------------------------------------------------
    */
    public function checkout()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {

            return redirect()
                ->route('client.cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        $cartItems = $cart->items()
            ->with('productVariant.product')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('client.checkout.index', [
            'cart' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Đặt hàng
    |--------------------------------------------------------------------------
    */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string|max:1000',
            'customer_note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,bank,vnpay,momo',
        ]);

        if ($request->payment_method === 'momo') {
            return back()
                ->withInput()
                ->with('error', 'Chua cau hinh thong tin Merchant MoMo.');
        }

        $cart = Cart::where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {

            return redirect()
                ->route('client.cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        $cartItems = $cart->items()
            ->with('productVariant.product')
            ->get();

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Tổng tiền
            |--------------------------------------------------------------------------
            */
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shippingFee = 0;
            $discount = 0;

            $total = $subtotal + $shippingFee - $discount;
            $paymentMethod = $request->payment_method === 'bank'
                ? 'vnpay'
                : $request->payment_method;

            /*
            |--------------------------------------------------------------------------
            | Tạo order
            |--------------------------------------------------------------------------
            */
            $order = Order::create([
                'user_id' => Auth::id(),

                'order_code' => 'ORD' . now()->format('YmdHis') . rand(10, 99),

                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_email' => $request->shipping_email,
                'shipping_address' => $request->shipping_address,
                'customer_note' => $request->customer_note,

                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discount,
                'total_amount' => $total,

                'payment_method' => $paymentMethod,

                'payment_status' => 'unpaid',

                'status' => 'pending',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Tạo order items
            |--------------------------------------------------------------------------
            */
            foreach ($cartItems as $item) {

                $variant = ProductVariant::with('product')
                    ->lockForUpdate()
                    ->find($item->product_variant_id);

                if (!$variant) {
                    throw new \Exception('Biến thể sản phẩm không tồn tại.');
                }

                if ($variant->stock < $item->quantity) {

                    throw new \Exception(
                        'Sản phẩm "' .
                        $variant->product->name .
                        '" không đủ tồn kho.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Trừ tồn kho
                |--------------------------------------------------------------------------
                */
                $variant->decrement('stock', $item->quantity);

                /*
                |--------------------------------------------------------------------------
                | Tạo item
                |--------------------------------------------------------------------------
                */
                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' => $variant->product_id,

                    'product_variant_id' => $variant->id,

                    'product_name' => $variant->product->name,

                    'product_image' => $variant->image,

                    'price' => $item->price,

                    'quantity' => $item->quantity,

                    'total' => $item->price * $item->quantity,
                ]);
            }

            if ($paymentMethod !== 'cod') {
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => null,
                    'payment_method' => $paymentMethod,
                    'amount' => $total,
                    'status' => 'pending',
                    'raw_response_data' => null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Xóa cart
            |--------------------------------------------------------------------------
            */
            $cart->items()->delete();

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | COD / VNPay / Momo
            |--------------------------------------------------------------------------
            */
            if ($paymentMethod === 'cod') {
                return redirect()
                    ->route('client.orders.success', $order->id)
                    ->with('success', 'Đặt hàng thành công.');
            }

            if ($paymentMethod === 'vnpay') {
                return redirect()
                    ->route('client.payment.vnpay', $order->id);
            }

            if ($paymentMethod === 'momo') {
                return redirect()
                    ->route('client.payment.momo', $order->id);
            }

            return redirect()
                ->route('client.orders.success', $order->id)
                ->with('success', 'Đặt hàng thành công.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Trang thành công
    |--------------------------------------------------------------------------
    */
    public function success($id)
    {
        $order = Order::with([
                'items.productVariant.product',
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('client.checkout.success', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | Hủy đơn hàng
    |--------------------------------------------------------------------------
    */
    public function cancel($id)
    {
        $order = Order::with('items.productVariant')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if (!in_array($order->status, ['pending', 'confirmed', 'processing'])) {

            return back()->with(
                'error',
                'Không thể hủy đơn hàng này.'
            );
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Hoàn tồn kho
            |--------------------------------------------------------------------------
            */
            foreach ($order->items as $item) {

                if ($item->productVariant) {

                    $item->productVariant->increment(
                        'stock',
                        $item->quantity
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update trạng thái
            |--------------------------------------------------------------------------
            */
            $order->update([
                'status' => 'cancelled'
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Đã hủy đơn hàng.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
