<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $primaryKey = 'id_booking';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'id_kapster',
        'id_jadwal',
        'tgl_booking',
        'status',
        'total_biaya',
    ];
}