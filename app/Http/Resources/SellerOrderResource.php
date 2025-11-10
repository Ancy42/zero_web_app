<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $estimateDays = $this->shop->estimated_delivery_time ?? '2-4 days';

        return [
            'id' => $this->id,
            'order_code' => (string) '#'.$this->prefix.''.$this->order_code,
            'amount' => (float) number_format($this->payable_amount, 2, '.', ''),
            'order_status' => $this->order_status->value,
            'payment_status' => $this->payment_status->value,
            'payment_method' => $this->payment_method->value == PaymentMethod::CASH->value ? 'Cash' : 'Online',
            'estimated_delivery_date' => (string) $estimateDays,
            'pickup_date' => $this->pickup_date ? Carbon::parse($this->pickup_date)->format('d M, Y') : null,
            'delivery_date' => $this->delivery_date ? Carbon::parse($this->delivery_date)->format('d M, Y') : null,
            'order_placed' => Carbon::parse($this->created_at)->format('d M, Y'),
            'delivery_charge' => (float) number_format($this->delivery_charge, 2, '.', ''),
            'user' => [
                'name' => $this->customer->user->name,
                'phone' => $this->customer->user->phone,
                'profile_photo' => $this->customer->user->thumbnail,
                'address' => AddressResource::make($this->address),
            ],
            'products' => SellerProductResource::collection($this->products),
            'rider' => $this->driverOrder ? OrderRiderResource::make($this->driverOrder) : null,
            'invoice_url' => route('shop.download-invoice', $this->id),
        ];
    }
}
