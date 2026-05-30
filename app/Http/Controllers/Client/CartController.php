<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getCart()
    {
        return Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);
    }

    public function index()
    {
        $cart = $this->getCart()
            ->items()
            ->with('productVariant.product')
            ->get();

        $subtotal = $cart->sum(fn($item) => $item->price * $item->quantity);

        return view('client.cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['nullable', 'integer', 'exists:products,id'],
                'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
                'quantity' => ['nullable', 'integer', 'min:1'],
            ]);

            $variant = null;

            if (!empty($validated['variant_id'])) {
                $variant = ProductVariant::with('product')
                    ->findOrFail($validated['variant_id']);
            } else {
                $variant = Product::with('variants')
                    ->findOrFail($validated['product_id'])
                    ->variants()
                    ->orderBy('price')
                    ->first();
            }

            if (!$variant) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy biến thể.'
                    ], 404);
                }
                return back()->with('error', 'Không tìm thấy biến thể.');
            }

            $cart = $this->getCart();

            $quantity = $validated['quantity'] ?? 1;

            $item = CartItem::where([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
            ])->first();

            $price = $variant->sale_price ?? $variant->price;

            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            // Đếm tổng số sản phẩm trong giỏ
            $cartCount = $cart->items()->sum('quantity');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm vào giỏ hàng.',
                    'cart_count' => $cartCount
                ]);
            }

            return back()->with('success', 'Đã thêm vào giỏ hàng.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
        ]);

        $cart = $this->getCart();

        foreach ($validated['items'] as $id => $quantity) {

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $id)
                ->first();

            if (!$item) {
                continue;
            }

            if ($quantity <= 0) {
                $item->delete();
            } else {
                $item->update([
                    'quantity' => $quantity
                ]);
            }
        }

        return back()->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function remove(Request $request, $id)
    {
        try {
            $cart = $this->getCart();

            CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $id)
                ->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm.'
                ]);
            }

            return back()->with('success', 'Đã xóa sản phẩm.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function clear(Request $request)
    {
        try {
            $this->getCart()->items()->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa giỏ hàng.'
                ]);
            }

            return back()->with('success', 'Đã xóa giỏ hàng.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function checkout()
    {
        $user = auth()->user();
        $cart = $this->getCart()
            ->items()
            ->with('productVariant.product')
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->route('client.shop')->with('warning', 'Giỏ hàng trống.');
        }

        $subtotal = $cart->sum(fn($item) => $item->price * $item->quantity);

        return view('client.checkout', compact('cart', 'subtotal', 'user'));
    }

    public function placeOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'shipping_name' => ['required', 'string', 'max:255'],
                'shipping_phone' => ['required', 'string', 'max:20'],
                'shipping_address' => ['required', 'string', 'max:500'],
                'customer_note' => ['nullable', 'string', 'max:500'],
                'payment_method' => ['required', 'in:COD,VNPAY,MOMO'],
            ]);

            $cart = $this->getCart()
                ->items()
                ->with('productVariant.product')
                ->get();

            if ($cart->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống.');
            }

            $subtotal = $cart->sum(fn($item) => $item->price * $item->quantity);
            $shippingFee = 0; // Có thể tính toán dựa trên địa chỉ
            $totalAmount = $subtotal + $shippingFee;

            // Tạo đơn hàng
            $order = Order::create([
                'order_code' => 'ORD-' . Str::upper(Str::random(8)) . '-' . time(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'customer_note' => $validated['customer_note'] ?? null,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'source' => 'web',
            ]);

            // Tạo order items từ cart
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            // Xóa giỏ hàng sau khi đặt hàng
            $this->getCart()->items()->delete();

            return redirect()->route('client.order.success', $order)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage());
        }
    }

    public function orderSuccess(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('client.shop')->with('error', 'Không có quyền xem đơn hàng này.');
        }

        $order->load('items.productVariant.product');

        return view('client.order-success', compact('order'));
    }
}

