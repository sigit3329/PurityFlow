<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDSData extends Model
{
    use HasFactory;

    protected $table = 'tdsdata'; // Nama tabel yang benar

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'tds_value', 
        'total_galon',
        'quality'
    ];
}
