<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Anonim - SpeakUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-indigo-900">
        <div class="container mx-auto px-4 py-5 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 text-white">
                <div class="rounded-full bg-white/10 p-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c2.761 0 5-2.686 5-6S14.761-1 12-1 7 1.686 7 5s2.239 6 5 6zM3 21c0-3.313 2.687-6 6-6h6c3.313 0 6 2.687 6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-300">SpeakUp</p>
                    <p class="text-xs text-slate-400">Lapor Pelecehan & Diskriminasi</p>
                </div>
            </a>
            <nav class="flex items-center gap-3">
                <a href="{{ route('track.form') }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-white hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cek Status
                </a>
                <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-indigo-900 hover:bg-slate-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                    Admin
                </a>
            </nav>
        </div>
    <main class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto text-center mb-10">
            <h1 class="text-4xl font-bold text-slate-900">Lapor Anonim</h1>
            <p class="text-lg text-slate-600 mt-3">Ruang Aman untuk Bersuara</p>
        </div>

        <!-- Form -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="jenis_kejadian" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kejadian</label>
                    <select name="jenis_kejadian" id="jenis_kejadian" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Jenis Kejadian</option>
                        <option value="Pelecehan Seksual">Pelecehan Seksual</option>
                        <option value="Kekerasan Fisik">Kekerasan Fisik</option>
                        <option value="Kekerasan Verbal">Kekerasan Verbal</option>
                        <option value="Diskriminasi">Diskriminasi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-2">Waktu Kejadian</label>
                    <input type="datetime-local" name="tanggal_kejadian" id="tanggal_kejadian" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="bukti" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-indigo-500 transition-colors">
                        <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="updateFileName(this)">
                        <label for="bukti" class="cursor-pointer">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <p>Klik untuk upload bukti</p>
                                <p class="text-sm">Format: JPG, PNG, PDF. Maksimal 2MB</p>
                            </div>
                        </label>
                        <div id="file-name" class="mt-2 text-sm text-indigo-600 hidden"></div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 font-medium">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileName.textContent = 'File dipilih: ' + input.files[0].name;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
</body>
</html>