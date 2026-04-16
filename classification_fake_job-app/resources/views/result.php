<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis - AI Job Shield</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased font-sans">

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-8">
            <a href="/" class="text-sm font-semibold text-brand-600 hover:text-brand-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="card-container p-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold mb-2">Laporan Analisis AI</h2>
                <p class="text-slate-500">Hasil deteksi berdasarkan data yang kamu unggah</p>
            </div>

            <div class="rounded-3xl p-8 border-2 border-brand-100 bg-brand-50 mb-8 text-center">
                <div class="text-sm uppercase tracking-widest font-bold text-brand-600 mb-2">Status Lowongan</div>
                <div class="text-5xl font-extrabold text-slate-900 mb-4">TERINDIKASI ASLI</div>
                <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    Tingkat Keyakinan: 98.5%
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="font-bold text-lg border-b pb-2">Analisis Mendalam Gemini AI</h3>
                <div class="grid gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="font-bold text-sm text-slate-700 mb-1">Verifikasi Perusahaan</h4>
                        <p class="text-slate-600 text-sm">Domain email dan profil perusahaan sesuai dengan data resmi di LinkedIn dan direktori perusahaan.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="font-bold text-sm text-slate-700 mb-1">Analisis Linguistik</h4>
                        <p class="text-slate-600 text-sm">Struktur bahasa profesional dan tidak ditemukan pola pemaksaan atau janji gaji yang tidak masuk akal.</p>
                    </div>
                </div>
            </div>

            <button onclick="window.print()" class="btn-primary mt-10 !bg-slate-100 !text-slate-900 hover:!bg-slate-200">
                Simpan Hasil (PDF)
            </button>
        </div>
    </main>
</body>
</html>