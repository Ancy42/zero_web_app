<?php

namespace Modules\Purchase\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseProductSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $productName = [];
        $separate = '|';
        foreach ($this->purchaseProducts as $purchaseProduct) {
            array_push($productName, $separate, $purchaseProduct->product->name);
        }
        return [
            'id' => $this->id,
            'purchase_code' => $this->purchase_code,
            'purchase_product' => $productName
        ];
    }
}
