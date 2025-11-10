<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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

        $estimateDelivery = $this->shop?->estimated_delivery_time ?? '2-3 days';

        $rate= $this->currency_rate ?? 0;
        $totalAmount= $this->total_amount * $rate;
        $taxAmount= $this->tax_amount * $rate;
        $discount= $this->discount * $rate;
        $couponDiscount= $this->coupon_discount * $rate;
        $payableAmount= $this->payable_amount * $rate;
        $deliveryCharge= $this->delivery_charge * $rate;

        return [
            'id' => $this->id,
            'order_code' => (string) '#'.$this->prefix.''.$this->order_code,
            'order_status' => $this->order_status->value,
            'created_at' => $this->created_at,
            'placed_at' => $this->created_at->format('d M, Y h:i A'),
            'estimated_delivery_date' => (string) $estimateDelivery,
            'payment_method' => $paymentMethod,
            'payment_status' => $this->payment_status->value,
            'total_amount' => (float) number_format($totalAmount, 2, '.', ''),
            'tax_amount' => (float) number_format($taxAmount, 2, '.', ''),
            'discount' => (float) number_format($discount, 2, '.', ''),
            'coupon_discount' => (float) number_format($couponDiscount, 2, '.', ''),
            'payable_amount' => (float) number_format($payableAmount, 2, '.', ''),
            'quantity' => (int) $this->products->sum('pivot.quantity'),
            'delivery_charge' => (float) number_format(($deliveryCharge ?? 0), 2, '.', ''),
            'shop' => ShopResource::make($this->shop),
            'products' => OrderProductResource::collection($this->products),
            'invoice_url' => route('shop.download-invoice', $this->id),
            'payment_receipt_url' => route('shop.payment-slip', $this->id),
            'address' => AddressResource::make($this->address),
            'all_vat_taxes' => $this->vatTaxes,
            'currency_symbol'=>$this->currency_symbol ?? '$',
        ];
    }
}
