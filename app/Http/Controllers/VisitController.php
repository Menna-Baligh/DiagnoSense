<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNextVisitRequest;
use App\Http\Resources\NextVisitResource;
use App\Http\Responses\ApiResponse;

class VisitController extends Controller
{
    public function store(StoreNextVisitRequest $request)
    {
        if ($request->action == 'save') {
            $visit = auth()->user()->doctor->visits()->create([
                'patient_id' => $request->patient_id,
                'next_visit_date' => $request->has_next_visit ? $request->next_visit_date : null,
                'status' => 'completed',
            ]);
        } else {
            $visit = auth()->user()->doctor->visits()->create([
                'patient_id' => $request->patient_id,
                'next_visit_date' => $request->next_visit_date ? $request->next_visit_date : null,
                'status' => 'draft',
            ]);
        }

        return ApiResponse::success(message: 'Visit created successfully', data: new NextVisitResource($visit), statusCode: 200);
    }
}
