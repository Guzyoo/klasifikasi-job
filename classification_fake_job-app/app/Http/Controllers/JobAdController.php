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

        if ($this->requestExceedsPostLimit($request)) {
            return back()->with('error', 'Ukuran total request melebihi batas PHP yang aktif. File diterima: ' . $this->formatBytes((int) $request->server('CONTENT_LENGTH', 0)) . '. Batas post_max_size: ' . ini_get('post_max_size') . '.');
        }

        $uploadedFile = $request->file('job_file');
        if ($uploadedFile && !$uploadedFile->isValid()) {
            return back()->with('error', 'Upload file gagal: ' . $this->uploadErrorMessage($uploadedFile->getError()) . ' Kode error PHP: ' . $uploadedFile->getError() . '. Limit aktif: upload_max_filesize=' . ini_get('upload_max_filesize') . ', post_max_size=' . ini_get('post_max_size') . '.');
        }

        // 1. VALIDASI INPUT (Biar user fleksibel, tapi nggak boleh kosong semua)
        $request->validate([
            'job_file' => 'nullable|file|mimes:jpeg,png,jpg,mp4|max:51200', // Max 50MB
            'job_link' => 'nullable|url',
            'job_text' => 'nullable|string',
        ], [
            'job_file.uploaded' => 'File gagal diupload. Pastikan ukuran video maksimal 50 MB dan konfigurasi PHP mengizinkan upload file besar.',
            'job_file.max' => 'Ukuran file maksimal 50 MB.',
            'job_file.mimes' => 'Format file harus JPG, PNG, JPEG, atau MP4.',
        ]);

        if (!$request->job_file && !$request->job_link && !$request->job_text) {
            return back()->with('error', 'Masukkan minimal satu bukti loker (Poster, Link, atau Teks) untuk dianalisis.');
        }

        // 2. SIAPKAN PENGIRIMAN KE FASTAPI 
        $fastApiUrl = 'http://127.0.0.1:8001/analyze'; 

        try {
            // Kita paksa kurirnya selalu pakai format Multipart Form Data
            $client = Http::timeout(180)->asMultipart(); 
            $fileStream = null;

            // 1. Kalau ada file, baru kita pakai attach()
            if ($request->hasFile('job_file')) {
                $file = $request->file('job_file');
                $fileStream = @fopen($file->getPathname(), 'r');

                if (!is_resource($fileStream)) {
                    return back()->with('error', 'File upload tidak bisa dibaca oleh server. Coba unggah ulang file atau gunakan file lain.');
                }

                $client = $client->attach(
                    'file',
                    $fileStream,
                    $file->getClientOriginalName(),
                    ['Content-Type' => $file->getMimeType() ?: $file->getClientMimeType()]
                );
            }

            // 2. Teks dan Link masukin ke sini (Gak boleh di-attach!)
            $response = $client->post($fastApiUrl, [
                'text' => $request->job_text ?? '',
                'link' => $request->job_link ?? '',
            ]);

            if (is_resource($fileStream)) {
                fclose($fileStream);
            }

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
            if (isset($fileStream) && is_resource($fileStream)) {
                fclose($fileStream);
            }

            // JURUS ANTI TEBAK-TEBAKAN
            return back()->with('error', 'Crash di Laravel: ' . $e->getMessage());
        }
    }

    private function requestExceedsPostLimit(Request $request): bool
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);

        return $contentLength > 0 && $contentLength > $this->phpSizeToBytes(ini_get('post_max_size'));
    }

    private function phpSizeToBytes(string $size): int
    {
        $size = trim($size);
        $unit = strtolower(substr($size, -1));
        $value = (float) $size;

        return match ($unit) {
            'g' => (int) ($value * 1024 * 1024 * 1024),
            'm' => (int) ($value * 1024 * 1024),
            'k' => (int) ($value * 1024),
            default => (int) $value,
        };
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi upload_max_filesize di konfigurasi PHP.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas MAX_FILE_SIZE dari form.',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian. Coba upload ulang dengan koneksi yang stabil.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diterima server.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary upload PHP tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file upload ke disk.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.',
            default => 'PHP menolak file upload.',
        };
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);

        return round($bytes / (1024 ** $power), 2) . ' ' . $units[$power];
    }
}
