<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredDoc extends Model
{
    use HasFactory;
    public $table = 'RegisteredDoc';
    protected $primaryKey = 'regDocID';
    public $timestamps = false;
    protected $fillable=
    [
        'requestID',
        'effectivityDate',
        'docFile',
    ];
}
