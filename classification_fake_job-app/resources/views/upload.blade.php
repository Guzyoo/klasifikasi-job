<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Job Shield - Deteksi Loker Palsu</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased">

    <nav class="p-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">G</div>
                <span class="text-xl font-bold tracking-tight">Gemini<span class="text-blue-600">Checker</span></span>
            </div>
            <div class="text-sm font-medium text-slate-500 italic">Project Tugas Akhir - UNMA</div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight mb-4">Analisis Keaslian Lowongan Kerja</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Lindungi diri dari penipuan. Unggah bukti lowongan kerja, dan biarkan AI Gemini kami menganalisis kebenarannya.</p>
        </div>

        <div class="card-container">
            @if(session('error') || $errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-50 border-2 border-red-100 flex items-start gap-3 animate-pulse">
                    <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Analisis Gagal</h3>
                        
                        @if(session('error'))
                            <p class="text-sm text-red-600 mt-1">{{ session('error') }}</p>
                        @endif

                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600 mt-1">- {{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            <form action="{{ route('analyze.job') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="space-y-8">
                    
                    <div class="group">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Link Sumber Lowongan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </div>
                            <input type="url" name="job_link" placeholder="https://linkedin.com/jobs/..." class="input-field pl-11">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unggah Poster atau Video Loker</label>
                        <div id="dropzone" class="upload-area group" onclick="document.getElementById('job_file').click()">
                            <input type="file" name="job_file" id="job_file" class="hidden" accept="image/*,video/*" onchange="previewFile()">
                            
                            <div id="placeholder-content">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-700">Klik untuk pilih file atau seret file ke sini</p>
                                <p class="text-xs text-slate-500 mt-1">Mendukung Gambar (PNG, JPG) atau Video (MP4)</p>
                            </div>

                            <div id="preview-container" class="hidden mt-4">
                                <img id="preview-image" class="mx-auto max-h-48 rounded-lg mb-4 hidden shadow-md">
                                <video id="preview-video" class="mx-auto max-h-48 rounded-lg mb-4 hidden shadow-md" controls></video>
                                <button type="button" onclick="resetFile(event)" class="text-xs font-bold text-red-500 uppercase tracking-wider hover:text-red-700 transition">Hapus File</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Chat / Teks Lowongan</label>
                        <textarea name="job_text" rows="4" placeholder="Tempel teks lowongan atau isi chat penawaran kerja di sini..." class="input-field"></textarea>
                    </div>

                    <button type="submit" id="submit-btn" class="btn-primary">
                        <span id="btn-text">Mulai Analisis Sekarang</span>
                        <span id="btn-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            AI Sedang Menganalisis...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>