<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\GoogleReCaptcha;

class GoogleReCaptchaRepository extends Repository
{
    /**
     * Get the model class of the repository.
     *
     * @return string
     */
    public static function model()
    {
        return GoogleReCaptcha::class;
    }

    /**
     * Update the google reCaptcha by request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoogleReCaptcha|null  $reCaptcha
     */
    public static function updateByRequest($request, $reCaptcha): GoogleReCaptcha
    {
        return self::query()->updateOrCreate(
            ['id' => $reCaptcha?->id ?? null],
            [
                'site_key' => $request->site_key,
                'secret_key' => $request->secret_key,
                'is_active' => $request->is_active ? true : false,
            ]
        );
    }
}
