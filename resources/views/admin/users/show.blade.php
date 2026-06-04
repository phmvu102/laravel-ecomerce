@extends('layouts.admin')

@section('page-title','Chi tiết người dùng')

@section('content')

<div class="bg-white rounded-2xl p-8">
    <div class="flex items-center gap-5">

        <img
            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
            class="w-24 h-24 rounded-full"
        >

        <div>

            <h2 class="text-2xl font-bold">
                {{ $user->name }}
            </h2>

            <p class="text-slate-500">
                {{ $user->email }}
            </p>

        </div>

    </div>

    <div class="grid grid-cols-2 gap-6 mt-8">

        <div>
            <strong>ID:</strong>
            {{ $user->id }}
        </div>

        <div>
            <strong>Vai trò:</strong>
            {{ ucfirst($user->role) }}
        </div>

        <div>
            <strong>Trạng thái:</strong>
            {{ ucfirst($user->status) }}
        </div>

        <div>
            <strong>Ngày tạo:</strong>
            {{ $user->created_at->format('d/m/Y H:i') }}
        </div>
    <a href="{{ route('admin.users.index') }}">Quay lại</a>

    </div>

</div>

@endsection
