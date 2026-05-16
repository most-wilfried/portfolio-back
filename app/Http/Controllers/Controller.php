<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function publicStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $configuredUrl = config('filesystems.public_url_override');

        if ($configuredUrl) {
            return rtrim($configuredUrl, '/') . '/' . ltrim($path, '/');
        }

        return request()->getSchemeAndHttpHost() . Storage::url($path);
    }
}
