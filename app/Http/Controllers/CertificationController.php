<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    public function index()
    {
        return Certification::orderByDesc('date')
            ->get()
            ->map(function ($certification) {
                $certification->file_url = $this->publicStorageUrl($certification->file);
                return $certification;
            });
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:180',
            'issuer' => 'required|string|max:180',
            'date' => 'required|date',
            'url' => 'nullable|url|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:12288',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('certifications', 'public');
        }

        $certification = Certification::create($data);
        $certification->file_url = $this->publicStorageUrl($certification->file);

        return $certification;
    }

    public function show(Certification $certification)
    {
        $certification->file_url = $this->publicStorageUrl($certification->file);

        return $certification;
    }

    public function update(Request $request, Certification $certification)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:180',
            'issuer' => 'sometimes|required|string|max:180',
            'date' => 'sometimes|required|date',
            'url' => 'nullable|url|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:12288',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if ($certification->file) {
                Storage::disk('public')->delete($certification->file);
            }
            $data['file'] = $request->file('file')->store('certifications', 'public');
        }

        $certification->update($data);
        $certification->file_url = $this->publicStorageUrl($certification->file);

        return $certification;
    }

    public function destroy(Certification $certification)
    {
        if ($certification->file) {
            Storage::disk('public')->delete($certification->file);
        }

        $certification->delete();

        return response()->json(['message' => 'Certification supprimée.']);
    }
}
