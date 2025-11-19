<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CaseRoom extends Model
{
    protected $fillable = [
        'patient_id',
        'primary_doctor_id',
        'title',
        'slug',
        'is_active'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function Doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function members()
    {
        return $this->hasMany(RoomMember::class);
    }
}
