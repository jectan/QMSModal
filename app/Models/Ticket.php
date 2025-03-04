<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'caller_id',
        'call_type_id',
        'call_details',
        'call_datetime',
        'call_status',
        'status',
        'ticket_no',
        'office_assigned',
        'feedback',
        'rating',
        'ticket_category',
        'date_rated',
        'status_updated_at',
        'created_by_id', 
        'updated_by_id'

    ];

    public function caller(){
        return $this->belongsTo(Caller::class, 'caller_id', 'id');
    }

    public function callerType(){
        return $this->belongsTo(CallerType::class, 'call_type_id', 'id');
    }
    
    public function actions()
    {
        return $this->hasMany(OfficeAssignedTicket::class, 'ticket_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }
    
    public function logs(){
        return $this->hasMany(TicketLog::class, 'ticket_id', 'id');
    }

   
}
