<?php
namespace App\Actions;

use App\Models\Plan;
use App\Http\Resources\PlanResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GetAvailablePlansAction
{

    public function execute(): AnonymousResourceCollection
    {
        $plans = Plan::all();
        return PlanResource::collection($plans);
    }
}
