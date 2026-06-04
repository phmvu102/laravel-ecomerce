@extends('layouts.admin')

@section('title','Sửa người dùng')
@section('page-title','Sửa người dùng')

@section('content')

<div class="max-w-4xl mx-auto">

    <form
        action="{{ route('admin.users.update',$user) }}"
        method="POST"
        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8"
    >
        @csrf
        @method('PUT')

        <h3 class="text-xl font-bold mb-8">
            Cập nhật người dùng
        </h3>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 font-medium">
                    Họ tên
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name) }}"
                    class="w-full rounded-xl border-slate-300"
                >
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email',$user->email) }}"
                    class="w-full rounded-xl border-slate-300"
                >
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Mật khẩu mới
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full rounded-xl border-slate-300"
                >

                <small class="text-slate-500">
                    Để trống nếu không đổi mật khẩu
                </small>
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
                    <option
                        value="customer"
                        {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}
                    >
                        Customer
                    </option>

                    <option
                        value="staff"
                        {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}
                    >
                        Staff
                    </option>

                    <option
                        value="vendor"
                        {{ old('role', $user->role) === 'vendor' ? 'selected' : '' }}
                    >
                        Vendor
                    </option>

                    <option
                        value="admin"
                        {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}
                    >
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
                Cập nhật
            </button>

        </div>

    </form>

</div>

@endsection
