<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveDocument extends Model
{
    use HasFactory;
    public $table = 'ApproveDocument';
    protected $primaryKey = 'approveID';
    public $timestamps = false;
    protected $fillable=
    [
        'userID',
        'requestID',
        'approveComment',
        'approveDate',
        'approveStatus',
    ];
    
    public function approvedBy(){
        return $this->belongsTo(Staff::class, 'userID', 'id');
    }

    public function approvedDocument(){
        return $this->belongsTo(RequestDocument::class, 'requestID', 'requestID');
    }
}
