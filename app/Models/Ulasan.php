<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'id_pelanggan',
        'rating',
        'komentar',
        'tgl_ulasan',
    ];
}