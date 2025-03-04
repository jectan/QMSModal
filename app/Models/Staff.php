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
        'job_title',
        'contact_no',
        'email',
        'user_id',
        'office_id',
        'role_id'
    ];

    public function office(){
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
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
