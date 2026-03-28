<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Tap - Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        .info-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .location-marker {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            border-radius: 50%;
            width: 12px;
            height: 12px;
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .coordinates-display {
            font-family: 'Courier New', monospace;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            color: #1e293b;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white text-sm"></i>
                    </div>
                    <span class="text-gray-800 font-medium text-sm tracking-wide">GOOGLE MAPS TAP</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Klik di peta untuk mendapatkan koordinat</span>
                    <button onclick="resetMap()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="flex h-screen">
        <!-- Map Container -->
        <div class="flex-1 relative">
            <div id="map"></div>

            <!-- Crosshair Center -->
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                <div class="w-6 h-6 border-2 border-blue-500 rounded-full flex items-center justify-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                </div>
            </div>

            <!-- Instructions Overlay -->
            <div class="absolute top-4 left-4 bg-white bg-opacity-90 p-4 rounded-lg shadow-md">
                <h3 class="font-semibold text-gray-800 mb-2">Petunjuk Penggunaan</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Klik di mana saja di peta</li>
                    <li>• Koordinat akan muncul di panel kanan</li>
