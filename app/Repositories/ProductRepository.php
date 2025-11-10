<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Product;
use App\Models\Category;
use App\Models\RecentView;
use Illuminate\Support\Str;
use App\Models\ProductTranslation;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Storage;
use Abedin\Maker\Repositories\Repository;

class ProductRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Product::class;
    }

    public static function recentView(Product $product)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            RecentView::where('product_id', $product->id)->where('user_id', $user->id)->firstOrCreate([
                'product_id' => $product->id,
                'user_id' => $user->id,
            ])?->update(['updated_at' => now()]);
        }

        return $product;
    }

    /**
     * Sanitizes a string by removing invalid or non-printable characters.
     *
     * @param  string  $input
     * @return string
     */
    public static function sanitizeUnicode($input)
    {
        $cleanedInput = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $input);
        $cleanedInput = preg_replace('/[\xF0-\xF9][\x80-\xBF][\xF0\x9F]{3}/u', '', $input);
        $cleanedInput = preg_replace('/[\xF0-\xF7][\x80-\xBF]{3}/u', '', $cleanedInput);
        $cleanedInput = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $cleanedInput);
        $cleanedInput = preg_replace('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', '', $cleanedInput);

        return $cleanedInput;
    }

    /**
     * store new product.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     *
     */
    public static function storeByRequest(ProductRequest $request): Product
    {
        $shop = generaleSetting('shop');
        $generaleSetting = generaleSetting('setting');
        $approve = $generaleSetting?->new_product_approval ? false : true;

        $user = auth()->user();
        $isAdmin = false;
        if ($user->hasRole('root') || ($generaleSetting?->shop_type == 'single')) {
            $isAdmin = true;
        }

        $videoMedia = self::videoCreateOrUpdate($request);
        $description = Purifier::clean(self::sanitizeUnicode($request->description));

        $keywords = implode(',', $request->meta_keywords ?? []);

        $product = self::create([
            'shop_id' => $shop?->id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $description,
            'short_description' => $request->short_description,
            'brand_id' => $request->brand,
            'unit' => $request->unit,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity ?? 0,
            'min_order_quantity' => $request->min_order_quantity ?? 1,
            'product_thumbnail' => $request->thumbnail,
            'code' => $request->code,
            'buy_price' => $request->buy_price ?? 0,
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => true,
            'unit_measurement_add' => $request->unit_measurement_add ?? 0,
            'is_approve' => $isAdmin ? true : $approve,
            'video_id' => $videoMedia ? $videoMedia->id : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $keywords ? Str::limit($keywords, 200, '') : null,
        ]);

        foreach ($request->names ?? [] as $key => $value) {
            if (! $key || ! $value) {
                continue;
            }

            $description = array_key_exists($key, $request->descriptions ?? []) ? $request->descriptions[$key] : null;
            $shortDescription = array_key_exists($key, $request->short_descriptions ?? []) ? $request->short_descriptions[$key] : null;

            ProductTranslation::create([
                'product_id' => $product->id,
                'lang' => $key,
                'name' => $value,
                'description' => $description,
                'short_description' => $shortDescription,
            ]);
        }

        $product->categories()->sync($request->categories ?? []);
        $product->categoryAttributes()->sync($request->category_attribute_ids ?? []);

        $thumbnails = array_filter($request->additionThumbnail ?? []);
        foreach ($thumbnails as $additionThumbnail) {
            $product->medias()->create([
                'additional_thumbnail' => $additionThumbnail
            ]);
        }


        return $product;
    }

    /**
     * Update the product.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     *                                                      return \App\Models\Product
     */
    public static function updateByRequest(ProductRequest $request, Product $product): Product
    {
        // dd($request->all());
        $generaleSetting = generaleSetting('setting');
        $approve = $generaleSetting?->update_product_approval ? false : true;

        $user = auth()->user();
        $isAdmin = false;
        if ($user->hasRole('root') || ($generaleSetting?->shop_type == 'single')) {
            $isAdmin = true;
        }

        $videoMedia = self::videoCreateOrUpdate($request, $product);
        $description = Purifier::clean(self::sanitizeUnicode($request->description));
        $keywords = implode(',', $request->meta_keywords ?? []);

        self::update($product, [
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $description,
            'short_description' => $request->short_description,
            'brand_id' => $request->brand ?? null,
            'unit' => $request->unit ,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'quantity' => $request->quantity ?? 0,
            'min_order_quantity' => $request->min_order_quantity ?? 1,
            'product_thumbnail' => $request->thumbnail ??  $product->product_thumbnail,
            'code' => $request->code,
            'buy_price' => $request->buy_price ?? 0,
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => false,
            'unit_measurement_add' => $request->unit_measurement_add ?? 0,
            'is_approve' => $isAdmin ? true : $approve,
            'video_id' => $videoMedia ? $videoMedia->id : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $keywords ? Str::limit($keywords, 200, '') : null,
        ]);

        foreach ($request->names ?? [] as $key => $value) {
            if (! $key || ! $value) {
                continue;
            }

            $description = array_key_exists($key, $request->descriptions ?? []) ? $request->descriptions[$key] : null;
            $shortDescription = array_key_exists($key, $request->short_descriptions ?? []) ? $request->short_descriptions[$key] : null;

            ProductTranslation::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'lang' => $key,
                ],
                [
                    'name' => $value,
                    'description' => $description,
                    'short_description' => $shortDescription,
                ]
            );
        }

        $product->categories()->sync($request->categories ?? []);
        $product->categoryAttributes()->sync($request->category_attribute_ids ?? []);

        // additional thumbnail
        $thumbnails = array_filter($request->additionThumbnail ?? []);

        $product->medias()->whereNotIn('additional_thumbnail', $thumbnails)->delete();

        foreach ($thumbnails as $thumbnail) {
            $product->medias()->updateOrCreate(
                [
                    'product_id' =>$product->id,
                    'additional_thumbnail' => $thumbnail
                ],
                []
            );
        }

        return $product;
    }

    private static function videoCreateOrUpdate($request, $product = null): ?Media
    {
        $media = $product?->videoMedia;
        $uploadVideoRequest = $request->uploadVideo;

        if (! $uploadVideoRequest || ! is_countable($uploadVideoRequest)) {
            return $media;
        }

        $type = $uploadVideoRequest['type'];
        $url = isset($uploadVideoRequest[$type.'_'.'url']) ? $uploadVideoRequest[$type.'_'.'url'] : null;

        if ($media && $type == 'file' && isset($uploadVideoRequest['file']) && is_file($uploadVideoRequest['file'])) {
            return MediaRepository::updateByRequest(
                $uploadVideoRequest['file'],
                'products',
                'file',
                $media
            );
        } elseif ($media && $type != 'file' && $url != null) {

            // Replace the width and height attributes in the iframe
            $customWidth = '100%';
            $customHeight = '650';
            $customizedIframe = preg_replace(['/width="(\d+(%?))"/', '/height="(\d+(%?))"/'], ["width=\"$customWidth\"", "height=\"$customHeight\""], $url);

            $media->update([
                'src' => $customizedIframe,
                'type' => $type,
            ]);

            return $media;
        }

        if (! $media && $type == 'file' && isset($uploadVideoRequest['file']) && is_file($uploadVideoRequest['file'])) {
            return MediaRepository::storeByRequest(
                $uploadVideoRequest['file'],
                'products',
                'file'
            );
        } elseif (! $media && $type != 'file' && $url != null) {

            $width = '100%';
            $height = '650';
            $customizedIframe = preg_replace(['/width="(\d+(%?))"/', '/height="(\d+(%?))"/'], ["width=\"$width\"", "height=\"$height\""], $url);

            return Media::create([
                'src' => $customizedIframe,
                'type' => $type,
            ]);
        }

        return $media;
    }

    /**
     * store new product from bulk import.
     */
    public static function bulkItemStore($rows, $folders = null)
    {

        $rootShop = generaleSetting('rootShop');

        $total = 0;

        $folders = $folders !== null ? array_keys($folders) : [];


        foreach ($rows as $row) {

            $createData = [];

            for ($i = 0; $i <= 12; $i++) {

                if ($i == 1) {
                    $createData['name'] = $row[$i];
                } elseif ($i == 2) {
                    $createData['product_thumbnail'] = $row[$i];
                } elseif ($i == 3) {
                    $selectCategories = explode(',', $row[$i]);
                    $categories = [];
                    foreach ($selectCategories as $categoryName) {
                        $category = Category::where('name', $categoryName)->first();

                        if ($category) {
                            $categories[] = $category->id;
                        }
                    }
                    $createData['categories'] = $categories;
                }  elseif ($i == 4) {
                    $brand = $rootShop->brands()->where('name', $row[$i])->first();
                    $createData['brand'] = $brand ? $brand->id : null;
                } elseif ($i == 5) {
                    $createData['unit'] = $row[$i];
                } elseif ($i == 6) {
                    $createData['price'] = $row[$i];
                } elseif ($i == 7) {
                    $createData['discount_price'] = $row[$i];
                } elseif ($i == 8) {
                    $createData['sku'] = $row[$i];
                } elseif ($i == 9) {
                    $createData['stock_quantity'] = $row[$i];
                } elseif ($i == 10) {
                    $createData['short_description'] = $row[$i];
                } elseif ($i == 11) {
                    $createData['description'] = $row[$i];
                }elseif ($i == 12) {
                    $createData['additional_thumbnail'] =$row[$i];
                }
            }

            if ($createData['name'] != null && $createData['price'] != null && count($createData['categories']) != 0) {

                if ($createData['price'] < $createData['discount_price']) {
                    $createData['discount_price'] = $createData['price'];
                }

                self::storeBulkProduct($createData);

                $total = $total + 1;
            }
        }

        return $total;
    }

    /**
     * store new product from bulk import.
     *
     * @return Product
     */
    private static function storeBulkProduct($data)
    {
        $shop = generaleSetting('shop');
        $generaleSetting = generaleSetting('setting');
        $approve = $generaleSetting?->new_product_approval ? false : true;

        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
        $isAdmin = false;
        if ($user->hasRole('root') || ($generaleSetting?->shop_type == 'single')) {
            $isAdmin = true;
        }

        $product = self::create([
            'shop_id' => $shop?->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? 'description',
            'short_description' => $data['short_description'] ?? 'short description',
            'brand_id' => $data['brand'] ?? null,
            'price' => $data['price'] ?? 0,
            'unit' => $data['unit'] ?? null,
            'discount_price' => $data['discount_price'] ?? 0,
            'quantity' => $data['stock_quantity'] ?? 1,
            'min_order_quantity' => 1,
            'product_thumbnail' => $data['product_thumbnail'],
            'is_active' => $isAdmin ? true : $approve,
            'is_new' => true,
            'is_approve' => $isAdmin ? true : $approve,
            'code' => $data['sku'] ?? random_int(100000, 999999),
        ]);

        $product->categories()->sync($data['categories'] ?? []);
        $product->categoryAttributes()->sync($data['category_attribute_ids'] ?? []);

        $thumbnails = $data['additional_thumbnail'] ?? [];
        if (!empty($thumbnails)) {
            $thumbnails = is_array($thumbnails) ? $thumbnails : explode(',', $thumbnails);

            foreach ($thumbnails as $additionThumbnail) {
                $product->medias()->create([
                    'additional_thumbnail' => trim($additionThumbnail)
                ]);
            }
        }

        return $product;
    }

    public static function storeMedia($thumbnail)
    {
        if ($thumbnail != null) {

            $realPath = $thumbnail->getRealPath();

            $path = 'thumbnails';

            $fileName = random_int(100000, 999999).date('YmdHis').'.'.pathinfo($realPath, PATHINFO_EXTENSION);

            $storagePath = Storage::disk('public')->putFileAs($path, $thumbnail, $fileName);

            $media = Media::create([
                'name' => pathinfo($storagePath, PATHINFO_FILENAME),
                'src' => $storagePath,
                'type' => 'image',
                'original_name' => basename($realPath),
                'extension' => pathinfo($storagePath, PATHINFO_EXTENSION),
            ]);

            return $media->id;
        }

        return null;
    }

}
