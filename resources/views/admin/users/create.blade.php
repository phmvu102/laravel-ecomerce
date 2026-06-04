@extends('layouts.admin')

@section('title','Thêm người dùng')
@section('page-title','Thêm người dùng')

@section('content')

<div class="max-w-4xl mx-auto">

    <form
        action="{{ route('admin.users.store') }}"
        method="POST"
        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8"
    >
        @csrf

        <h3 class="text-xl font-bold mb-8">
            Thêm người dùng mới
        </h3>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-medium">
                    Họ tên
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full rounded-xl border-slate-300"
                >

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        Họ tên phải có ít nhất 3 ký tự.
                    </p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full rounded-xl border-slate-300"
                >

                @error('email')
                    <p class="text-red-500 text-sm mt-1">
                        Email đã tồn tại hoặc không hợp lệ.
                    </p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Mật khẩu
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full rounded-xl border-slate-300"
                >

                @error('password')
                    <p class="text-red-500 text-sm mt-1">
                        Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.
                    </p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Xác nhận mật khẩu
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full rounded-xl border-slate-300"
                >
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Vai trò
                </label>

                <select
                    name="role"
                    class="w-full rounded-xl border-slate-300"
                >
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>
                        Customer
                    </option>

                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>
                        Staff
                    </option>

                    <option value="vendor" {{ old('role') === 'vendor' ? 'selected' : '' }}>
                        Vendor
                    </option>

                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">

            <a
                href="{{ route('admin.users.index') }}"
                class="px-5 py-2.5 border rounded-xl"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl"
            >
                Thêm người dùng
            </button>
        </div>
    </form>
</div>
@endsection
