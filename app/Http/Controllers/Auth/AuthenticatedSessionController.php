<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Lấy thông tin user vừa đăng nhập
        $user = Auth::user();

        // Kiểm tra nếu tài khoản bị khóa (banned) thì đăng xuất ngay
        if ($user->status === 'banned') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
        }

        // Điều hướng dựa trên Vai trò (Role)
        return match ($user->role) {
            'admin'  => redirect()->intended('/admin/dashboard'),
            'vendor' => redirect()->intended('/vendor/dashboard'),
            'staff'  => redirect()->intended('/staff/dashboard'),
            default  => redirect()->intended('/'), // Khách hàng về trang chủ
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
