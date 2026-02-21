<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function store(StorePatientRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'type' => 'patient',
            'password' => Str::random(10),
        ]);
        $patient = Patient::query()->create([
            'user_id' => $user->id,
            'age' => $request->age ?? null,
            'gender' => $request->gender ?? null,
            'national_id' => $request->national_id ?? null,
        ]);
        $patient->doctors()->attach($request->user()->doctor->id);
        MedicalHistory::query()->create([
            'patient_id' => $patient->id,
            'is_smoker' => $request->is_smoker ?? null,
            'previous_surgeries' => $request->previous_surgeries ?? null,
            'chronic_diseases' => $request->chronic_diseases ?? null,
            'previous_surgeries_name' => $request->previous_surgeries_name ?? null,
            'medications' => $request->medications ?? null,
            'allergies' => $request->allergies ?? null,
            'family_history' => $request->family_history ?? null,
        ]);
        $reportsTypes = ['lab', 'radiology', 'medical_history'];
        $filesForAI = [];
        foreach ($reportsTypes as $type) {
            if ($request->hasFile($type)) {
                foreach ($request->file($type) as $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = Storage::disk('azure')->putFileAs($type, $file, $fileName);
                    $mimeType = $file->getMimeType();
                    Report::query()->create([
                        'patient_id' => $patient->id,
                        'type' => $type,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'mime_type' => $mimeType,
                    ]);
                    $filesForAI[] = Storage::disk('azure')->temporaryUrl(
                        $filePath,
                        now()->addMinutes(10)
                    );
                }
            }
        }
        //* only for testing i will delete it later
        return response()->json([
        'status' => 'success',
        'ai_links' => $filesForAI
        ], 201);

        // call ai
        // return response
    }
}
