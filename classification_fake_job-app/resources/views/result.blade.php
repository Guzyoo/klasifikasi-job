<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis - AI Job Shield</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased font-sans">

    <nav class="p-6">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md shadow-blue-200">G</div>
                <span class="text-lg font-bold tracking-tight">Gemini<span class="text-blue-600">Checker</span></span>
            </div>
            <a href="/" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-6">
        
        @php
            $status = strtoupper($data['data']['status'] ?? 'TIDAK DIKETAHUI');
            $keyakinan = $data['data']['keyakinan'] ?? '0';
            $alasan = $data['data']['alasan'] ?? 'Tidak ada analisis yang dapat ditampilkan.';
            $urlSumber = $data['url_sumber'] ?? null;
            
            // Tangkap data ekstraksi baru dari AI
            $deskripsi = $data['data']['deskripsi_pekerjaan'] ?? ['Tidak disebutkan'];
            $kriteria = $data['data']['kriteria'] ?? ['Tidak disebutkan'];
            $gaji = $data['data']['rentang_gaji'] ?? 'Tidak disebutkan';

            if (str_contains($status, 'PALSU') || str_contains($status, 'SCAM')) {
                $bgBox = 'bg-red-50 border-red-200';
                $iconBox = 'bg-red-100 text-red-600';
                $textStatus = 'text-red-700';
                $dotKeyakinan = 'bg-red-500 shadow-red-300';
                $icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'; 
            } else {
                $bgBox = 'bg-green-50 border-green-200';
                $iconBox = 'bg-green-100 text-green-600';
                $textStatus = 'text-green-700';
                $dotKeyakinan = 'bg-green-500 shadow-green-300';
                $icon = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'; 
            }
        @endphp 

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 md:p-12 border border-slate-100 relative overflow-hidden">
            
            <div class="text-center mb-10 relative z-10">
                <h2 class="text-2xl font-extrabold mb-2">Laporan Analisis AI</h2>
                <p class="text-slate-500">Hasil ekstraksi dan deteksi kecerdasan buatan.</p>
            </div>

            <div class="rounded-3xl p-8 border-2 {{ $bgBox }} mb-10 text-center relative z-10 shadow-sm">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 {{ $iconBox }} rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-sm uppercase tracking-widest font-bold {{ $textStatus }} mb-2">Status Keputusan</div>
                <div class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">
                    {{ $status }}
                </div>
                <div class="inline-flex items-center gap-2 bg-white px-5 py-2.5 rounded-full shadow-sm text-sm font-bold border border-slate-100 text-slate-700">
                    <span class="w-3 h-3 rounded-full animate-pulse shadow-sm {{ $dotKeyakinan }}"></span>
                    Confidence Score: {{ $keyakinan }}%
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 relative z-10">
                <div class="p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Deskripsi Pekerjaan
                    </h4>
                    <ul class="list-disc list-inside text-slate-700 space-y-1 text-sm">
                        @if(is_array($deskripsi))
                            @foreach($deskripsi as $desc)
                                <li>{{ $desc }}</li>
                            @endforeach
                        @else
                            <li>{{ $deskripsi }}</li>
                        @endif
                    </ul>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-amber-50/50 rounded-2xl border border-amber-100">
                        <h4 class="font-bold text-amber-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Kriteria & Syarat
                        </h4>
                        <ul class="list-disc list-inside text-slate-700 space-y-1 text-sm">
                            @if(is_array($kriteria))
                                @foreach($kriteria as $krit)
                                    <li>{{ $krit }}</li>
                                @endforeach
                            @else
                                <li>{{ $kriteria }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                        <h4 class="font-bold text-emerald-900 mb-1 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Rentang Gaji
                        </h4>
                        <p class="text-slate-800 font-semibold text-lg">{{ $gaji }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6 relative z-10">
                <h3 class="font-bold text-xl border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Detail Analisis & Alasan
                </h3>
                
                <div class="p-6 bg-slate-50/80 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-slate-700 leading-relaxed text-justify">
                        {{ $alasan }}
                    </p>
                </div>

                @if($urlSumber && $urlSumber !== 'Tidak ada URL')
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-slate-800 mb-1">Sumber Tautan</h4>
                        <a href="{{ $urlSumber }}" target="_blank" class="text-blue-600 text-sm hover:underline hover:text-blue-800 line-clamp-1 break-all">
                            {{ $urlSumber }}
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-10 flex justify-center">
                <button onclick="window.print()" class="w-full sm:w-auto px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Simpan Laporan (PDF)
                </button>
            </div>

        </div>
    </main>
</body>
</html>