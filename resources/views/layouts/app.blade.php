<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Obat Klinik</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white border-b shadow-sm p-4 flex justify-between items-center">
        <div>
            <a href="/" class="font-bold text-xl text-blue-600">Sistem Obat</a>
        </div>
        <div>
            @auth
                <span class="mr-4 text-sm">Halo, {{ Auth::user()->name }}</span>
                <a href="{{ url('/dashboard') }}" class="mr-4 text-blue-600">Dashboard</a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-red-600">Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="mr-4">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest
        </div>
    </nav>

    {{-- Body --}}
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white p-4 border-r min-h-screen">
            <ul class="space-y-2 text-sm">
                <li><a href="/obat" class="block py-1 text-blue-600 hover:underline">Data Obat</a></li>
                <li><a href="/obat-masuk" class="block py-1 text-blue-600 hover:underline">Obat Masuk</a></li>
                <li><a href="/obat-keluar" class="block py-1 text-blue-600 hover:underline">Obat Keluar</a></li>

                @auth
                    @if(Auth::user()->role === 'kepala_klinik')
                        <li class="mt-4 font-semibold text-gray-600">Report</li>
                        <li><a href="/laporan/stok" class="block py-1 text-green-600 hover:underline">Laporan Stok</a></li>
                        <li><a href="/laporan/keuangan" class="block py-1 text-green-600 hover:underline">Laporan Keuangan</a></li>
                    @endif
                @endauth
            </ul>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
