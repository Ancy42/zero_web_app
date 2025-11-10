<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;

class LanguageRepository extends Repository
{
    public static function model()
    {
        return Language::class;
    }

    public static function checkFileExitsOrNot(array $fileNames)
    {
        foreach ($fileNames as $name) {
            if (! self::isNameExists($name)) {
                $title = $name;
                if ($name == 'en') {
                    $title = 'English';
                } elseif ($name == 'ar') {
                    $title = 'Arabic';
                } elseif ($name == 'bn') {
                    $title = 'Bengali';
                }

                self::create([
                    'title' => $title,
                    'name' => $name,
                ]);
            }
        }
    }

    public static function storeByRequest($request)
    {
        $filePath = base_path("lang/$request->name.json");

        $jsonData = file_get_contents(public_path('default/emptyLanguage.json'));

        try {
            file_put_contents($filePath, $jsonData, JSON_PRETTY_PRINT);

            $flag = MediaRepository::storeByRequest(
                $request->file('flag'),
                'language',
                'image'
            );

            self::create([
                'title' => $request->title,
                'name' => $request->name,
                'media_id' => $flag->id ?? null,
            ]);

            return ['type' => 'success', 'message' => __('Created Successfully')];
        } catch (\Throwable $e) {
            return ['type' => 'error', 'message' => $e->getMessage()];
        }
    }

    public static function updateByRequest(Language $language, LanguageRequest $request, $filePath)
    {
        $processedData = [];

        $flag = $language->media;
        if ($request->hasFile('flag')) {
            $flag = MediaRepository::updateByRequest(
                $request->file('flag'),
                'langugage',
                'image',
                $flag
            );
        }

        foreach ($request->data as $entry) {
            $key = $entry['key'];
            $value = array_key_exists('value', $entry) ? $entry['value'] : '';
            $processedData[$key] = $value;
        }

        $existingData = json_decode(file_get_contents($filePath), true);

        $updatedData = array_merge($existingData, $processedData);

        try {
            file_put_contents($filePath, json_encode($updatedData, JSON_PRETTY_PRINT));

            $language->update([
                'title' => $request->title,
                'media_id' => $flag->id ?? null,
            ]);

            return $language;

            return ['type' => 'success', 'message' => __('Updated Successfully')];
        } catch (\Throwable $e) {
            return ['type' => 'error', 'message' => $e->getMessage()];
        }
    }

    public static function isNameExists($name)
    {
        return self::query()->where('name', $name)->exists();
    }
}
