<x-filament-panels::page>
    @php
        $items = [
            ['Logo perusahaan', '1:1', '1000 x 1000 px', 'PNG/WebP transparan', 'Maks. 2 MB', 'Profil Website'],
            ['Hero halaman utama', '16:9', '1920 x 1080 px', 'JPG/WebP', 'Maks. 5 MB', 'Bagian Halaman > Home > Hero'],
            ['Hero halaman internal', '16:9', '1920 x 1080 px', 'JPG/WebP', 'Maks. 5 MB', 'Bagian Halaman > Produk/Portofolio/Artikel/Tentang/Kontak'],
            ['Profil dan cerita perusahaan', '5:4', '1600 x 1280 px', 'JPG/WebP', 'Maks. 5 MB', 'Bagian Halaman > About/Story'],
            ['Foto produk', '4:3', '1600 x 1200 px', 'JPG/WebP', 'Maks. 5 MB', 'Produk > Media produk'],
            ['Foto portofolio', '4:3', '1600 x 1200 px', 'JPG/WebP', 'Maks. 5 MB', 'Portofolio > Dokumentasi'],
            ['Foto artikel', '16:9', '1600 x 900 px', 'JPG/WebP', 'Maks. 5 MB', 'Artikel > Media utama'],
            ['Foto anggota tim', '4:3', '1200 x 900 px', 'JPG/WebP', 'Maks. 5 MB', 'Tim Perusahaan > Profil'],
            ['Foto profil pengguna', '1:1', '800 x 800 px', 'JPG/PNG/WebP', 'Maks. 2 MB', 'Profil akun/Pengguna CMS'],
            ['Video konten', '16:9', '1920 x 1080 px', 'MP4 H.264', 'Maks. 40 MB/120 detik', 'Produk/Portofolio/Artikel/Bagian Halaman'],
        ];
    @endphp

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($items as [$name, $ratio, $resolution, $format, $limit, $location])
            <x-filament::section>
                <x-slot name="heading">{{ $name }}</x-slot>
                <div class="grid gap-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500 dark:text-gray-400">Rasio</span>
                        <strong>{{ $ratio }}</strong>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500 dark:text-gray-400">Resolusi ideal</span>
                        <strong>{{ $resolution }}</strong>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500 dark:text-gray-400">Format</span>
                        <strong>{{ $format }}</strong>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500 dark:text-gray-400">Batas</span>
                        <strong>{{ $limit }}</strong>
                    </div>
                    <p class="border-t border-gray-200 pt-3 text-xs text-gray-500 dark:border-white/10 dark:text-gray-400">
                        Lokasi CMS: {{ $location }}
                    </p>
                </div>
            </x-filament::section>
        @endforeach
    </div>

    <x-filament::section>
        <x-slot name="heading">Checklist sebelum mengunggah</x-slot>
        <div class="grid gap-4 text-sm md:grid-cols-2">
            <ul class="grid gap-2">
                <li>1. Potong gambar mengikuti rasio fitur, bukan memaksa gambar melebar.</li>
                <li>2. Gunakan file asli. Jangan mengambil screenshot atau memperbesar gambar kecil.</li>
                <li>3. Pilih WebP untuk foto umum, PNG transparan hanya untuk logo.</li>
                <li>4. Pastikan objek utama tidak terlalu dekat dengan tepi gambar.</li>
            </ul>
            <ul class="grid gap-2">
                <li>5. Isi deskripsi gambar berdasarkan objek yang terlihat, bukan nama file.</li>
                <li>6. Periksa pratinjau desktop dan mobile setelah menyimpan.</li>
                <li>7. Hindari teks penting di dalam foto karena dapat terpotong pada layar kecil.</li>
                <li>8. Untuk video, gunakan MP4 16:9 tanpa letterbox atau bar hitam.</li>
            </ul>
        </div>
    </x-filament::section>
</x-filament-panels::page>
