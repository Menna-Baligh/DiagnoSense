<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MedicalFileService
{
    public function getMedicalHistoryFiles(?Patient $patient, ?string $search = null): Collection
    {

        if (! $patient) {
            throw new \Exception('Unauthorized', 403);
        }

        return $patient->reports()
            ->where('type', 'medical_history')
            ->when($search, function ($query) use ($search) {
                $query->where(
                    'file_name',
                    'like',
                    "%{$search}%"
                );
            })
            ->latest()
            ->get();
    }

    public function getLabReports(?Patient $patient, ?string $search = null): Collection
    {

        if (! $patient) {
            throw new \Exception('Unauthorized', 403);
        }

        return $patient->reports()
            ->where('type', 'lab')
            ->when($search, function ($query) use ($search) {
                $query->where(
                    'file_name',
                    'like',
                    "%{$search}%"
                );
            })
            ->with(['patient.visits.doctor.user'])
            ->latest()
            ->get();
    }

    public function getRadiologyReports(?Patient $patient, ?string $search = null): Collection
    {

        if (! $patient) {
            throw new \Exception('Unauthorized', 403);
        }

        return $patient->reports()
            ->where('type', 'radiology')
            ->when($search, function ($query) use ($search) {
                $query->where(
                    'file_name',
                    'like',
                    "%{$search}%"
                );
            })
            ->latest()
            ->get();
    }

    public function updateProfile(User $user, array $data): array
    {

        $user->update($data);

        return [
            'name' => $user->name,
            'contact' => $user->contact,
        ];
    }
}
