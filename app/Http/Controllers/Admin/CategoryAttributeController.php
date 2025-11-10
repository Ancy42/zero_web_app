<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryAttribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryAttributeRequest;
use App\Repositories\CategoryAttributeRepository;
use App\Http\Resources\AdminCategoryAttributeResource;

class CategoryAttributeController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::with('attributes.subAttributes')->whereNull('parent_id')->get();

        $htmlTree = $this->formatCategoryTree($categories);

        return view('admin.categoryAttribute.index', compact('htmlTree'));
    }

    private function formatCategoryTree($categories, $level = 0)
    {
        $html = '<ul>';
        foreach ($categories as $category) {
            $html .= '<li id="cat-' . $category->id . '" data-id="' . $category->id . '" data-type="category">';
            $html .= '<div class="category-item">';
            $html .= '<i class="fa-solid fa-folder-open mr-2"></i> ' . $category->name;
            $html .= '</div>';
            if ($category->attributes && $category->attributes->count()) {
                $html .= $this->formatCategoryAttributeTree($category->attributes, $level + 1);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function formatCategoryAttributeTree($attributes, $level = 1)
    {
        $html = '<ul>';
        foreach ($attributes as $attribute) {
            $html .= '<li id="attr-' . $attribute->id . '" data-id="' . $attribute->id . '" data-type="attribute">';
            $html .= '<div class="attribute-item">';
            $html .= '<i class="fa-solid fa-folder-open mr-2"></i> ' . $attribute->name;
            $html .= '</div>';

            if ($attribute->subAttributes && $attribute->subAttributes->count()) {
                $html .= $this->formatCategoryAttributeTree($attribute->subAttributes, $level + 1);
            }

            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }



    public function store(CategoryAttributeRequest $request)
    {
        $categoryAttribute = CategoryAttribute::find($request->parent_id);
        if ($categoryAttribute && $categoryAttribute->parent_id !== null) {
            return back()->withError('Attribute not allowed here', [], 422);
        }

        CategoryAttributeRepository::storeByRequest($request);

        return to_route('admin.categoryAttribute.index')->withSuccess(__('Attribute Created successfully'));
    }

    public function show()
    {
        $categoryAttribute = CategoryAttribute::find(request('attribute_id'));

        if (! $categoryAttribute) {
            return $this->json('Attribute not found', [], 422);
        }

        return $this->json('details', [
            'attribute' => AdminCategoryAttributeResource::make($categoryAttribute),
        ]);
    }

    public function update(CategoryAttributeRequest $request, CategoryAttribute $categoryAttribute)
    {

        CategoryAttributeRepository::updateByRequest($request, $categoryAttribute);

        return to_route('admin.categoryAttribute.index')->withSuccess(__('Attribute updated successfully'));
    }

    /**
     * category status toggle
     */
    public function statusToggle(CategoryAttribute $categoryAttribute)
    {
        $categoryAttribute->update(['status' => ! $categoryAttribute->status]);

        return back()->withSuccess(__('Status updated successfully'));
    }

    public function destroy(CategoryAttribute $categoryAttribute)
    {
        if (! $categoryAttribute) {
            return back()->withError(__('Attribute not found'));
        }

        $categoryAttribute->delete();

        return back()->withSuccess(__('Attribute deleted successfully'));
    }

    public function menuUpdate(Request $request)
    {
        $parentId = $request->input('parent_id');
        $position = $request->input('position');
        $category_attributeId = $request->input('category_attribute_id');

        $categoryAttribute = CategoryAttribute::find($category_attributeId);
        $categoryAttribute->update(['parent_id' => $parentId, 'position' => $position]);

        return $this->json('success', [], 200);
    }
}
