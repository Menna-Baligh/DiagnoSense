<?php

use App\Models\Patient;
use App\Models\Report;
use App\Models\User;

beforeEach(function () {

    $this->patientUser = User::factory()->create();

    $this->patient = Patient::factory()->create([
        'user_id' => $this->patientUser->id,
    ]);

    $this->actingAs($this->patientUser);
});

describe('Medical History Files', function () {

    it('returns medical history files successfully', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'medical_history',
            'file_name' => 'history1.pdf',
            'file_path' => 'reports/history1.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'medical_history',
            'file_name' => 'history2.pdf',
            'file_path' => 'reports/history2.pdf',
        ]);

        $response = $this->getJson(
            route('patient.medical-history')
        );

        $response->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Medical history files retrieved successfully'
            )
            ->assertJsonCount(2, 'data');
    });

    it('filters medical history files by search', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'medical_history',
            'file_name' => 'brain.pdf',
            'file_path' => 'reports/brain.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'medical_history',
            'file_name' => 'heart.pdf',
            'file_path' => 'reports/heart.pdf',
        ]);

        $response = $this->getJson(
            route('patient.medical-history', [
                'search' => 'brain',
            ])
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('returns empty array when no medical history files exist', function () {

        $response = $this->getJson(
            route('patient.medical-history')
        );

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });

    it('returns 401 for guest user', function () {

        auth()->logout();

        $response = $this->getJson(
            route('patient.medical-history')
        );

        $response->assertStatus(401);
    });
});

describe('Lab Reports', function () {

    it('returns lab reports successfully', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'lab',
            'file_name' => 'cbc.pdf',
            'file_path' => 'reports/cbc.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'lab',
            'file_name' => 'urine.pdf',
            'file_path' => 'reports/urine.pdf',
        ]);

        $response = $this->getJson(
            route('patient.lab-reports')
        );

        $response->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Lab reports retrieved successfully'
            )
            ->assertJsonCount(2, 'data');
    });

    it('filters lab reports by search', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'lab',
            'file_name' => 'cbc.pdf',
            'file_path' => 'reports/cbc.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'lab',
            'file_name' => 'urine.pdf',
            'file_path' => 'reports/urine.pdf',
        ]);

        $response = $this->getJson(
            route('patient.lab-reports', [
                'search' => 'cbc',
            ])
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('returns empty array when no lab reports exist', function () {

        $response = $this->getJson(
            route('patient.lab-reports')
        );

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });
});

describe('Radiology Reports', function () {

    it('returns radiology reports successfully', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'radiology',
            'file_name' => 'mri.pdf',
            'file_path' => 'reports/mri.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'radiology',
            'file_name' => 'xray.pdf',
            'file_path' => 'reports/xray.pdf',
        ]);

        $response = $this->getJson(
            route('patient.radiology-reports')
        );

        $response->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Radiology reports retrieved successfully'
            )
            ->assertJsonCount(2, 'data');
    });

    it('filters radiology reports by search', function () {

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'radiology',
            'file_name' => 'mri.pdf',
            'file_path' => 'reports/mri.pdf',
        ]);

        Report::create([
            'patient_id' => $this->patient->id,
            'type' => 'radiology',
            'file_name' => 'xray.pdf',
            'file_path' => 'reports/xray.pdf',
        ]);

        $response = $this->getJson(
            route('patient.radiology-reports', [
                'search' => 'mri',
            ])
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    it('returns empty array when no radiology reports exist', function () {

        $response = $this->getJson(
            route('patient.radiology-reports')
        );

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });
});
