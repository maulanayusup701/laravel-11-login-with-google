<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | Space</title>
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
                            Verifikasi
                            <span class="font-medium">email Anda</span>
                        </h2>
                        <div class="w-12 h-px bg-[#d4d4cc] mt-4"></div>
                    </div>

                    <p class="text-[#6b6b64] text-sm leading-relaxed">
                        Untuk keamanan akun Anda, silakan verifikasi alamat email terlebih dahulu sebelum melanjutkan.
                    </p>

                    <div class="pt-6">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    ✓</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    ✓</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-[#e8e8e0] border-2 border-white flex items-center justify-center text-[#2c2c28] text-xs font-medium">
                                    ✓</div>
                            </div>
                            <div class="text-[#8b8b84] text-xs">
                                <span class="text-[#2c2c28] font-medium">100%</span> akun terverifikasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Verification Form -->
            <div class="lg:w-1/2 p-8 lg:p-10 bg-white">
                <div class="max-w-sm mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-xl font-medium text-[#2c2c28]">Verifikasi Email</h3>
                        <p class="text-[#8b8b84] text-sm mt-1">Cek email Anda</p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-[#d4e8d4] bg-[#f8fdf8] px-4 py-3 text-sm text-[#3b6b3b]"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-lg border border-[#e8d4d4] bg-[#fdf8f8] px-4 py-3 text-sm text-[#6b3b3b]"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Verification Info -->
                    <div class="bg-[#fafaf7] border border-[#e8e8e0] rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-[#e8e8e0] rounded-full flex items-center justify-center">
                                <i class="fas fa-envelope text-[#2c2c28] text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[#2c2c28] font-medium text-sm">Email terkirim ke</p>
                                <p class="text-[#6b6b64] text-xs">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <p class="text-[#6b6b64] text-sm leading-relaxed">
                            Kami telah mengirimkan link verifikasi ke email Anda. Silakan buka email dan klik link
                            verifikasi untuk melanjutkan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-4">
                        <button type="button"
                            class="w-full bg-[#2c2c28] text-white py-2.5 rounded-lg font-medium hover:bg-[#3c3c38] transition-all duration-200 text-sm"
                            onclick="window.location.href='{{ url('/home') }}'">
                            Sudah diverifikasi
                        </button>

                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-[#fafaf7] text-[#2c2c28] py-2.5 rounded-lg font-medium hover:bg-[#e8e8e0] transition-all duration-200 text-sm border border-[#e8e8e0]">
                                Kirim Ulang Email
                            </button>
                        </form>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-[#e8e8e0]"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-[#b8b8b0] text-xs">atau</span>
                        </div>
                    </div>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="text-center">
                        @csrf
                        <button type="submit" class="text-[#8b8b84] hover:text-[#2c2c28] transition text-sm">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-[#b8b8b0] text-xs mt-6">
            © 2024 SPACE — ruang kerja yang tenang
        </p>
    </div>

    <script>
        // Auto-refresh setiap 30 detik untuk cek verifikasi
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>

</html>
