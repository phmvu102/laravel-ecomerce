{{-- resources/views/client/checkout/success.blade.php --}}

@extends('layouts.app')

@section('title', 'Order Success')

@section('content')

<section
    class="relative overflow-hidden min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-100 py-16"
>
    {{-- BACKGROUND --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 left-0 h-96 w-96 rounded-full bg-sky-400/20 blur-3xl"
        ></div>

        <div
            class="absolute bottom-0 right-0 h-[30rem] w-[30rem] rounded-full bg-blue-500/20 blur-3xl"
        ></div>
    </div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border border-white/30 bg-white/10 p-10 text-center shadow-[0_8px_40px_rgba(14,165,233,0.15)] backdrop-blur-3xl"
        >
            {{-- ICON --}}
            <div
                class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-r from-emerald-400 to-sky-500 text-white shadow-xl shadow-sky-500/30"
            >
                <svg
                    class="h-12 w-12"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </div>

            {{-- TITLE --}}
            <h1
                class="mt-8 text-4xl font-black tracking-tight text-slate-900"
            >
                Đặt hàng thành công
            </h1>

            <p class="mt-4 text-base leading-7 text-slate-600">
                Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận và
                đang chờ xác nhận.
            </p>

            {{-- ORDER INFO --}}
            <div
                class="mt-10 rounded-3xl border border-white/20 bg-white/20 p-6 text-left backdrop-blur-xl"
            >

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Mã đơn hàng
                        </p>

                        <p class="mt-1 text-lg font-black text-slate-900">
                            {{ $order->order_code }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Tổng tiền
                        </p>

                        <p class="mt-1 text-lg font-black text-sky-700">
                            {{ number_format($order->total_amount, 0, ',', '.') }} ₫
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Thanh toán
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ strtoupper($order->payment_method) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Trạng thái
                        </p>

                        <span
                            class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700"
                        >
                            {{ match($order->status) {
                                'pending' => 'Chờ xử lý',
                                'processing' => 'Đang xử lý',
                                'shipping' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã huỷ',
                                default => ucfirst($order->status)
                            } }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div
                class="mt-10 flex flex-col justify-center gap-4 sm:flex-row"
            >
                <a
                    href="{{ route('client.orders.show', $order->id) }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 text-sm font-black uppercase tracking-wide text-white shadow-lg shadow-sky-500/30 transition hover:scale-[1.02]"
                >
                    Xem đơn hàng
                </a>

                <a
                    href="{{ route('client.shop') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-white/30 bg-white/20 px-6 py-4 text-sm font-black uppercase tracking-wide text-slate-700 backdrop-blur-xl transition hover:bg-white/30"
                >
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
