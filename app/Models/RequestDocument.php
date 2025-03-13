<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    use HasFactory;
    public $table = 'RequestDocument';
    protected $primaryKey = 'requestID';
    public $timestamps = false;
    protected $fillable=
    [  
        'requestID',
        'requestTypeID',
        'docTypeID',
        'docRefCode',
        'currentRevNo',
        'docTitle',
        'requestReason',  
        'userID',  
        'requestFile',  
        'requestDate',
        'requestStatus',
    ];

    public function createdBy(){
        return $this->belongsTo(Staff::class, 'userID', 'id');
    }

    public function DocumentType(){
        return $this->belongsTo(DocType::class, 'docTypeID', 'docTypeID');
    }

    public function RequestType(){
        return $this->belongsTo(RequestType::class, 'requestTypeID', 'requestTypeID');
    }
}