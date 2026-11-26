<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kapster extends Model
{
    protected $table = 'kapster';

    protected $primaryKey = 'id_kapster';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'no_hp',
        'spesialisasi',
        'status_aktif',
    ];
}