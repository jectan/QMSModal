<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeAssignedTicket extends Model
{
    protected $fillable = [
        'status',
        'caller_id',
        'ticket_id',
        'office_id',
        'estimated_date',
        'remarks',
        'work_done',
        'actual_date',
        'assigned_by_id',
        'status_updated_at',
        'status_updated_by_id'
    ];

    public function office(){
        return $this->belongsTo(office::class, 'office_id', 'id');
    }
    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
    public function assignedBy(){
        return $this->belongsTo(User::class, 'assigned_by_id', 'id');
    }
    public function statusUpdatedBy(){
        return $this->belongsTo(User::class, 'status_updated_by_id', 'id');
    }

    public function logs(){
        return $this->hasMany(OfficeAssignedTicketLog::class, 'assigned_office_ticket_id', 'id');
    }
}
