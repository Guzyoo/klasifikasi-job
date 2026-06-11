<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAnalysis extends Model
{
    use HasFactory;

    // Izinkan Laravel mengisi kolom-kolom ini
    protected $fillable = [
        'file_path',
        'type',
        'result',
        'actual'
    ];
}
