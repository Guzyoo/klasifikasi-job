<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobAnalysis;

class AdminController
{
    public function index()
    {
        $analyses = JobAnalysis::orderBy('created_at', 'desc')->get();
        
        $totalData = $analyses->count();
        $validatedData = $analyses->whereNotNull('actual');
        $tervalidasi = $validatedData->count();
        $belumValidasi = $totalData - $tervalidasi;

        // --- MENGHITUNG METRIK EVALUASI (AKURASI & F1-SCORE) ---
        $akurasi = 0;
        $f1_macro = 0;

        if ($tervalidasi > 0) {
            $correct = 0;
            $classes = ['ASLI', 'PALSU', 'BUKAN LOWONGAN'];
            $f1_scores = [];

            // 1. Hitung Akurasi Dasar
            foreach ($validatedData as $d) {
                // Gemini kadang nambahin spasi, kita bersihin dulu
                $res = trim(strtoupper($d->result));
                $act = trim(strtoupper($d->actual));
                if (str_contains($res, $act)) {
                    $correct++;
                }
            }
            $akurasi = round(($correct / $tervalidasi) * 100, 2);

            // 2. Hitung F1-Score Macro (Kaya di Jurnal Machine Learning!)
            foreach ($classes as $c) {
                $tp = 0; $fp = 0; $fn = 0;
                
                foreach ($validatedData as $d) {
                    $res = trim(strtoupper($d->result));
                    $act = trim(strtoupper($d->actual));
                    
                    // Kita pakai str_contains biar aman kalau format AI agak meleset
                    $isResMatch = str_contains($res, $c);
                    $isActMatch = $act === $c;

                    if ($isResMatch && $isActMatch) $tp++;
                    if ($isResMatch && !$isActMatch) $fp++;
                    if (!$isResMatch && $isActMatch) $fn++;
                }

                $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
                $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
                $f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;
                
                $f1_scores[] = $f1;
            }

            // Rata-ratakan F1 Score dari semua kelas
            $f1_macro = round((array_sum($f1_scores) / count($f1_scores)) * 100, 2);
        }

        return view('admin', compact('analyses', 'totalData', 'tervalidasi', 'belumValidasi', 'akurasi', 'f1_macro'));
    }

    public function validateData(Request $request, $id)
    {
        $request->validate([
            'actual' => 'required|in:ASLI,PALSU,BUKAN LOWONGAN'
        ]);

        $job = JobAnalysis::findOrFail($id);
        $job->actual = $request->actual;
        $job->save();

        return back()->with('success', 'Data loker #' . $id . ' tervalidasi: ' . $request->actual . '. Metrik F1-Score diperbarui!');
    }
}