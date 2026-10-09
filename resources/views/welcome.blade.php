<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PT. Besi Leleh Buatan Jakarta</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" /> --}}
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="max-w-5xl w-full bg-white shadow rounded-lg p-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-3">
        <div>
            <h1 class="text-2xl font-bold">PT. Besi Leleh Buatan Jakarta</h1>
            <p class="text-gray-600">
                Sistem Manajemen Pengadaan Bahan Baku
                <br>
                <span class="italic">(Raw Material Procurement System)</span>
            </p>
        </div>

        <div class="space-x-2">
            <a href="{{ route('login') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Login
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Register
                </a>
            @endif
        </div>
    </div>

    <hr class="mb-3">

    {{-- DESKRIPSI SISTEM --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <section class="mb-6">

                {{-- FOKUS SISTEM --}}
                <h2 class="text-lg font-semibold mb-2">Fokus Sistem</h2>
                <ul class="list-disc list-inside text-gray-700 text-sm">
                    <li>Pengadaan bahan baku logam ringan untuk kebutuhan produksi</li>
                    <li>Pengelolaan bahan baku baja secara terstruktur dan terkontrol</li>
                    <li>Pengadaan material plastik dan resin sesuai spesifikasi</li>
                    <li>Pengelolaan berbagai jenis bahan baku lainnya yang digunakan dalam proses produksi</li>
                </ul>

                {{-- INTI SISTEM --}}
                <h2 class="text-lg font-semibold mb-2 mt-4">Inti Sistem</h2>
                <p class="text-sm leading-relaxed text-gray-600">
                    Sistem Manajemen Pengadaan Bahan Baku ini dirancang untuk mendukung seluruh
                    proses pengadaan material perusahaan, mulai dari perencanaan kebutuhan,
                    pemesanan kepada supplier, penerimaan barang, hingga pencatatan data pengadaan.
                    Sistem ini membantu memastikan bahwa bahan baku tersedia tepat waktu,
                    sesuai jumlah, dan sesuai spesifikasi yang dibutuhkan.
                </p>

                {{-- HARAPAN SISTEM --}}
                <h2 class="text-lg font-semibold mb-2 mt-4">Harapan Sistem</h2>
                <ul class="list-disc list-inside text-gray-700 text-sm">
                    <li>Meningkatkan efisiensi dan transparansi dalam proses pengadaan bahan baku</li>
                    <li>Mengurangi kesalahan pencatatan dan keterlambatan pengadaan</li>
                    <li>Mempermudah monitoring status pengadaan dan penerimaan barang</li>
                    <li>Mendukung pengambilan keputusan berbasis data yang akurat dan terintegrasi</li>
                </ul>

            </section>

        </div>

        <div class="flex justify-center items-center">
            <img src="{{ asset('images/img_blbj_1.jpg') }}"
                 alt="PT BLBJ"
                 class="rounded shadow">
        </div>
    </div>



    {{-- FOOTER --}}
    <footer class="text-center text-sm text-gray-500 mt-4">
        © {{ date('Y') }} PT. Besi Leleh Buatan Jakarta
    </footer>

</div>

</body>
</html>
