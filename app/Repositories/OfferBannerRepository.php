<?php

namespace App\Repositories;

use App\Models\OfferBanner;
use App\Repositories\MediaRepository;
use Abedin\Maker\Repositories\Repository;

class OfferBannerRepository extends Repository
{
    public static function model()
    {
        return OfferBanner::class;
    }


    public static function updateByRequest(OfferBanner $offerBanner, $request)
    {
        $offerBanner->update([
            'image' => $request->thumbnail ?? '',
            'link'  =>$request->link ?? ''
        ]);
        return $offerBanner;
    }
}
