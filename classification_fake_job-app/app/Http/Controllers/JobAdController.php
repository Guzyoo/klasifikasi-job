<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib dipanggil untuk nembak API
use App\Models\JobAnalysis;

class JobAdController
{
    public function analyze(Request $request)
    {
        set_time_limit(180);
        
        if ($request->hasFile('job_file') && !$request->file('job_file')->isValid()) {
            dd('ALASAN ASLI PHP NOLAK: ' . $request->file('job_file')->getErrorMessage());
        }
        // 1. VALIDASI INPUT (Biar user fleksibel, tapi nggak boleh kosong semua)
        $request->validate([
            'job_file' => 'nullable|file|mimes:jpeg,png,jpg,mp4|max:10240', // Max 10MB
            'job_link' => 'nullable|url',
            'job_text' => 'nullable|string',
        ]);

        if (!$request->job_file && !$request->job_link && !$request->job_text) {
            return back()->with('error', 'Masukkan minimal satu bukti loker (Poster, Link, atau Teks) untuk dianalisis.');
        }

        // 2. SIAPKAN PENGIRIMAN KE FASTAPI 
        $fastApiUrl = 'http://127.0.0.1:8001/analyze'; 

        try {
            // Kita paksa kurirnya selalu pakai format Multipart Form Data
            $client = Http::timeout(180)->asMultipart(); 

            // 1. Kalau ada file, baru kita pakai attach()
            if ($request->hasFile('job_file')) {
                $file = $request->file('job_file');
                $client = $client->attach(
                    'file', file_get_contents($file), $file->getClientOriginalName()
                );
            }

            // 2. Teks dan Link masukin ke sini (Gak boleh di-attach!)
            $response = $client->post($fastApiUrl, [
                'text' => $request->job_text ?? '',
                'link' => $request->job_link ?? '',
            ]);

            // 3. TANGANI HASIL DARI AI
            if ($response->successful()) {
                $result = $response->json();

                // =========================================================
                // [+] FITUR BARU: SIMPAN KE DATABASE BUAT EVALUASI SKRIPSI
                // =========================================================
                try {
                    $inputType = 'text';
                    $filePath = null;

                    if ($request->hasFile('job_file')) {
                        $inputType = 'file';
                        // Simpan gambar ke folder public/storage/job_files
                        $filePath = $request->file('job_file')->store('job_files', 'public');
                    } elseif ($request->filled('job_link')) {
                        $inputType = 'link';
                    }

                    // Tembak ke Database!
                    JobAnalysis::create([
                        'file_path' => $filePath,
                        'type' => $inputType,
                        'result' => $result['data']['status'] ?? 'ERROR',
                        'actual' => null // Kosongin dulu, nanti lu isi pas mau ngitung F1 Score
                    ]);
                } catch (\Exception $dbError) {
                    // Kalau database gagal nyimpen, biarin aja lewat, jangan sampe user kena error
                    \Log::error('Gagal simpan ke DB: ' . $dbError->getMessage());
                }
                // =========================================================

                return view('result', ['data' => $result]);
            } else {
                $errorMsg = $response->json('detail') ?? 'Waduh, server AI gagal merespons.';
                return back()->with('error', 'Error dari AI: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            // JURUS ANTI TEBAK-TEBAKAN
            return back()->with('error', 'Crash di Laravel: ' . $e->getMessage());
        }
    }
}