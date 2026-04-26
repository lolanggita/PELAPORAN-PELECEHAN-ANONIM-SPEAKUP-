<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Berhasil - SpeakUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <h1 class="text-3xl font-bold mb-4 text-green-600">Laporan Berhasil Dikirim!</h1>
            <p class="text-lg mb-4">Terima kasih atas laporan Anda. Kami akan memprosesnya segera.</p>
            <p class="text-xl font-semibold mb-6">Kode Tracking Anda adalah: <span class="text-indigo-600">{{ session('kode_tracking') }}</span></p>
            <p class="text-sm text-gray-600 mb-4">Simpan kode ini untuk melacak status laporan Anda.</p>
            <a href="{{ route('track.form') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Lacak Laporan</a>
        </div>
    </div>
    
    <!-- SweetAlert2 untuk Notifikasi Berhasil -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Laporan Masuk!',
            text: 'Data Anda telah tersimpan dengan aman di database kami.',
            confirmButtonColor: '#4f46e5'
        });
    </script>
</body> 
</html>