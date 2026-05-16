<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    private const PROJECT_TYPES = ['académique', 'personnel', 'professionnel'];
    private const STATUSES = ['terminé', 'en cours'];

    public function index()
    {
        return Project::orderByDesc('year')
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
            'image' => 'nullable|image|max:4096',
        ]);

        $data = $this->normalizeLinksAndMeta($data, true);

        if (isset($data['technologies'])) {
            $data['technologies'] = array_filter(array_map('trim', explode(',', (string) $data['technologies'])));
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($data);

        return $this->withPresentationFields($project);
    }

    public function show(Project $project)
    {
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
            'image' => 'nullable|image|max:4096',
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

        return $this->withPresentationFields($project);
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return response()->json(['message' => 'Projet supprimé.']);
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
        $project->image_url = $project->image ? url(Storage::url($project->image)) : null;
        $project->github_link = $project->github_url;
        $project->demo_link = $project->demo_url;

        return $project;
    }
}
