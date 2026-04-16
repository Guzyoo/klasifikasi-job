<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib dipanggil untuk nembak API

class JobAdController
{
    public function analyze(Request $request)
    {
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
        // (Pastikan URL ini sesuai dengan port server Python lu nanti)
        $fastApiUrl = 'http://127.0.0.1:8001/analyze'; 
        
        try {
            // Kita paksa kurirnya selalu pakai format Multipart Form Data
            $client = Http::timeout(60)->asMultipart(); 

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
                return view('result', ['data' => $result]);
            } else {
                $errorMsg = $response->json('detail') ?? 'Waduh, server AI gagal merespons.';
                return back()->with('error', 'Error dari AI: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            // JURUS ANTI TEBAK-TEBAKAN: 
            // Kita tampilin pesan error aslinya dari Laravel biar ketahuan kalau dia ngambek lagi!
            return back()->with('error', 'Crash di Laravel: ' . $e->getMessage());
        }
    }
} 