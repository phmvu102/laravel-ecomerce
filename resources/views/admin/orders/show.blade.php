@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng')

@section('content')

<div class="bg-white rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-content-between mb-6">
        <form
            action="{{ route('admin.orders.updateStatus', $order) }}"
            method="POST"
            class="mt-4"
        >
            @csrf
            @method('PUT')

            <label class="block mb-2 font-medium">
                Cập nhật trạng thái
            </label>

            <select
                name="status"
                class="w-full border rounded-xl px-4 py-2"
            >
                <option value="pending" @selected($order->status=='pending')>
                    Chờ xử lý
                </option>

                <option value="processing" @selected($order->status=='processing')>
                    Đang xử lý
                </option>

                <option value="shipping" @selected($order->status=='shipping')>
                    Đang giao
                </option>

                <option value="completed" @selected($order->status=='completed')>
                    Hoàn thành
                </option>

                <option value="cancelled" @selected($order->status=='cancelled')>
                    Đã huỷ
                </option>
            </select>

            <button
                class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-xl"
            >
                Cập nhật
            </button>

            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-xl">
                Quay lại danh sách đơn hàng
            </a>
        </form>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold">Đơn hàng #{{ $order->order_code }}</h3>
            <p class="text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div>
            <span class="text-sm text-slate-600">Trạng thái:</span>
            <div class="mt-1">
                @switch($order->status)
                    @case('pending')
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">Chờ xử lý</span>
                        @break
                    @case('processing')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">Đang xử lý</span>
                        @break
                    @case('shipping')
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs">Đang giao</span>
                        @break
                    @case('completed')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Hoàn thành</span>
                        @break
                    @case('cancelled')
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">Đã huỷ</span>
                        @break
                    @default
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs">{{ ucfirst($order->status) }}</span>
                @endswitch
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="col-span-2">
            <h4 class="font-semibold mb-3">Sản phẩm</h4>

            <table class="w-full text-sm">
                <thead class="text-left text-slate-600">
                    <tr>
                        <th>Tên sản phẩm - Biến thể sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-t py-2">
                            <td class="py-3">
                                @php
                                    $productLabel = $item->product_name ?? ($item->productVariant->product->name ?? 'Sản phẩm');
                                    $variantLabel = null;

                                    if (!empty($item->productVariant) && $item->productVariant->relationLoaded('attributeValues')) {
                                        $vals = $item->productVariant->attributeValues;
                                        if ($vals->isNotEmpty()) {
                                            $variantLabel = $vals->map(function($v) {
                                                $attr = $v->attribute->name ?? null;
                                                return ($attr ? $attr.': ' : '').$v->value;
                                            })->join(', ');
                                        }
                                    } elseif (!empty($item->productVariant)) {
                                        // fallback: try to access attributeValues without checking relation
                                        $vals = $item->productVariant->attributeValues ?? collect();
                                        if ($vals->isNotEmpty()) {
                                            $variantLabel = $vals->map(function($v) {
                                                $attr = $v->attribute->name ?? null;
                                                return ($attr ? $attr.': ' : '').$v->value;
                                            })->join(', ');
                                        }
                                    }
                                @endphp

                                {{ $productLabel }}
                                @if(!empty($variantLabel))
                                    - {{ $variantLabel }}
                                @endif
                            </td>

                            <td class="py-3">{{ $item->quantity }}</td>

                            <td class="py-3">{{ number_format($item->price) }}đ</td>

                            <td class="py-3">{{ number_format($item->total) }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <h4 class="font-semibold mb-3">Tổng quan</h4>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Tạm tính</span><span>{{ number_format($order->subtotal) }}đ</span></div>
                <div class="flex justify-between"><span>Phí giao hàng</span><span>{{ number_format($order->shipping_fee) }}đ</span></div>
                <div class="flex justify-between"><span>Giảm giá</span><span>{{ number_format($order->discount_amount) }}đ</span></div>
                <div class="flex justify-between font-bold"><span>Tổng</span><span>{{ number_format($order->total_amount) }}đ</span></div>
                <div class="flex justify-between"><span>Thanh toán</span><span>{{ $order->payment?->payment_method ?? $order->payment_method ?? '---' }}</span></div>
                <div class="flex justify-between"><span>Người nhận</span><span>{{ $order->shipping_name }}</span></div>
                <div class="flex justify-between"><span>Địa chỉ</span><span class="text-right">{{ $order->shipping_address }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
