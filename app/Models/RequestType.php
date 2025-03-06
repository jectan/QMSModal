<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;
    public $table = 'RequestType';
    protected $primaryKey = 'requestTypeID';
    public $timestamps = false;
    protected $fillable=
    [
        'requestTypeDesc',
        'status',
    ];
}
