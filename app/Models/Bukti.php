<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    use HasFactory;

    protected $table = 'buktis';
    protected $primaryKey = 'id_bukti';

    protected $fillable = [
        'id_laporan',
        'file_bukti',
        'tipe_file',
    ];

    /**
     * Relasi ke Laporan
     */
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }
}