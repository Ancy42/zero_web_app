<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\WalletRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class PushData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $banners = [
            'default/banners/banner-1.jpg',
            'default/banners/banner-2.jpg',
            'default/banners/banner-3.jpg',
            'default/banners/banner-4.jpg'
        ];
        foreach($banners as $banner){
            Banner::create([
                'banner' => $banner,
                'status' => true,
            ]);
        }
        $this->info('Banner data pushed successfully');

        $this->pushVendors();
        $this->pushCategories();
        $this->pushAttributes();
        $this->pushProducts();
        $this->pushBlog();
    }

    private function pushVendors()
    {
        $jsonData = file_get_contents(storage_path('data/vendors.json'));
        $data = json_decode($jsonData, true);

        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        foreach($data as $vendor){
            $user = User::create([
                'name' => $vendor['name'],
                'phone' => $vendor['phone'],
                'email' => $vendor['email'],
                'password' => Hash::make($vendor['password']),
                'gender' => fake()->randomElement(['male', 'female']),
                'is_active' => true,
            ]);
            // assign role
            $user->assignRole('shop');
            WalletRepository::storeByRequest($user);
            Shop::create([
                'user_id' => $user->id,
                'name' => $vendor['name'],
                'shop_logo' => 'default/vendors/logo/' . $vendor['logo'],
                'shop_banner' => 'default/vendors/banner/' . $vendor['banner'],
                'delivery_charge' => fake()->randomElement([60, 40, 75]),
                'status' => true,
            ]);

            $bar->advance();
        }
        $bar->finish();
        $this->info('Vendors data pushed successfully');
    }

    private function pushCategories() {
        $this->info('Pushing categories data...');
        $jsonData = file_get_contents(storage_path('data/products.json'));
        $data = json_decode($jsonData, true);
        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        foreach ($data as $productData) {
            if($productData['category-name']){
                Category::firstOrCreate([
                    'name' => $productData['category-name'],
                    'image' => 'default/Category-Products/'. $productData['category-name'] .'/'. $productData['category-img'],
                ]);
            }
            if($productData['brand']){
                Brand::firstOrCreate([
                    'name' => $productData['brand'],
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
    }

    private function pushAttributes()
    {
        $this->info('Pushing attributes data...');
        $jsonData = file_get_contents(storage_path('data/attributes.json'));
        $data = json_decode($jsonData, true);
        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        foreach ($data as $attribute) {
            $category = Category::where('name', $attribute['category_name'])->first();
            if($category){
                $parentAttribute = CategoryAttribute::create([
                        'category_id' => $category->id,
                        'parent_id' => null,
                        'name' => $attribute['parent_attribute'],
                        'status' => true,
                    ]);

                $childAttributes = explode(',', $attribute['child_attributes']);

                foreach($childAttributes as $child) {
                    CategoryAttribute::create([
                        'category_id' => $category->id,
                        'parent_id' => $parentAttribute->id,
                        'name' => $child,
                        'status' => true,
                    ]);
                }
            }
            $bar->advance();
        }
         $bar->finish();

    }

    private function pushProducts()
    {
        $this->info('Products data pushed successfully');
        $jsonData = file_get_contents(storage_path('data/products.json'));
        $data = json_decode($jsonData, true);
        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        foreach ($data as $productData) {
            $shop = Shop::where('name', $productData['shop-name'])->first();
            $brand = Brand::where('name', $productData['brand'])->first();
            $product = Product::create([
                'shop_id' => $shop->id,
                'name' => $productData['product-name'],
                'slug' => \Str::slug($productData['product-name']),
                'description' => $productData['description'],
                'short_description' => $productData['short-description'],
                'brand_id' => $brand?->id,
                'unit' => $productData['unit'],
                'price' => $productData['selling-price'],
                'discount_price' => $productData['discount-price'] ?? 0,
                'quantity' => $productData['qty'],
                'min_order_quantity' => $productData['min-order'],
                'product_thumbnail' =>'default/Category-Products/'. $productData['category-name'] .'/'. $productData["product-img"],
                'code' => $productData['product-sku'],
                'buy_price' => $productData['buying-price'],
                'is_active' => true,
                'is_new' => true,
                'is_approve' =>true,
                'video_id' =>null,
            ]);
            $category = Category::where('name', $productData['category-name'])->first();
            $product->categories()->attach($category->id);
            $attributes = explode(',', $productData['attributes']);
            foreach($attributes as $attributeName) {
                $attribute = CategoryAttribute::where('name', $attributeName)->first();
                if($attribute){
                    $product->categoryAttributes()->attach($attribute->id);
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }

    private function pushBlog()
    {
        $this->info('Products data pushed successfully');
        $jsonData = file_get_contents(storage_path('data/blog.json'));
        $data = json_decode($jsonData, true);
        $bar = $this->output->createProgressBar(count($data));
        $bar->start();
        foreach ($data as $post) {
            $category = Category::where('name', $post['category'])->first();
            $blog = Blog::create([
                'user_id' => 1,
                'title' => $post['title'],
                'category_id' => $category->id,
                'description' => $post['description'],
                'image' => 'default/blogs/' . $post['image'],
            ]);

            foreach ($post['tags'] ?? [] as $tag) {
                $tag = Tag::firstOrCreate([
                    'name' => $tag,
                    'slug' => \Str::slug($tag),
                ]);

                $blog->tags()->attach($tag->id);
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
