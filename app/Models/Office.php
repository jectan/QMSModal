<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Office extends Model
{
    protected $fillable = [
        'code',
        'name',
        'head'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function staff(){
        return $this->hasMany(Staff::class, 'office_id', 'id');
    }
}
