<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\PortfolioProfile;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class StorageAuditController extends Controller
{
    public function __invoke()
    {
        $disk = Storage::disk('public');
        $publicRoot = storage_path('app/public');

        return response()->json([
            'config' => [
                'app_url' => config('app.url'),
                'filesystem_default' => config('filesystems.default'),
                'public_url' => config('filesystems.disks.public.url'),
                'public_root' => $publicRoot,
            ],
            'server_limits' => [
                'file_uploads' => ini_get('file_uploads'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'max_file_uploads' => ini_get('max_file_uploads'),
            ],
            'directories' => [
                'storage_app_public_exists' => is_dir($publicRoot),
                'storage_app_public_writable' => is_writable($publicRoot),
                'public_storage_exists' => file_exists(public_path('storage')),
                'public_storage_is_link' => is_link(public_path('storage')),
                'public_storage_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
            ],
            'files' => [
                'profile' => $this->profileFiles($disk),
                'projects' => $this->projectFiles($disk),
                'certifications' => $this->certificationFiles($disk),
            ],
        ]);
    }

    private function fileState($disk, ?string $path): ?array
    {
        if (! $path) {
            return null;
        }

        $exists = $disk->exists($path);

        return [
            'path' => $path,
            'exists' => $exists,
            'size' => $exists ? $disk->size($path) : null,
            'url' => $this->publicStorageUrl($path),
        ];
    }

    private function profileFiles($disk): array
    {
        $profile = PortfolioProfile::first();

        if (! $profile) {
            return [];
        }

        return [
            'avatar' => $this->fileState($disk, $profile->avatar),
            'cv' => $this->fileState($disk, $profile->cv),
        ];
    }

    private function projectFiles($disk): array
    {
        return Project::with('images')
            ->get()
            ->map(fn ($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'image' => $this->fileState($disk, $project->image),
                'gallery' => $project->images
                    ->map(fn ($image) => $this->fileState($disk, $image->path))
                    ->values(),
            ])
            ->values()
            ->all();
    }

    private function certificationFiles($disk): array
    {
        return Certification::query()
            ->get()
            ->map(fn ($certification) => [
                'id' => $certification->id,
                'title' => $certification->title,
                'file' => $this->fileState($disk, $certification->file),
            ])
            ->values()
            ->all();
    }
}
