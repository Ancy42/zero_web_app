<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Blog;
use App\Services\Chat;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Http\Controllers\Controller;
use App\Repositories\BlogRepository;
use App\Repositories\CategoryRepository;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogRepository::query()->with('tags', 'category', 'user', 'views')->latest('id')->paginate(20);

        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = CategoryRepository::query()->active()->get();

        $tags = Tag::latest('id')->take(150)->get();

        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(BlogRequest $request)
    {
        BlogRepository::storeByRequest($request);

        return to_route('admin.blog.index')->withSuccess(__('Created successfully'));
    }

    public function edit(Blog $blog)
    {
        $categories = CategoryRepository::query()->active()->get();

        $blogTags = $blog->tags()?->pluck('id')->toArray() ?? [];
        $mandatoryTags = Tag::whereIn('id', $blogTags)->get();

        // Calculate tags are needed to reach 150
        $remainingCount = 150 - $mandatoryTags->count();

        $additionalTags = Tag::latest()
            ->whereNotIn('id', $blogTags)
            ->take($remainingCount)
            ->get();
        $tags = $mandatoryTags->merge($additionalTags);

        return view('admin.blog.edit', compact('blog', 'categories', 'tags', 'blogTags'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        BlogRepository::updateByRequest($request, $blog);

        return to_route('admin.blog.index')->withSuccess(__('Updated successfully'));
    }

    public function statusToggle(Blog $blog)
    {
        $blog->update([
            'is_active' => ! $blog->is_active,
        ]);

        return to_route('admin.blog.index')->withSuccess(__('Status Updated Successfully'));
    }

    public function destroy(Blog $blog)
    {

        $blog->tags()->detach();

        $blog->delete();

        return to_route('admin.blog.index')->withSuccess(__('Deleted successfully'));
    }


     /**
     * generate ai blog description
     */

     public function generateAIData(Request $request)
    {

        try {

            $request->validate([
                'title' => 'required|string',
            ]);

            $chat = new Chat();
            $chat->systemMessage($request->title);

            $question = str_replace(
                ['{title}'],
                [$request->title],
                generaleSetting()->blog_description
            );

            $question .= "Format the blog description with proper HTML tags (<p>, <h2>, <ul>, <li>) so it can be directly used inside CKEditor.Do not include extra phrases like 'The blog title is','```html', ‘Sure’ or ‘Here is’.Just output the final formatted blog description.";

            $response = $chat->send($question);

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
