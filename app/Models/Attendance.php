<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_attendances';

    protected $fillable = [
        'PersonnelNo',
        'CurrentDateTime',
        'CheckType',
        'idTR',
        'Verify',
        'SN',
        'Longitude',
        'Latitude',
        'IP',
    ];
}
