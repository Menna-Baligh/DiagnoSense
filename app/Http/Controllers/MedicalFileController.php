<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\MedicalFileResource;
use App\Http\Resources\LabReportResource;

class MedicalFileController extends Controller
{
    /**
     * Medical History Files
     */
    public function medicalHistoryFiles(Request $request)
    {
        $user = $request->user();

        if (!$user->patient) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $patient = $user->patient;

        $files = $patient->reports()
            ->where('type', 'medical_history')
            ->latest()
            ->get();

        return MedicalFileResource::collection($files);
    }

    /**
     * Lab Reports
     */
    public function labReports(Request $request)
    {
        $user = $request->user();

        if (!$user->patient) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $patient = $user->patient;

        $reports = $patient->reports()
            ->where('type', 'lab') 
            ->with(['patient.visits.doctor.user']) 
            ->latest()
            ->get();

        return LabReportResource::collection($reports);
    }

    public function radiologyReports(Request $request)
{
    $user = $request->user();

    if (!$user->patient) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    $patient = $user->patient;

    $reports = $patient->reports()
        ->where('type', 'radiology') 
        ->latest()
        ->get();

    return \App\Http\Resources\RadiologyReportResource::collection($reports);
}
}