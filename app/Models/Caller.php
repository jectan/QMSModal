<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caller extends Model
{
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'address',
        'contact_no',
        'email',
        // 'is_anonymous',
        'barangay_id',
        'created_by_id', 
        'updated_by_id'
    ];

    public function getFullNameAttribute()
    {
        $first_name = ucfirst($this->firstname);
        $last_name = ucfirst($this->middlename);
        $middle_name = ucfirst($this->lastname);
        return "{$first_name} {$middle_name} {$last_name}";
    }
    
    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }



}