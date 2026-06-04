@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Quản lý đơn hàng')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Quản lý đơn hàng
            </h2>

            <p class="text-slate-500 text-sm mt-1">
                Theo dõi toàn bộ đơn hàng trong hệ thống
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Tìm mã đơn hoặc khách hàng..."
                class="px-4 py-2 rounded-xl border border-slate-300 w-72"
            >

            <select
                name="status"
                class="px-7 py-2 rounded-xl border border-slate-300"
            >
                <option value="">Tất cả trạng thái</option>

                <option value="pending"
                    @selected(request('status')=='pending')>
                    Chờ xử lý
                </option>

                <option value="processing"
                    @selected(request('status')=='processing')>
                    Đang xử lý
                </option>

                <option value="shipping"
                    @selected(request('status')=='shipping')>
                    Đang giao
                </option>

                <option value="completed"
                    @selected(request('status')=='completed')>
                    Hoàn thành
                </option>

                <option value="cancelled"
                    @selected(request('status')=='cancelled')>
                    Đã huỷ
                </option>
            </select>

            <button
                class="px-5 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
            >
                Lọc
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            Mã đơn
                        </th>

                        <th class="px-6 py-4 text-left">
                            Khách hàng
                        </th>

                        <th class="px-6 py-4 text-left">
                            Tổng tiền
                        </th>

                        <th class="px-6 py-4 text-left">
                            Thanh toán
                        </th>

                        <th class="px-6 py-4 text-left">
                            Trạng thái
                        </th>

                        <th class="px-6 py-4 text-left">
                            Ngày đặt
                        </th>

                        <th class="px-6 py-4 text-center">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>

                @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="px-6 py-4 font-semibold">
                            {{ $order->order_code }}
                        </td>

                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium">
                                    {{ $order->user?->name ?? 'Khách vãng lai' }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $order->user?->email }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-semibold text-rose-600">
                            {{ number_format($order->total_amount) }}đ
                        </td>

                        <td class="px-6 py-4">
                            {{ $order->payment?->payment_method ?? '---' }}
                        </td>

                        <td class="px-6 py-4">
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">
                                        Chờ xử lý
                                    </span>
                                    @break

                                @case('processing')
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">
                                        Đang xử lý
                                    </span>
                                    @break

                                @case('shipping')
                                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs">
                                        Đang giao
                                    </span>
                                    @break

                                @case('completed')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                                        Hoàn thành
                                    </span>
                                    @break

                                @case('cancelled')
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                                        Đã huỷ
                                    </span>
                                    @break
                            @endswitch

                        </td>

                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <a
                                    href="{{ route('admin.orders.show',$order) }}"
                                    class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100"
                                >
                                    Xem
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-500">
                            Không có đơn hàng nào
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-5 border-t">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
