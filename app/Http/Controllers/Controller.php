<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function json(?string $message = null, $data = [], $statusCode = 200, array $headers = [])
    {
        $content = [];
        if ($message) {
            $content['message'] = $message;
        }

        if (! empty($data)) {
            $content['data'] = $data;
        }

        return response()->json($content, $statusCode, $headers, JSON_PRESERVE_ZERO_FRACTION);
    }

    protected function setEnv($key, $value)
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);

            // Check if the key exists in the .env file
            if (strpos($str, "{$key}=") === false) {
                $str .= "{$key}={$value}\n";
            } else {
                $str = preg_replace("/{$key}=.*/", "{$key}={$value}", $str);
            }

            // Trim both key and value to remove leading/trailing whitespaces
            $str = rtrim($str)."\n";

            // Update the .env file
            file_put_contents($envFile, $str);

            return ['type' => 'success', 'message' => __('Updated Successfully')];
        } catch (Exception $e) {
            return ['type' => 'error', 'message' => $e->getMessage()];
        }
    }
}
