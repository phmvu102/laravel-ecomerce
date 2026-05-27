@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-2">
                Tổng đơn hàng
            </p>

            <h3 class="text-3xl font-bold">
                1,250
            </h3>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-2">
                Doanh thu
            </p>

            <h3 class="text-3xl font-bold">
                250M
            </h3>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-2">
                Khách hàng
            </p>

            <h3 class="text-3xl font-bold">
                530
            </h3>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <p class="text-sm text-slate-500 mb-2">
                Sản phẩm
            </p>

            <h3 class="text-3xl font-bold">
                320
            </h3>
        </div>

    </div>

    <!-- Content -->
    <div class="bg-white rounded-2xl shadow-sm p-6">

        <p class="text-slate-700 mb-6">
            Chào mừng Admin! Đây là không gian quản trị hệ thống.
        </p>

        <a
            href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition"
        >
            Quản lý Danh mục sản phẩm
        </a>

    </div>

@endsection
