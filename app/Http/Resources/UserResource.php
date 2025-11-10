<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $accountVerified = false;
        if ($this->phone_verified_at || $this->email_verified_at) {
            $accountVerified = true;
        }

        $lastOnline = $this->last_online >= now() ? true : false;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'phone_verified' => (bool) $this->phone_verified_at ? true : false,
            'email' => $this->email,
            'email_verified' => (bool) $this->email_verified_at ? true : false,
            'is_active' => (bool) $this->is_active,
            'profile_photo' => $this->thumbnail,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'country' => $this->country,
            'phone_code' => $this->phone_code,
            'account_verified' => (bool) $accountVerified,
            'last_online' => $lastOnline
        ];
    }
}
