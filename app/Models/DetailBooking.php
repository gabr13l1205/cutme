<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBooking extends Model
{
    protected $table = 'detail_booking';

    protected $primaryKey = 'id_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'id_layanan',
        'subtotal',
    ];
}