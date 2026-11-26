<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKapster extends Model
{
    protected $table = 'jadwal_kapster';

    protected $primaryKey = 'id_jadwal';

    public $timestamps = false;

    protected $fillable = [
        'id_kapster',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status_slot',
    ];
}