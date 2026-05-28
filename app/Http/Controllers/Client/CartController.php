<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        [$cart, $subtotal] = $this->cartSummary();

        return view('client.cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['nullable', 'required_without_all:product_variant_id,variant_id', 'integer', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $variantId = $validated['product_variant_id'] ?? $validated['variant_id'] ?? null;
        $variant = $variantId
            ? ProductVariant::with('product')->findOrFail($variantId)
            : Product::with('variants')->findOrFail($validated['product_id'])->variants()->orderBy('price')->first();

        if (! $variant) {
            return $this->cartResponse($request, false, 'Product is not available.');
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = session('cart', []);
        $key = (string) $variant->id;
        $price = (float) ($variant->sale_price ?? $variant->price);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'name' => $variant->product->name,
                'sku' => $variant->sku,
                'image' => $variant->image,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        $this->storeCart($cart);

        return $this->cartResponse($request, true, 'Added to cart.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*' => ['integer', 'min:0', 'max:99'],
            'id' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $cart = session('cart', []);
        $items = $validated['items'] ?? [];

        if (isset($validated['id'], $validated['quantity'])) {
            $items[$validated['id']] = $validated['quantity'];
        }

        foreach ($items as $id => $quantity) {
            $key = (string) $id;

            if (! isset($cart[$key])) {
                continue;
            }

            if ((int) $quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = (int) $quantity;
            }
        }

        $this->storeCart($cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $id): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[(string) $id]);
        $this->storeCart($cart);

        return back()->with('success', 'Item removed.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget(['cart', 'cart_count']);

        return back()->with('success', 'Cart cleared.');
    }

    public function checkout(): View|RedirectResponse
    {
        [$cart, $subtotal] = $this->cartSummary();

        if ($cart->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Your cart is empty.');
        }

        return view('client.checkout', compact('cart', 'subtotal'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        [$cart, $subtotal] = $this->cartSummary();

        if ($cart->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $order = DB::transaction(function () use ($cart, $subtotal, $validated) {
            $order = Order::create([
                'order_code' => $this->makeOrderCode(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_fee' => 0,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'customer_note' => $validated['customer_note'] ?? null,
                'payment_method' => $validated['payment_method'] ?? 'COD',
                'source' => 'web',
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        session()->forget(['cart', 'cart_count']);

        return redirect()->route('client.order.success', $order);
    }

    public function orderSuccess(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.productVariant.product');

        return view('client.order-success', compact('order'));
    }

    private function cartSummary(): array
    {
        $cart = collect(session('cart', []));
        $subtotal = $cart->sum(fn (array $item): float => $item['price'] * $item['quantity']);

        return [$cart, $subtotal];
    }

    private function storeCart(array $cart): void
    {
        session([
            'cart' => $cart,
            'cart_count' => collect($cart)->sum('quantity'),
        ]);
    }

    private function cartResponse(Request $request, bool $success, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->isJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'cart_count' => session('cart_count', 0),
            ], $success ? 200 : 422);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }

    private function makeOrderCode(): string
    {
        do {
            $code = 'WEB-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
