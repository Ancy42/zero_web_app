<?php

namespace App\Http\Controllers\API\Rider;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\RiderRequest;
use App\Http\Resources\RiderUserResource;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * show user details.
     */
    public function show()
    {
        $user = auth()->user();

        $currentMonthDelivered = $user->driver->driverOrders()
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('is_completed', true)->count();

        $currentMonthCashCollected = $user->driver->driverOrders()
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('is_completed', true)
            ->whereHas('order', function ($query) {
                return $query->where('payment_method', PaymentMethod::CASH->value);
            })
            ->with('order')->get()->sum('order.payable_amount');

        return $this->json('user details', [
            'user' => RiderUserResource::make($user),
            'curren_month_deliverd' => $currentMonthDelivered,
            'current_month_cash_collected' => $currentMonthCashCollected,
        ]);
    }

    /**
     * update profile.
     */
    public function update(RiderRequest $request)
    {
        $user = UserRepository::updateByRequest($request, auth()->user());

        $user->refresh();

        return $this->json('Profile is updated successfully', [
            'user' => RiderUserResource::make($user),
        ]);
    }
}
