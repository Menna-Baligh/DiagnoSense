<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CaseRoom;
use App\Models\RoomMember;
use App\Http\Requests\Room\StoreCaseRoomRequest;
use App\Http\Responses\ApiResponse;
class CaseRoomController extends Controller
{
    public function store(StoreCaseRoomRequest $request)
    {
        $user = $request->user();
        $doctor = $user->doctor ?? null;

        if (!$doctor) {
            return ApiResponse::error('Only doctors can create case rooms', null,403);
        }

        $patientId = $request->input('patient_id');

        $existing = CaseRoom::where('patient_id', $patientId)->where('is_active', true)->first();
        if ($existing) {
            return ApiResponse::success('An active case room already exists for this patient.', [
                'existing' => $existing
            ], 200);
        }

        do {
            $slug = Str::random(48);
        } while (CaseRoom::where('slug', $slug)->exists());

        $room = CaseRoom::create([
            'patient_id' => $patientId,
            'primary_doctor_id' => $doctor->id,
            'title' => $request->input('title') ?: "Case#{$patientId}",
            'slug' => $slug,
            'is_active' => true,
        ]);

        RoomMember::firstOrCreate(
            ['case_room_id' => $room->id, 'doctor_id' => $doctor->id],
            ['type' => 'primary', 'joined_at' => now()]
        );

        $data = [
            'id' => $room->id,
            'slug' => $room->slug,
            'title' => $room->title,
            'patient_id' => $room->patient_id,
            'primary_doctor_id' => $room->primary_doctor_id,
            'created_at' => $room->created_at->format('Y-m-d H:i:s'),
        ];

        return ApiResponse::success('Case room created successfully.', $data, 201);
    }

/*
    public function show(Request $request, CaseRoom $caseRoom)
    {
        $user = $request->user();
        if (!$user || $user->type !== 'doctor') {
            return ApiResponse::error('Only doctors are allowed', null,403);
        }

        $doctor = $user->doctor ?? null;
        if (!$doctor) {
            return ApiResponse::error('Doctor not found',null,403);
        }

        $isPrimary = $caseRoom->doctor_id === $doctor->id;
        $isMember = $caseRoom->members()->where('doctor_id', $doctor->id)->exists();

        if (!($isPrimary || $isMember)) {
            return ApiResponse::error('You are not a member of this case room', null,403);
        }

        $caseRoom->load(['patient.user', 'primaryDoctor.user', 'members.doctor.user']);

        return ApiResponse::success('Case room details.', $caseRoom, 200);
    }
*/
}
