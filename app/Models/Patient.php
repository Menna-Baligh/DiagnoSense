<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        // 'doctor_id',
//        'phone',
//        'age',
//        'gender',
//        'profile_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
