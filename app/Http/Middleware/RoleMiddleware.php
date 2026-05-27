<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Kiểm tra xem đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Kiểm tra xem role của user có nằm trong danh sách được phép không
        $user = Auth::user();
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Nếu không có quyền, đá ra trang chủ hoặc báo lỗi 403
        abort(403, 'Bạn không có quyền truy cập vào trang này.');
    }
}
