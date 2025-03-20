<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'position',
        'email',
        'user_id',
        'unitID',
    ];

    public function unit(){
        return $this->belongsTo(Unit::class, 'unitID', 'unitID');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getFullNameAttribute()
    {
        $first_name = ucfirst($this->firstname);
        $last_name = ucfirst($this->middlename);
        $middle_name = ucfirst($this->lastname);
        return "{$first_name} {$middle_name} {$last_name}";
    }
}
