<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Doctor.{doctorId}', function ($user, $doctorId) {
    return $user->doctor && (int) $user->doctor->id === (int) $doctorId;
});

Broadcast::channel('chatbot-answer.{doctorId}.{patientId}', function ($user, $doctorId, $patientId) {
    return (int) $user->doctor->id === (int) $doctorId && $user->doctor->patients->contains((int) $patientId);
});
