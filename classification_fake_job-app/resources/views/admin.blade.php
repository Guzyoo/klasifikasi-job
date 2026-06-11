<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Job Shield AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl z-20 hidden md:flex">
        <div class="p-6 border-b border-slate-800">
            <h2 class="text-xl font-extrabold tracking-tight">Gemini<span class="text-blue-500">Checker</span></h2>
            <p class="text-xs text-slate-400 mt-1">Admin Panel</p>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-xl font-semibold shadow-md shadow-blue-900/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Evaluasi Dataset
            </a>
            <a href="/" class="flex items-center gap-3 text-slate-400 hover:text-white hover:bg-slate-800 px-4 py-3 rounded-xl font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Halaman Depan Web
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-slate-50/50">
        <header class="bg-white px-8 py-5 border-b border-slate-200 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Validasi & Performa AI</h1>
            <div class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200">
                Gemini 2.5 Flash
            </div>
        </header>

        <div class="p-8 max-w-7xl mx-auto w-full">
            
            @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 font-semibold shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-slate-700">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Skor Performa Model
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-gradient-to-br from-purple-600 to-indigo-700 p-6 rounded-2xl shadow-lg text-white">
                    <div class="text-purple-200 text-sm font-bold mb-1 tracking-wide">F1-SCORE (MACRO)</div>
                    <div class="text-4xl font-black drop-shadow-md">{{ $f1_macro }}%</div>
                    <div class="mt-2 text-xs text-purple-200">Keseimbangan deteksi asli & palsu</div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-2xl shadow-lg text-white">
                    <div class="text-blue-100 text-sm font-bold mb-1 tracking-wide">AKURASI TOTAL</div>
                    <div class="text-4xl font-black drop-shadow-md">{{ $akurasi }}%</div>
                    <div class="mt-2 text-xs text-blue-100">Tebakan benar vs Keseluruhan</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="text-slate-400 text-sm font-bold mb-1 tracking-wide">DATA TERVALIDASI</div>
                    <div class="text-4xl font-black text-slate-800">{{ $tervalidasi }} <span class="text-lg text-slate-400 font-medium">/ {{ $totalData }}</span></div>
                    <div class="mt-2 text-xs text-slate-500">Telah diberi label *Ground Truth*</div>
                </div>
                <div class="bg-red-50 p-6 rounded-2xl shadow-sm border border-red-100">
                    <div class="text-red-400 text-sm font-bold mb-1 tracking-wide">BELUM VALIDASI</div>
                    <div class="text-4xl font-black text-red-600">{{ $belumValidasi }}</div>
                    <div class="mt-2 text-xs text-red-400">Menunggu pengecekan manual</div>
                </div>
            </div>

            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-slate-700">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                Dataset & Ground Truth
            </h3>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Tipe Input</th>
                                <th class="px-6 py-4">Tebakan AI (Result)</th>
                                <th class="px-6 py-4">Validasi Asli (Actual)</th>
                                <th class="px-6 py-4">Aksi Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($analyses as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold text-slate-500">#{{ $item->id }}</td>
                                <td class="px-6 py-4 uppercase font-bold text-xs text-slate-400">
                                    <span class="bg-slate-100 px-2 py-1 rounded">{{ $item->type }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if(str_contains($item->result, 'PALSU') || str_contains($item->result, 'SCAM'))
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md font-bold text-xs shadow-sm">PALSU</span>
                                    @elseif(str_contains($item->result, 'ASLI'))
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md font-bold text-xs shadow-sm">ASLI</span>
                                    @else
                                        <span class="bg-slate-200 text-slate-700 px-3 py-1 rounded-md font-bold text-xs shadow-sm">BUKAN LOKER</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->actual)
                                        <span class="text-slate-800 font-bold uppercase flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $item->actual }}
                                        </span>
                                    @else
                                        <span class="text-amber-500 font-semibold italic flex items-center gap-1.5">
                                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                                            Belum Validasi
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.validate', $item->id) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <select name="actual" class="bg-white border border-slate-300 text-slate-700 rounded-lg text-xs p-2 focus:ring-2 focus:ring-blue-500 outline-none font-medium shadow-sm">
                                            <option value="" disabled selected>Pilih Ground Truth...</option>
                                            <option value="ASLI">ASLI</option>
                                            <option value="PALSU">PALSU</option>
                                            <option value="BUKAN LOWONGAN">BUKAN LOWONGAN</option>
                                        </select>
                                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-lg text-xs shadow-md transition">
                                            Set Label
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Belum ada data loker yang dikumpulkan ke dalam dataset.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>