<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Space</title>
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

        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background: linear-gradient(90deg, #4f46e5, #2563eb);
            transform: translateX(4px);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-[#f5f5f0] min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-[#e8e8e0] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-[#2c2c28] rounded-lg flex items-center justify-center">
                        <i class="fas fa-circle text-white text-sm"></i>
                    </div>
                    <span class="text-[#2c2c28] font-medium text-sm tracking-wide">SPACE</span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[#2c2c28] font-medium text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-[#8b8b84] text-xs">{{ auth()->user()->email }}</p>
                    </div>
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-[#4f46e5] to-[#2563eb] rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-[#8b8b84] hover:text-[#2c2c28] transition">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-[#e8e8e0] min-h-screen p-6">
            <nav class="space-y-2">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 bg-[#fafaf7] rounded-lg text-[#2c2c28] font-medium">
                    <i class="fas fa-home text-[#4f46e5]"></i>
                    Dashboard
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 sidebar-item rounded-lg text-[#6b6b64] hover:text-[#2c2c28]">
                    <i class="fas fa-chart-bar text-[#6b6b64]"></i>
                    Analytics
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 sidebar-item rounded-lg text-[#6b6b64] hover:text-[#2c2c28]">
                    <i class="fas fa-users text-[#6b6b64]"></i>
                    Team
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 sidebar-item rounded-lg text-[#6b6b64] hover:text-[#2c2c28]">
                    <i class="fas fa-cog text-[#6b6b64]"></i>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 animate-fade-in">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Section -->
                <div class="bg-gradient-to-r from-[#4f46e5] to-[#2563eb] rounded-2xl p-8 text-white mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Selamat datang di Space</h1>
                            <p class="text-[#e8e8e0]">Platform kerja tenang untuk tim produktif</p>
                        </div>
                        <div class="hidden lg:block">
                            <i class="fas fa-rocket text-6xl opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-[#e8e8e0] stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#6b6b64] text-sm font-medium">Proyek Aktif</p>
                                <p class="text-2xl font-bold text-[#2c2c28] mt-1">12</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-[#4f46e5] to-[#2563eb] rounded-lg flex items-center justify-center">
                                <i class="fas fa-project-diagram text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-[#e8e8e0] stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#6b6b64] text-sm font-medium">Tugas Selesai</p>
                                <p class="text-2xl font-bold text-[#2c2c28] mt-1">87%</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-[#10b981] to-[#06b6d4] rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-[#e8e8e0] stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#6b6b64] text-sm font-medium">Kolaborator</p>
                                <p class="text-2xl font-bold text-[#2c2c28] mt-1">24</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-[#f59e0b] to-[#ef4444] rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-[#e8e8e0]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-[#2c2c28]">Aktivitas Terbaru</h2>
                        <a href="#" class="text-[#4f46e5] text-sm font-medium hover:text-[#2563eb]">Lihat
                            Semua</a>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-[#fafaf7] rounded-lg">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-[#4f46e5] to-[#2563eb] rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-[#2c2c28]">Proyek baru "Aplikasi Mobile" dibuat</p>
                                <p class="text-[#8b8b84] text-sm">2 menit yang lalu</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-[#fafaf7] rounded-lg">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-[#10b981] to-[#06b6d4] rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-[#2c2c28]">Tugas "Desain UI Dashboard" selesai</p>
                                <p class="text-[#8b8b84] text-sm">15 menit yang lalu</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-[#fafaf7] rounded-lg">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-[#f59e0b] to-[#ef4444] rounded-full flex items-center justify-center">
                                <i class="fas fa-comment text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-[#2c2c28]">Diskusi baru di "Meeting Sprint Planning"</p>
                                <p class="text-[#8b8b84] text-sm">1 jam yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6">
        <button
            class="bg-[#4f46e5] hover:bg-[#2563eb] text-white p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110">
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>
</body>

</html>
