<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Role extends Model
{
    protected $fillable = [
        'id',
        'name'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function roles(){
        return $this->hasOne(UserRole::class, 'role_id', 'id');
    }
}
