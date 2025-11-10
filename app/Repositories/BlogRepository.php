<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class BlogRepository extends Repository
{
    public static function model()
    {
        return Blog::class;
    }

    public static function storeByRequest(BlogRequest $request): Blog
    {

        $description = ProductRepository::sanitizeUnicode($request->description);
        $description = mb_convert_encoding($description, 'HTML-ENTITIES', 'UTF-8');
        $description = Purifier::clean($description);

        $title = ProductRepository::sanitizeUnicode($request->title);

        $blog = self::create([
            'user_id' => auth()->id(),
            'title' => $title,
            'category_id' => $request->category,
            'description' => $description,
            'image' => $request->thumbnail,
        ]);

        foreach ($request->tags ?? [] as $tag) {
            $tag = Tag::firstOrCreate([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);

            $blog->tags()->attach($tag->id);
        }

        return $blog;
    }

    public static function updateByRequest(BlogRequest $request, Blog $blog): Blog
    {

        $description = ProductRepository::sanitizeUnicode($request->description);
        $description = mb_convert_encoding($description, 'HTML-ENTITIES', 'UTF-8');

        $title = ProductRepository::sanitizeUnicode($request->title);

        $blog->update([
            'title' => $title,
            'category_id' => $request->category,
            'description' => $description,
            'image' => $request->thumbnail ?? $blog->image,
        ]);

        $blog->tags()->detach();

        foreach ($request->tags ?? [] as $tag) {
            $tag = Tag::firstOrCreate([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);

            $blog->tags()->attach($tag->id);
        }

        return $blog;
    }
}
