<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_absen';

    protected $fillable = [
        'tgl_tarik',
        'tanggal',
        'mesin',
        'nik', // PersonnelNo
        'jam', // jam
        'status', // 1 atau 2. 1 in 2 out
        'f_export', // 2
        'idplant', // 1
        'Lokasi',
        'Longitude',
        'Latitude'
    ];
}
