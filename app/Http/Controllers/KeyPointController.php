<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyKeyPointRequest;
use App\Http\Requests\UpdateKeyPointRequest;
use App\Http\Responses\ApiResponse;
use App\Models\KeyPoint;

class KeyPointController extends Controller
{
    public function destroy(DestroyKeyPointRequest $request,$keyPointId){
        $keyPoint = KeyPoint::findOrFail($keyPointId);
        $keyPoint->delete();
        return ApiResponse::success('Key point deleted successfully', null, 200);
    }
    public function update(UpdateKeyPointRequest $request, $keyPointId){
        $keyPoint = KeyPoint::findOrFail($keyPointId);
        $validated = $request->validated();
        $keyPoint->update([
            'insight' => $validated['insight'],
        ]);
        return ApiResponse::success('Key point updated successfully', ['id' => $keyPoint->id , 'insight' => $keyPoint->insight], 200);
    }
}
