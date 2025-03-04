<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeAssignedTicketLog extends Model
{
    protected $fillable = [
        'status',
        'assigned_by_id',
        'assigned_office_ticket_id',
        'remarks',
        'office_id'
    ];

    public function assignedby(){
        return $this->belongsTo(User::class, 'assigned_by_id', 'id');
    }
}
