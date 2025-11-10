<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;
use Abedin\Maker\Repositories\Repository;

class BannerRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Banner::class;
    }

    /**
     * store new banner
     *
     * */
    public static function storeByRequest($request): Banner
    {

        // shop
        $shop = generaleSetting('shop');

        $shopId = $shop?->id;

        $user = $shop?->user;
        if ($user && $user->hasRole('root') && ! $request->for_shop) {
            $shopId = null;
        }

        return self::create([
            'shop_id' => $shopId,
            'title' => $request->title,
            'description' => $request->description,
            'banner' => $request->banner,
            'status' => true,
        ]);
    }

    /**
     * Update the banner.
     */
    public static function updateByRequest($request, Banner $banner): Banner
    {

        $shop = generaleSetting('shop');
        $shopId = $request->for_shop ? $shop?->id : $banner->shop_id;
        $user = $shop?->user;
        if ($user && $user->hasRole('root') && ! $request->for_shop) {
            $shopId = null;
        }

        $banner->update([
            'shop_id' => $shopId,
            'title' => $request->title,
            'description' => $request->description,
            'banner' => $request->banner ?? $banner->banner,
        ]);

        return $banner;
    }

    /**
     * delete banner
     *
     * */
    public static function destroy(Banner $banner): bool
    {
        $media =Media::where('src',$banner->banner)->first();
        
        if (Storage::exists($media->src)) {
            Storage::delete($media->src);
        }
        $banner->delete();
        $media->delete();

        return true;
    }


    public static function updateBySellerApiRequest($request, Banner $banner): Banner
     {
        $media =Media::where('src',$banner->banner)->first() ?? null;
        if ($request->hasFile('banner')) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->banner,
                'banners',
                'image',
                $media
            );
        }

        $shop = generaleSetting('shop');
        $shopId = $request->for_shop ? $shop?->id : $banner->shop_id;
        $user = $shop?->user;
        if ($user && $user->hasRole('root') && ! $request->for_shop) {
            $shopId = null;
        }

        $banner->update([
            'shop_id' => $shopId,
            'title' => $request->title,
            'description' => $request->description,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'banner' => $thumbnail->src ?? $banner->banner,
        ]);

        return $banner;
    }
    public static function storeBySellerApiRequest($request): Banner
    {

        $thumbnail = MediaRepository::storeByRequest($request->banner, 'banners', 'thumbnail', 'image');

        // shop
        $shop = generaleSetting('shop');

        $shopId = $shop?->id;

        $user = $shop?->user;
        if ($user && $user->hasRole('root') && ! $request->for_shop) {
            $shopId = null;
        }

        return self::create([
            'shop_id' => $shopId,
            'title' => $request->title,
            'description' => $request->description,
            'banner' => $thumbnail->src ?? '',
            'status' => true,
        ]);

    }
}
