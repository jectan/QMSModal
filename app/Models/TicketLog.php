<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    protected $fillable = [
        'status',
        'assigned_by_id',
        'ticket_id',
        'remarks',
        'office_id'
    ];

    public function assignedby(){
        return $this->belongsTo(User::class, 'assigned_by_id', 'id');
    }

    public function getOffice(){
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
  
}
