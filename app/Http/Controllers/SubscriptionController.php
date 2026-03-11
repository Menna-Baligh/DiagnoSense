<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribePlanRequest;
use App\Http\Resources\CurrentSubscriptionResource;
use App\Http\Resources\PlanResource;
use App\Http\Responses\ApiResponse;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $service)
    {
        $this->subscriptionService = $service;
    }

    public function subscribe(SubscribePlanRequest $request)
    {
        $validated = $request->validated();

        try {
            $doctor = $request->user()->doctor;
            $subscription = $this->subscriptionService->subscribeDoctorToPlan($doctor, $validated['plan_id']);

            return ApiResponse::success(
                "Successfully subscribed to the plan!",
                null,
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                400
            );
        }
    }

    public function switchToPayPerUse(Request $request)
    {
        $this->subscriptionService->setPayPerUseMode($request->user()->doctor);

        return ApiResponse::success(
            "Switched to Pay-Per-Use mode. E£ 25 will be charged per file.",
            null,
            200
        );
    }

    public function index(){
        $plans = Plan::all();
        return ApiResponse::success(
            "Available plans retrieved successfully",
            PlanResource::collection($plans),
            200
        );
    }

    public function current(Request $request){
        $doctor = $request->user()->doctor->load(['wallet', 'subscriptions.plan']);
        if(!$doctor->billing_mode){
            return ApiResponse::error(
                "No active subscription or billing mode found.",
                null,
                404
            );
        }
        return ApiResponse::success(
        "Current billing mode retrieved successfully",
        new CurrentSubscriptionResource($doctor),
        200
        );
    }


    public function cancel(Request $request)
    {
        $doctor = $request->user()->doctor;
        $mode = $doctor->billing_mode;
        
        if ($mode === 'pay_per_use') {
            $doctor->update(['billing_mode' => null]);
            return ApiResponse::success("Pay-Per-Use mode has been disabled. Please subscribe to a plan to continue.", null, 200);
        }

        $subscription = $doctor->activeSubscription;

        if (!$subscription || $mode === null) {
            return ApiResponse::error("No active subscription or billing mode found to cancel.", null, 404);
        }

        $limitReached = $subscription->used_summaries >= $subscription->plan->summaries_limit;

        $subscription->update(['status' => 'cancelled']);

        if ($limitReached) {
            $message = "Subscription cancelled. Note: You have already reached your limit of {$subscription->plan->summaries_limit} summaries.";
        } else {
            $remaining = $subscription->plan->summaries_limit - $subscription->used_summaries;
            $message = "Subscription cancelled. You can still use your remaining {$remaining} summaries until " . $subscription->expires_at->format('D, F j, Y');
        }

        return ApiResponse::success($message, null, 200);
    }

}
