<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    public $table = 'Unit';
    protected $primaryKey = 'unitID';
    public $timestamps = false;
    protected $fillable=
    [
        'unitName',
        'divID',
        'status',
    ];

    public function getDivision()
    {
        return $this->hasMany(Division::class, 'divID', 'divID');
    }
}