<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Retrieves a paginated list of categories with their associated products.
     *
     * @param  Request  $request  The HTTP request object.
     * @return JsonResponse The JSON response containing the categories and the total count.
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $categories = CategoryRepository::query()->whereNull('parent_id')->active()->latest('id');

        $total = $categories->count();

        $categories = $categories->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->with('subCategories')->get();

        return $this->json('categories', [
            'total' => $total,
            'categories' => $this->formatCategoryTree($categories),
        ]);
    }

    private function formatCategoryTree($categories)
    {
        $array = [];
        foreach ($categories as $category) {
            $array[] = [
                'id' => $category->id,
                'name' => $category->name,
                'thumbnail' => $category->thumbnail,
                'total_products' => $category->products()->isActive()->count(),
                'sub_categories' => $this->formatCategoryTree($category->subCategories()->active()->get()),
            ];
        }

        return $array;
    }

     public function getCategoryAttributes(Request $request)
    {
        $category = CategoryRepository::find($request->category_id);

        if (! $category) {
            return $this->json('attributes', [
                'attributes' => [],
            ]);
        }

        $attributes = $category->attributes()->active()->get();

        return $this->json('attributes', [
            'attributes' => $this->formatCategoryAttributeTree($attributes),
        ]);
    }

    private function formatCategoryAttributeTree($attributes)
    {
        $array = [];
        foreach ($attributes as $attribute) {
            $array[] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'sub_attributes' => $this->formatCategoryAttributeTree($attribute->subAttributes()->active()->get()),
            ];
        }
        return $array;
    }
}
