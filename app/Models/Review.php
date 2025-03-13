<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $table = 'Review';
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
}
