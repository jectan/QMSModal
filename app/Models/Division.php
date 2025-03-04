<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    public $table = 'Division';
    protected $primaryKey = 'divID';
    public $timestamps = false;
    protected $fillable=
    [
        'divName',
        'status',
    ];
}