<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpeakUp - Ruang Aman untuk Bersuara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white">
    <header class="bg-indigo-900">
        <div class="container mx-auto px-4 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-full bg-white/10 p-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c2.761 0 5-2.686 5-6S14.761-1 12-1 7 1.686 7 5s2.239 6 5 6zM3 21c0-3.313 2.687-6 6-6h6c3.313 0 6 2.687 6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-300">SpeakUp</p>
                    <p class="text-xs text-slate-400">Lapor Pelecehan & Diskriminasi</p>
                </div>
            </div>
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
    </header>

    <main class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-950 to-slate-900 opacity-95"></div>
        <div class="relative container mx-auto px-4 py-24">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-sm uppercase tracking-[0.4em] text-slate-400 mb-4">Ruang Aman untuk Bersuara</p>
                <h1 class="text-5xl font-extrabold leading-tight mb-6">Sistem Pelaporan Pelecehan dan Diskriminasi Berbasis Anonim</h1>
                <p class="text-lg text-slate-300 mb-10">Laporkan kejadian tanpa harus mengungkapkan identitas, kemudian pantau status laporan Anda dengan mudah menggunakan kode tracking unik.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('lapor.create') }}" class="inline-flex items-center justify-center rounded-full bg-white px-8 py-4 text-sm font-semibold text-indigo-900 shadow-lg shadow-indigo-900/20 hover:bg-slate-100 transition">
                        Lapor Sekarang (Anonim)
                    </a>
                    <a href="{{ route('track.form') }}" class="inline-flex items-center justify-center rounded-full border border-white/30 bg-white/10 px-8 py-4 text-sm font-semibold text-white hover:bg-white/20 transition">
                        Cek Status Laporan
                    </a>
                </div>
            </div>

            <div class="mt-20 grid gap-6 md:grid-cols-3">
                <div class="rounded-3xl bg-slate-950/80 p-8 shadow-xl shadow-black/20">
                    <div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-700/20 text-indigo-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">100% Anonim</h3>
                    <p class="text-slate-400">Anda tidak perlu login atau mengungkap identitas untuk membuat laporan. Privasi terjaga.</p>
                </div>
                <div class="rounded-3xl bg-slate-950/80 p-8 shadow-xl shadow-black/20">
                    <div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-700/20 text-indigo-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 19a7 7 0 100-14 7 7 0 000 14z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Tracking Real-time</h3>
                    <p class="text-slate-400">Gunakan kode unik untuk memantau perkembangan laporan dan tindak lanjut kapan saja.</p>
                </div>
                <div class="rounded-3xl bg-slate-950/80 p-8 shadow-xl shadow-black/20">
                    <div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-700/20 text-indigo-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 3v1m0 16v1m8.66-12.66l-.71.71M5.66 18.36l-.71.71M21 12h-1M4 12H3m16.66 4.66l-.71-.71M5.66 5.64l-.71-.71"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Tindak Lanjut Jelas</h3>
                    <p class="text-slate-400">Laporan dikelola oleh tim verifikasi dan ditindaklanjuti secara transparan oleh pihak kampus.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>