<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'user',
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute',
            'payment'
        ]);

        // Lọc trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where('order_code', 'like', "%{$keyword}%")

                    ->orWhereHas('user', function ($user) use ($keyword) {
                        $user->where('name', 'like', "%{$keyword}%")
                             ->orWhere('email', 'like', "%{$keyword}%");
                    });

            });
        }

        $orders = $query
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.productVariant.product',
            'items.productVariant.attributeValues.attribute'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Cập nhật trạng thái đơn hàng thành công'
        );
    }
}
