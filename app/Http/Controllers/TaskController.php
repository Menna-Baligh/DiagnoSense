<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        $currentDoctor = auth()->user()->doctor;
        $task = $currentDoctor->tasks()->create([
            'title' => $request->title,
            'description' => $request->description ?? null,
            'patient_id' => $request->patient_id,
        ]);
        $nextVisit = $currentDoctor->appointments()->create([
            'appointment_date' => $request->appointment_date,
            'patient_id' => $request->patient_id,
        ]);
        $task['next_visit'] = $nextVisit->appointment_date;

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => new TaskResource($task),
            'next' => $request->get('action') == 'save_and_create_another' ? 'create' : 'index',
        ]);
    }
}
