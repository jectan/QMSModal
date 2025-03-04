<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallerType extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
    ];
}
