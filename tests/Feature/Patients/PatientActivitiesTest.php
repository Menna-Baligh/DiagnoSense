<?php

use App\Models\KeyPoint;
use App\Models\Patient;
use App\Models\AiAnalysisResult;

beforeEach(function () {

    $this->doctorUser = createDoctorWithBilling();

    $this->doctor = $this->doctorUser->doctor;

    $this->patient = Patient::factory()->create();

    $this->patient->doctors()->attach($this->doctor->id);

    $this->actingAs($this->doctorUser);
});

describe('Patient Activities', function () {
it('returns patient activity history successfully', function () {

    $analysis = AiAnalysisResult::factory()->create([
        'patient_id' => $this->patient->id,
    ]);

    KeyPoint::factory()->create([
        'ai_analysis_result_id' => $analysis->id,
        'is_ai_generated' => false,
    ]);

    KeyPoint::factory()->create([
        'ai_analysis_result_id' => $analysis->id,
        'is_ai_generated' => false,
    ]);

    $response = $this->getJson(
        route('patients.activities', [
            'patient' => $this->patient->id,
        ])
    );

    $response->assertStatus(200)
        ->assertJsonPath(
            'message',
            'Activity history retrieved successfully'
        )
        ->assertJsonCount(2, 'data');
});

it('returns activities ordered by latest first', function () {

    $analysis = AiAnalysisResult::factory()->create([
        'patient_id' => $this->patient->id,
    ]);

    $oldKeyPoint = KeyPoint::factory()->create([
        'ai_analysis_result_id' => $analysis->id,
        'is_ai_generated' => false,
        'created_at' => now()->subDay(),
    ]);

    sleep(1);

    $latestKeyPoint = KeyPoint::factory()->create([
        'ai_analysis_result_id' => $analysis->id,
        'is_ai_generated' => false,
    ]);

    $response = $this->getJson(
        route('patients.activities', [
            'patient' => $this->patient->id,
        ])
    );

    $response->assertStatus(200);

    expect($response->json('data.0.model_id'))
        ->toBe($latestKeyPoint->id);

    expect($response->json('data.1.model_id'))
        ->toBe($oldKeyPoint->id);
});
    it('returns 403 when doctor tries to access unauthorized patient activities', function () {

        $anotherDoctorUser = createDoctorWithBilling();

        $anotherPatient = Patient::factory()->create();

        $anotherPatient->doctors()->attach(
            $anotherDoctorUser->doctor->id
        );

        $response = $this->getJson(
            route('patients.activities', [
                'patient' => $anotherPatient->id,
            ])
        );

        $response->assertStatus(403)
            ->assertJsonPath(
                'message',
                'An error occurred while retrieving patient activities.'
            );
    });

    it('returns 401 for guest user', function () {

        auth()->logout();

        $response = $this->getJson(
            route('patients.activities', [
                'patient' => $this->patient->id,
            ])
        );

        $response->assertStatus(401);
    });

    it('returns empty array when patient has no activities', function () {

        $response = $this->getJson(
            route('patients.activities', [
                'patient' => $this->patient->id,
            ])
        );

        $response->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Activity history retrieved successfully'
            )
            ->assertJsonCount(0, 'data');
    });
});