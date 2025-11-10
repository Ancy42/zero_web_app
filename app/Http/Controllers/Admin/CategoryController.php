<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CategoryRepository;
use App\Http\Resources\AdminCategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a category listing.
     */

    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('subCategories')->get();

        function formatCategoryTree($categories, $level = 0)
        {
            $html = '<ul class="category-level-' . $level . '">';
            foreach ($categories as $category) {
                $html .= '<li id="' . $category->id . '" data-id="' . $category->id . '">';
                $html .= '<div class="category-item">';
                $html .= '<i class="fa-solid fa-folder-open mr-2"></i> ' . $category->name;
                $html .= '</div>';
                if ($category->subCategories->count()) {
                    $html .= formatCategoryTree($category->subCategories, $level + 1);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';

            return $html;
        }


        $htmlTree = formatCategoryTree($categories);

        return view('admin.category.index', compact('htmlTree'));
    }

    /**
     * create a new category
     */
    public function create()
    {

    }

    /**
     * store a new category
     */
    public function store(CategoryRequest $request)
    {

        CategoryRepository::storeByRequest($request);

        return to_route('admin.category.index')->withSuccess(__('Category created successfully'));
    }

    public function show()
    {
        $category = Category::find(request('category_id'));

        if (! $category) {
            return $this->json('category not found', [], 422);
        }

        return $this->json('details', [
            'category' => AdminCategoryResource::make($category),
        ]);
    }

    /**
     * edit a category
     */
    public function edit(Category $category)
    {

    }

    /**
     * update a category
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category = CategoryRepository::updateByRequest($request, $category);

        return to_route('admin.category.index')->withSuccess(__('Category updated successfully'));
    }

    /**
     * category status toggle
     */
    public function statusToggle(Category $category)
    {
        $category->update(['status' => ! $category->status]);

        return back()->withSuccess(__('Status updated successfully'));
    }

    public function destroy(Category $category)
    {
        if (! $category) {
            return back()->withError(__('Category not found'));
        }

        $this->deleteCategory($category);

        return back()->withSuccess(__('Category deleted successfully'));
    }

    protected function deleteCategory(Category $category)
    {
        foreach ($category->subCategories as $subCategory) {
            $this->deleteCategory($subCategory);
        }
        $category->delete();
    }


    public function menuUpdate(Request $request)
    {
        $parentId = $request->input('parent_id');
        $position = $request->input('position');
        $categoryId = $request->input('category_id');

        $category = Category::find($categoryId);
        $category->update(['parent_id' => $parentId, 'position' => $position]);

        return $this->json('success', [], 200);
    }
}
