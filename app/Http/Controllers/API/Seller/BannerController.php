<?php

namespace App\Http\Controllers\API\Seller;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Repositories\BannerRepository;
use App\Http\Requests\BannerUpdateRequest;
use App\Http\Requests\SellerBannerRequest;
use App\Http\Resources\SellerUserResource;

class BannerController extends Controller
{
    public function index()
    {
        /** @var Shop $shop */
        $shop = generaleSetting('shop');

        $banners = $shop->banners;

        return $this->json('all banners', [
            'banners' => BannerResource::collection($banners),
        ]);
    }

    public function store(SellerBannerRequest $request)
    {
        BannerRepository::storeBySellerApiRequest($request);

        return $this->json('banner created successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }

    public function update(SellerBannerRequest $request)
    {
        $banner = Banner::find($request->id);
        if(!$banner){
            return $this->json(__('Banner not Found'));
        }
        BannerRepository::updateBySellerApiRequest($request, $banner);

        return $this->json('banner updated successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }

    public function destroy(Banner $banner)
    {
        BannerRepository::destroy($banner);

        return $this->json('banner deleted successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }
}
