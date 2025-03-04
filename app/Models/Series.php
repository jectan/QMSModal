<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'prefix',
        'starting_value',
        'max_digit'
    ]; 
}
