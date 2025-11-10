<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPlanRequest;
use App\Models\SubscriptionPlan;
use App\Repositories\SubscriptionPlanRepository;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $subscriptionPlans = SubscriptionPlanRepository::query()->paginate(20);

        return view('admin.subscription-plan.index', compact('subscriptionPlans'));
    }

    public function create()
    {
        return view('admin.subscription-plan.create');
    }

    public function store(SubscriptionPlanRequest $request)
    {
        SubscriptionPlanRepository::storeByRequest($request);

        return to_route('admin.subscription-plan.index')->withSuccess(__('Created successfully'));
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plan.edit', compact('subscriptionPlan'));
    }

    public function update(SubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        SubscriptionPlanRepository::updateByRequest($request, $subscriptionPlan);

        return to_route('admin.subscription-plan.index')->withSuccess(__('Updated successfully'));
    }

    public function statusToggle(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update([
            'is_active' => ! $subscriptionPlan->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();

        return back()->withSuccess('deleted successfully');
    }
}
