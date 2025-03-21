<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewDocument extends Model
{
    use HasFactory;
    public $table = 'ReviewDocument';
    protected $primaryKey = 'reviewID';
    public $timestamps = false;
    protected $fillable=
    [
        'userID',
        'requestID',
        'reviewComment',
        'reviewDate',
        'reviewStatus',
    ];
    
    public function reviewedBy(){
        return $this->belongsTo(Staff::class, 'userID', 'id');
    }

    public function reviewedDocument(){
        return $this->belongsTo(RequestDocument::class, 'requestID', 'requestID');
    }
}
