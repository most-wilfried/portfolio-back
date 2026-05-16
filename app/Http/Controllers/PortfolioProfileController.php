<?php

namespace App\Http\Controllers;

use App\Models\PortfolioProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioProfileController extends Controller
{
    public function show()
    {
        return $this->withAvatarUrl($this->profile());
    }

    public function update(Request $request)
    {
        $profile = $this->profile();

        $data = $request->validate([
            'full_name' => 'required|string|max:180',
            'age' => 'nullable|integer|min:1|max:120',
            'professional_title' => 'required|string|max:180',
            'location' => 'nullable|string|max:180',
            'short_description' => 'nullable|string|max:600',
            'about_intro' => 'nullable|string',
            'journey' => 'nullable|string',
            'goals' => 'nullable|string',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:180',
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|max:10240',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:12288',
        ]);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('profile', 'public');
        }

        if ($request->hasFile('cv')) {
            if ($profile->cv) {
                Storage::disk('public')->delete($profile->cv);
            }
            $data['cv'] = $request->file('cv')->store('profile/cv', 'public');
        }

        $profile->update($data);

        return $this->withAvatarUrl($profile);
    }

    private function profile(): PortfolioProfile
    {
        return PortfolioProfile::firstOrCreate([], [
            'full_name' => 'Votre Nom',
            'professional_title' => 'Développeur Full Stack',
            'short_description' => 'Ajoutez ici votre courte description depuis le tableau de bord.',
            'about_intro' => 'Présentez-vous depuis la section Profil du tableau de bord.',
            'journey' => 'Décrivez votre parcours depuis l’administration.',
            'goals' => 'Ajoutez vos objectifs professionnels depuis l’administration.',
        ]);
    }

    private function withAvatarUrl(PortfolioProfile $profile): PortfolioProfile
    {
        $profile->avatar_url = $this->publicStorageUrl($profile->avatar);
        $profile->cv_url = $this->publicStorageUrl($profile->cv);

        return $profile;
    }
}
