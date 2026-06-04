<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function datatable()
    {
        return DataTables::of(User::query())

            ->addColumn('avatar', function ($user) {

                if ($user->avatar) {
                    return '<img src="' . asset('storage/' . $user->avatar) . '"
                                class="w-10 h-10 rounded-full object-cover">';
                }

                return '
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                        ' . strtoupper(substr($user->name, 0, 1)) . '
                    </div>
                ';
            })

            ->addColumn('phone', function ($user) {
                return $user->phone ?: '-';
            })

            ->addColumn('role_badge', function ($user) {


                return match ($user->role) {

                    'admin' => '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">
                            Admin
                        </span>
                    ',

                    'vendor' => '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-600">
                            Vendor
                        </span>
                    ',

                    'staff' => '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-600">
                            Staff
                        </span>
                    ',

                    default => '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-600">
                            Customer
                        </span>
                    ',
                };
            })

            ->addColumn('status_badge', function ($user) {

                return $user->status === 'active'
                    ? '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-600">
                            Hoạt động
                        </span>
                    '
                    : '
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">
                            Khóa
                        </span>
                    ';
            })

            ->editColumn('created_at', function ($user) {
                return $user->created_at
                    ? $user->created_at->format('d/m/Y H:i')
                    : '-';
            })

            ->addColumn('actions', function ($user) {
                return view(
                    'admin.users.partials.actions',
                    compact('user')
                )->render();
            })

            ->rawColumns([
                'avatar',
                'role_badge',
                'status_badge',
                'actions'
            ])

            ->make(true);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Thêm người dùng thành công');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ) {

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {

            $data['password'] = Hash::make(
                $request->password
            );
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Cập nhật thành công');
    }
}
