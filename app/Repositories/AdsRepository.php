<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\BannerRequest;
use App\Models\Ad;
use Illuminate\Support\Facades\Storage;

class AdsRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Ad::class;
    }

    /**
     * store new banner
     *
     * */
    public static function storeByRequest(BannerRequest $request): Ad
    {

        return self::create([
            'title' => $request->title,
            'image' => $request->banner,
            'status' => true,
        ]);
    }

    /**
     * Update the banner.
     */
    public static function updateByRequest($request, Ad $ad): Ad
    {
        $ad->update([
            'title' => $request->title,
            'image' => $request->banner ?? $ad->image ,
        ]);

        return $ad;
    }

    /**
     * delete banner
     *
     * */
    public static function destroy(Ad $ad): bool
    {
        $ad->delete();
        return true;
    }
}
