<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .input-transition {
            transition: all 0.2s ease;
        }
    </style>
</head>

<body class="bg-[#f5f5f0] min-h-screen flex items-center justify-center p-4">

    <div class="max-w-5xl w-full animate-fade-in">
        <div class="flex flex-col lg:flex-row bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e8e8e0]">

            <!-- Left Side - Soft Branding -->
            <div class="lg:w-1/2 p-8 lg:p-10 bg-[#fafaf7]">
                <div class="flex items-center gap-2 mb-12">
                    <div class="w-8 h-8 bg-[#2c2c28] rounded-lg flex items-center justify-center">
                        <i class="fas fa-circle text-white text-xs"></i>
                    </div>
                    <span class="text-[#2c2c28] font-medium text-sm tracking-wide">SPACE</span>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2 class="text-3xl font-light text-[#2c2c28] leading-tight">
                            Ruang kerja
                            <span class="font-medium">yang tenang</span>
                        </h2>
                        <div class="w-12 h-px bg-[#d4d4cc] mt-4"></div>
                    </div>

                    <p class="text-[#6b6b64] text-sm leading-relaxed">
                        Fokus pada apa yang penting. Platform sederhana untuk tim yang menghargai ketenangan dalam
                        bekerja.
                    </p>

                    <div class="pt-6">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    A</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    B</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    C</div>
                            </div>
                            <div class="text-[#8b8b84] text-xs">
                                <span class="text-[#2c2c28] font-medium">200+</span> tim mempercayai
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="lg:w-1/2 p-8 lg:p-10 bg-white">
                <div class="max-w-sm mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-xl font-medium text-[#2c2c28]">Masuk</h3>
                        <p class="text-[#8b8b84] text-sm mt-1">Selamat datang kembali</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-[#e8d4d4] bg-[#fdf8f8] px-4 py-3 text-sm text-[#6b3b3b]"
                            role="alert">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form id="loginForm" class="space-y-5" action="{{ route('login.post') }}" method="post">
                        @csrf
                        <!-- Email Field -->
                        <div>
                            <label
                                class="block text-[#4a4a44] text-xs font-medium uppercase tracking-wide mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 bg-[#fafaf7] border border-[#e8e8e0] rounded-lg focus:outline-none focus:border-[#b8b8b0] focus:bg-white transition-all input-transition text-sm @error('email') border-[#c4a4a4] @enderror"
                                placeholder="nama@email.com" required autocomplete="email">
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label class="block text-[#4a4a44] text-xs font-medium uppercase tracking-wide mb-2">Kata
                                sandi</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2.5 bg-[#fafaf7] border border-[#e8e8e0] rounded-lg focus:outline-none focus:border-[#b8b8b0] focus:bg-white transition-all input-transition text-sm @error('password') border-[#c4a4a4] @enderror"
                                placeholder="••••••••" required autocomplete="current-password">
                        </div>

                        <!-- Options -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" value="1"
                                    class="w-3.5 h-3.5 text-[#2c2c28] rounded border-[#d4d4cc] focus:ring-0 focus:ring-offset-0">
                                <span class="ml-2 text-xs text-[#6b6b64]">Ingat saya</span>
                            </label>
                            <a href="#" class="text-xs text-[#8b8b84] hover:text-[#2c2c28] transition">Lupa
                                password?</a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-[#2c2c28] text-white py-2.5 rounded-lg font-medium hover:bg-[#3c3c38] transition-all duration-200 text-sm">
                            Masuk
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-[#e8e8e0]"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-[#b8b8b0] text-xs">atau</span>
                        </div>
                    </div>

                    <!-- Google Login (Socialite) -->
                    <a href="{{ route('auth.google') }}" id="googleLogin"
                        class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-[#e8e8e0] rounded-lg hover:bg-[#fafaf7] transition-all duration-200">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span class="text-[#4a4a44] text-sm">Login with Google</span>
                    </a>

                    <!-- Sign Up Link -->
                    <p class="text-center text-[#8b8b84] text-xs mt-6">
                        Belum punya akun?
                        <a href="#" class="text-[#2c2c28] font-medium hover:underline ml-1">
                            Daftar
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-[#b8b8b0] text-xs mt-6">
            © 2024 SPACE — ruang kerja yang tenang
        </p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            btn.disabled = true;
        });
    </script>
</body>

</html>
