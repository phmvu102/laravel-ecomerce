<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->cartItems();
        $subtotal = $cartItems->sum('total');
        $shippingFee = $subtotal > 0 && $subtotal < 500000 ? 30000 : 0;
        $total = $subtotal + $shippingFee;

        return view('client.cart', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $variantId = $data['product_variant_id'] ?? null;

        if (!$variantId && !empty($data['product_id'])) {
            $variantId = Product::with('variants')
                ->findOrFail($data['product_id'])
                ->variants
                ->sortBy(fn ($variant) => $variant->sale_price ?? $variant->price)
                ->first()?->id;
        }

        if (!$variantId) {
            return $this->cartResponse($request, false, 'Sản phẩm chưa có phiên bản để thêm vào giỏ.');
        }

        $quantity = $data['quantity'] ?? 1;
        $cart = session('cart', []);
        $cart[$variantId] = ($cart[$variantId] ?? 0) + $quantity;

        $this->saveCart($cart);

        return $this->cartResponse($request, true, 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);
        $cart[$data['product_variant_id']] = $data['quantity'];
        $this->saveCart($cart);

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        $this->saveCart($cart);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function clear()
    {
        $this->saveCart([]);

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }

    public function checkout()
    {
        $cartItems = $this->cartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $subtotal = $cartItems->sum('total');
        $shippingFee = $subtotal < 500000 ? 30000 : 0;
        $total = $subtotal + $shippingFee;

        return view('client.checkout', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $cartItems = $this->cartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:COD'],
        ]);

        $subtotal = $cartItems->sum('total');
        $shippingFee = $subtotal < 500000 ? 30000 : 0;
        $total = $subtotal + $shippingFee;

        $order = DB::transaction(function () use ($cartItems, $data, $subtotal, $shippingFee, $total) {
            $order = Order::create([
                'order_code' => 'WEB'.now()->format('ymdHis').Str::upper(Str::random(4)),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => 0,
                'total_amount' => $total,
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
                'customer_note' => $data['customer_note'] ?? null,
                'payment_method' => $data['payment_method'],
                'source' => 'web',
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_variant_id' => $item['variant']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            return $order;
        });

        $this->saveCart([]);

        return redirect()->route('client.order.success', $order);
    }

    public function orderSuccess(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.productVariant.product', 'items.productVariant.attributeValues.attribute');

        return view('client.order-success', compact('order'));
    }

    private function cartItems()
    {
        $cart = session('cart', []);
        $variantIds = array_keys($cart);

        return ProductVariant::with(['product.brand', 'attributeValues.attribute'])
            ->whereIn('id', $variantIds)
            ->get()
            ->map(function ($variant) use ($cart) {
                $quantity = (int) ($cart[$variant->id] ?? 0);
                $price = (float) ($variant->sale_price ?? $variant->price);

                return [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $price * $quantity,
                ];
            })
            ->filter(fn ($item) => $item['quantity'] > 0)
            ->values();
    }

    private function saveCart(array $cart): void
    {
        $cart = collect($cart)
            ->filter(fn ($quantity) => (int) $quantity > 0)
            ->map(fn ($quantity) => min((int) $quantity, 99))
            ->all();

        session([
            'cart' => $cart,
            'cart_count' => array_sum($cart),
        ]);
    }

    private function cartResponse(Request $request, bool $success, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'cart_count' => session('cart_count', 0),
            ], $success ? 200 : 422);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }
}
