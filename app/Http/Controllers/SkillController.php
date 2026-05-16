<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    private const CATEGORIES = [
        'Frontend',
        'Backend',
        'Frameworks',
        'Mobile',
        'IoT / Électronique',
        'Base de données',
        'Outils',
    ];

    public function index(Request $request)
    {
        return Skill::query()
            ->when(!$request->boolean('include_inactive'), fn ($query) => $query->where('is_active', true))
            ->orderBy('category')
            ->orderBy('display_order')
            ->orderByDesc('percentage')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|string|in:' . implode(',', self::CATEGORIES),
            'level' => 'nullable|string|max:60',
            'icon' => 'nullable|string|max:120',
            'percentage' => 'required|integer|min:0|max:100',
            'display_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['level'] = $data['level'] ?? $this->levelFromPercentage((int) $data['percentage']);
        $data['display_order'] = $data['display_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        return Skill::create($data);
    }

    public function show(Skill $skill)
    {
        return $skill;
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:120',
            'category' => 'sometimes|required|string|in:' . implode(',', self::CATEGORIES),
            'level' => 'nullable|string|max:60',
            'icon' => 'nullable|string|max:120',
            'percentage' => 'sometimes|required|integer|min:0|max:100',
            'display_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($data['percentage']) && empty($data['level'])) {
            $data['level'] = $this->levelFromPercentage((int) $data['percentage']);
        }

        $skill->update($data);

        return $skill;
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return response()->json(['message' => 'Compétence supprimée.']);
    }

    private function levelFromPercentage(int $percentage): string
    {
        if ($percentage >= 80) {
            return 'Avancé';
        }

        if ($percentage >= 50) {
            return 'Intermédiaire';
        }

        return 'Débutant';
    }
}
