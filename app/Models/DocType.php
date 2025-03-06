<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    use HasFactory;
    public $table = 'DocType';
    protected $primaryKey = 'docTypeID';
    public $timestamps = false;
    protected $fillable=
    [
        'docTypeDesc',
        'status',
    ];
}
