<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{
    protected $fillable = [
        'case_room_id',
        'doctor_id',
        'type',
        'joined_at'
    ];

    public function room()
    {
        return $this->belongsTo(CaseRoom::class,'case_room_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');
    }
}
