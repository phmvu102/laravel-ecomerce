@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <style>
        .liquid-glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px) saturate(140%);
            -webkit-backdrop-filter: blur(24px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                inset 0 2px 4px 0 rgba(255, 255, 255, 0.15),
                inset 0 -2px 4px 0 rgba(0, 0, 0, 0.2);
        }

        .liquid-input {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .liquid-input:focus {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(34, 211, 238, 0.5);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.25);
        }
    </style>

    <div class="min-h-screen overflow-hidden bg-[#020617] relative flex items-center justify-center">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.3),transparent_40%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.2),transparent_45%),linear-gradient(to_bottom_right,#020617,#071226,#0f172a)]">
        </div>

        <div
            class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-cyan-500/15 rounded-full blur-[140px] animate-pulse"
            style="animation-duration: 8s;"></div>

        <div
            class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[140px] animate-pulse"
            style="animation-duration: 10s;"></div>

        <div class="relative z-10 w-full min-h-screen grid lg:grid-cols-12 max-w-[1600px] mx-auto">
            <div class="hidden lg:flex lg:col-span-7 relative items-center px-16 xl:px-24 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-10 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px]">
                </div>

                <div
                    class="absolute left-10 top-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-cyan-500/10 rounded-full animate-spin"
                    style="animation-duration: 40s;"></div>
                <div
                    class="absolute left-24 top-1/2 -translate-y-1/2 w-[450px] h-[450px] border border-blue-500/10 rounded-full animate-spin"
                    style="animation-duration: 25s; animation-direction: reverse;"></div>

                <div class="relative z-10 max-w-xl">
                    <div
                        class="inline-flex items-center gap-3.5 px-5 py-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl shadow-xl mb-8 transform hover:scale-105 transition duration-300">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold text-sm tracking-wide">Khôi phục tài khoản</h3>
                            <p class="text-cyan-200/50 text-xs">Bảo mật thông tin tối đa</p>
                        </div>
                    </div>

                    <h1 class="text-5xl xl:text-6xl font-extrabold tracking-tight leading-[1.15] text-white">
                        Quên mật khẩu
                        <span
                            class="block bg-gradient-to-r from-cyan-300 via-blue-400 to-indigo-400 bg-clip-text text-transparent drop-shadow-sm">
                            VUCCI Shop
                        </span>
                    </h1>

                    <p class="mt-6 text-base xl:text-lg leading-relaxed text-slate-400 max-w-md">
                        Đừng lo lắng! Hãy cung cấp địa chỉ email đã đăng ký, chúng tôi sẽ gửi một liên kết để bạn thiết lập lại mật khẩu mới ngay lập tức.
                    </p>

                    <div class="flex gap-5 mt-10">
                        <div
                            class="px-6 py-4 rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-md shadow-lg">
                            <h2 class="text-2xl font-bold text-white tracking-tight">Mã hóa</h2>
                            <p class="text-slate-500 text-xs mt-0.5 font-medium uppercase tracking-wider">
                                Liên kết an toàn
                            </p>
                        </div>

                        <div
                            class="px-6 py-4 rounded-2xl bg-white/[0.03] border border-white/5 backdrop-blur-md shadow-lg">
                            <h2 class="text-2xl font-bold text-white tracking-tight">Hỗ trợ 24/7</h2>
                            <p class="text-slate-500 text-xs mt-0.5 font-medium uppercase tracking-wider">
                                Tự động xử lý
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center lg:col-span-5 px-4 sm:px-8 lg:px-12 xl:px-16 py-8 w-full">
                <div
                    class="liquid-glass-card relative w-full max-w-md rounded-[32px] overflow-hidden p-8 sm:p-10 transition-all duration-300 hover:border-white/20">
                    <div
                        class="absolute -top-20 -right-20 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="text-center mb-8">
                            <div
                                class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-gradient-to-tr from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 ring-4 ring-blue-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>

                            <h2 class="text-2xl font-bold text-white tracking-wide">
                                Tìm lại mật khẩu
                            </h2>

                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                {{ __('Quên mật khẩu? Không vấn đề gì. Chỉ cần cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết đặt lại mật khẩu để bạn có thể chọn mật khẩu mới.') }}
                            </p>
                        </div>

                        <x-auth-session-status class="mb-5 text-sm text-emerald-400 font-medium text-center bg-emerald-500/10 py-2.5 px-4 rounded-xl border border-emerald-500/20" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                            @csrf

                            <div class="space-y-1.5">
                                <x-input-label for="email" :value="__('Địa chỉ Email')"
                                    class="text-slate-300 font-medium text-xs uppercase tracking-wider ml-1" />
                                <div class="relative group">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </span>

                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                        required autofocus placeholder="name@gmail.com"
                                        class="liquid-input w-full rounded-2xl pl-11 pr-5 py-3.5 text-sm text-white placeholder:text-slate-600 outline-none focus:ring-0" />
                                </div>

                                <x-input-error :messages="$errors->get('email')"
                                    class="mt-1.5 text-xs text-rose-400/90 font-medium ml-1" />
                            </div>

                            <div class="flex items-center justify-end text-xs pt-1">
                                <a href="{{ route('login') }}"
                                    class="text-slate-400 hover:text-cyan-400 font-medium transition duration-150 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Quay lại đăng nhập
                                </a>
                            </div>

                            <div class="pt-3">
                                <button type="submit"
                                    class="group relative w-full overflow-hidden rounded-2xl py-3.5 font-semibold text-sm text-white transition-all duration-300 active:scale-[0.98] shadow-lg shadow-blue-500/20">

                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 group-hover:opacity-90 transition duration-300">
                                    </div>

                                    <div
                                        class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.2),transparent_60%)] transition duration-500">
                                    </div>

                                    <span
                                        class="relative z-10 tracking-wider flex items-center justify-center gap-2">
                                        {{ __('Liên kết đặt lại mật khẩu qua email') }}

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 transform group-hover:translate-x-1 transition duration-200"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
