<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        return Experience::orderByDesc('start_date')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company' => 'required|string|max:180',
            'position' => 'required|string|max:180',
            'location' => 'nullable|string|max:120',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'technologies' => 'nullable',
        ]);

        $data = $this->normalizeTechnologies($data);

        return Experience::create($data);
    }

    public function show(Experience $experience)
    {
        return $experience;
    }

    public function update(Request $request, Experience $experience)
    {
        $data = $request->validate([
            'company' => 'sometimes|required|string|max:180',
            'position' => 'sometimes|required|string|max:180',
            'location' => 'nullable|string|max:120',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'technologies' => 'nullable',
        ]);

        $data = $this->normalizeTechnologies($data);

        $experience->update($data);

        return $experience;
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return response()->json(['message' => 'Expérience supprimée.']);
    }

    private function normalizeTechnologies(array $data): array
    {
        if (array_key_exists('technologies', $data)) {
            $data['technologies'] = is_array($data['technologies'])
                ? array_values(array_filter(array_map('trim', $data['technologies'])))
                : array_values(array_filter(array_map('trim', explode(',', (string) $data['technologies']))));
        }

        return $data;
    }
}
