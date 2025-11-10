<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paymentMethod = $this->payment_method->value;
        if ($this->payment_status->value == PaymentStatus::PENDING->value && $paymentMethod != PaymentMethod::CASH->value) {
            $paymentMethod = PaymentMethod::ONLINE->value;
        }
        $rate= $this->currency_rate ?? 0;
        $currencyAmount= $this->payable_amount * $rate;
        return [
            'id' => $this->id,
            'order_code' => (string) '#'.$this->prefix.''.$this->order_code,
            'currency_symbol'=>$this->currency_symbol ?? '$',
            'quantity' => (int) $this->products->sum('pivot.quantity'),
            'amount' => (float) number_format($currencyAmount, 2, '.', ''),
            'payment_method' => $paymentMethod,
            'payment_status' => $this->payment_status->value,
            'order_status' => $this->order_status->value,
            'created_at' => $this->created_at,
            'placed_at' => $this->created_at->format('d M, Y h:i A'),
            'address' => AddressResource::make($this->address),
        ];
    }
}
