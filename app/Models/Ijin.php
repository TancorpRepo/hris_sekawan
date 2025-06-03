<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ijin extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'tbl_ijins';

    protected $fillable = [
        'PersonnelNo',
        'jam',
        'idTR',
        'Verify',
        'SN',
        'Longitude',
        'Latitude',
        'IP',
        'Keterangan',
    ];
}
