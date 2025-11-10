<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogDetailsResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ProductResource;
use App\Models\Blog;
use App\Models\Category;
use App\Repositories\BlogRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $categoryId = $request->category_id;

        $blogs = BlogRepository::query()->where('is_active', true)
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })->latest('id');

        $total = $blogs->count();

        $blogs = $blogs->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->with('tags', 'category', 'user', 'views')->get();

        // categories whose blogs are available
        $categories = Category::whereHas('blogs')->get()->map(function ($category) {
            return (object) [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        return $this->json('all blog posts', [
            'total' => $total,
            'blogs' => BlogResource::collection($blogs),
            'categories' => $categories,
        ]);
    }

    public function show($slugOrId, Request $request)
    {
        $blog = Blog::where('slug', $slugOrId)->orWhere('id', $slugOrId)->first();

        if (! $blog) {
            return $this->json('The selected blog id or slug is invalid', [], Response::HTTP_BAD_REQUEST);
        }

        $this->viewUpdateOrCreate($blog, $request);

        // related blogs
        $relatedBlogs = Blog::where('category_id', $blog->category_id)->where('id', '!=', $blog->id)->where('is_active', true)->latest('id')->limit(6)->get();

        // popular blogs
        $popularBlogs = Blog::where('is_active', true)->where('id', '!=', $blog->id)->withCount('views')->orderBy('views_count', 'desc')->limit(5)->get();

        // related products
        $relatedProducts = ProductRepository::query()->whereHas('categories', function ($query) use ($blog) {
            $query->where('id', $blog->category_id);
        })->isActive()
            ->inRandomOrder()
            ->limit(5)->get();

        return $this->json('blog details', [
            'blog' => BlogDetailsResource::make($blog),
            'related_blogs' => BlogResource::collection($relatedBlogs),
            'popular_blogs' => BlogResource::collection($popularBlogs),
            'related_products' => ProductResource::collection($relatedProducts),
        ]);
    }

    private function viewUpdateOrCreate(Blog $blog, $request)
    {
        $ipAddress = $request->ip() ?? '127.0.0.1';

        $blog->views()->updateOrCreate(
            [
                'blog_id' => $blog->id,
                'ip_address' => $ipAddress,
            ],
            [
                'ip_address' => $ipAddress,
            ]
        );
    }
}
