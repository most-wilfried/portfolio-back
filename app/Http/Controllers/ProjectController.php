<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    private const PROJECT_TYPES = ['académique', 'personnel', 'professionnel'];
    private const STATUSES = ['terminé', 'en cours'];

    public function index()
    {
        return Project::with('images')
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($project) {
                return $this->withPresentationFields($project);
            });
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string',
            'category' => 'nullable|string|max:120',
            'project_type' => 'nullable|string|in:' . implode(',', self::PROJECT_TYPES),
            'year' => 'nullable|integer|min:2000|max:2100',
            'status' => 'nullable|string|in:' . implode(',', self::STATUSES),
            'technologies' => 'nullable',
            'github_url' => 'nullable|url|max:255',
            'github_link' => 'nullable|url|max:255',
            'demo_url' => 'nullable|url|max:255',
            'demo_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:10240',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|max:10240',
        ]);

        $data = $this->normalizeLinksAndMeta($data, true);

        if (isset($data['technologies'])) {
            $data['technologies'] = array_filter(array_map('trim', explode(',', (string) $data['technologies'])));
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($data);
        $this->storeGalleryImages($request, $project);
        $project->load('images');

        return $this->withPresentationFields($project);
    }

    public function show(Project $project)
    {
        $project->load('images');

        return $this->withPresentationFields($project);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:180',
            'description' => 'sometimes|required|string',
            'category' => 'nullable|string|max:120',
            'project_type' => 'nullable|string|in:' . implode(',', self::PROJECT_TYPES),
            'year' => 'nullable|integer|min:2000|max:2100',
            'status' => 'nullable|string|in:' . implode(',', self::STATUSES),
            'technologies' => 'nullable',
            'github_url' => 'nullable|url|max:255',
            'github_link' => 'nullable|url|max:255',
            'demo_url' => 'nullable|url|max:255',
            'demo_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:10240',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|max:10240',
        ]);

        $data = $this->normalizeLinksAndMeta($data);

        if (isset($data['technologies'])) {
            $data['technologies'] = array_filter(array_map('trim', explode(',', (string) $data['technologies'])));
        }

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);
        $this->storeGalleryImages($request, $project);
        $project->load('images');

        return $this->withPresentationFields($project);
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $project->delete();

        return response()->json(['message' => 'Projet supprimé.']);
    }

    public function destroyImage(Project $project, ProjectImage $image)
    {
        abort_unless($image->project_id === $project->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $project->load('images');

        return $this->withPresentationFields($project);
    }

    private function normalizeLinksAndMeta(array $data, bool $withDefaults = false): array
    {
        if (array_key_exists('github_link', $data)) {
            $data['github_url'] = $data['github_url'] ?? $data['github_link'];
        }

        if (array_key_exists('demo_link', $data)) {
            $data['demo_url'] = $data['demo_url'] ?? $data['demo_link'];
        }

        unset($data['github_link'], $data['demo_link']);

        if ($withDefaults) {
            $data['category'] = $data['category'] ?? 'Web';
            $data['project_type'] = $data['project_type'] ?? 'personnel';
            $data['status'] = $data['status'] ?? 'terminé';
        }

        return $data;
    }

    private function withPresentationFields(Project $project): Project
    {
        $project->image_url = $this->publicStorageUrl($project->image);
        $galleryImages = $project->relationLoaded('images') ? $project->images : collect();
        $project->gallery_images = $galleryImages
            ->map(fn ($image) => [
                'id' => $image->id,
                'url' => $this->publicStorageUrl($image->path),
            ])
            ->values();
        $project->github_link = $project->github_url;
        $project->demo_link = $project->demo_url;

        return $project;
    }

    private function storeGalleryImages(Request $request, Project $project): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextOrder = (int) $project->images()->max('display_order') + 1;

        foreach ($request->file('images') as $index => $image) {
            $project->images()->create([
                'path' => $image->store('projects/gallery', 'public'),
                'display_order' => $nextOrder + $index,
            ]);
        }
    }
}
