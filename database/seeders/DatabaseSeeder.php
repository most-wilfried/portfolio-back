<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Experience;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::where('email', 'virtut@gmail.com')->delete();

        User::updateOrCreate(
            ['email' => 'wiltchout@gmail.com'],
            [
                'name' => 'Wiltchout',
                'password' => 'MostWanted17',
                'email_verified_at' => now(),
            ]
        );

        PortfolioProfile::updateOrCreate(
            ['id' => 1],
            [
                'full_name' => 'Votre Nom',
                'age' => null,
                'professional_title' => 'Développeur Full Stack',
                'location' => null,
                'short_description' => 'Ajoutez ici votre courte description depuis le tableau de bord.',
                'about_intro' => 'Présentez-vous depuis la section Profil du tableau de bord.',
                'journey' => 'Décrivez votre parcours depuis l’administration.',
                'goals' => 'Ajoutez vos objectifs professionnels depuis l’administration.',
                'github_url' => null,
                'linkedin_url' => null,
                'whatsapp_number' => null,
                'email' => null,
                'phone' => null,
            ]
        );

        collect([
            ['name' => 'HTML', 'category' => 'Frontend', 'level' => 'Avancé'],
            ['name' => 'CSS', 'category' => 'Frontend', 'level' => 'Avancé'],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'level' => 'Intermédiaire'],
            ['name' => 'React', 'category' => 'Frameworks', 'level' => 'Intermédiaire'],
            ['name' => 'PHP', 'category' => 'Backend', 'level' => 'Intermédiaire'],
            ['name' => 'Laravel', 'category' => 'Frameworks', 'level' => 'Intermédiaire'],
            ['name' => 'MySQL', 'category' => 'Base de données', 'level' => 'Intermédiaire'],
            ['name' => 'Flutter', 'category' => 'Mobile', 'level' => 'Débutant'],
            ['name' => 'Arduino', 'category' => 'IoT / Électronique', 'level' => 'Intermédiaire'],
            ['name' => 'ESP32', 'category' => 'IoT / Électronique', 'level' => 'Intermédiaire'],
            ['name' => 'Git', 'category' => 'Outils', 'level' => 'Intermédiaire'],
            ['name' => 'GitHub', 'category' => 'Outils', 'level' => 'Intermédiaire'],
        ])->each(fn ($skill) => Skill::updateOrCreate(
            ['name' => $skill['name']],
            $skill
        ));

        collect([
            [
                'title' => 'Système de sécurité intelligent avec ESP32',
                'description' => 'Prototype IoT avec capteurs, alertes et contrôle à distance.',
                'category' => 'IoT',
                'technologies' => ['ESP32', 'Arduino', 'C++'],
                'github_url' => null,
                'demo_url' => null,
            ],
            [
                'title' => 'Application mobile Flutter',
                'description' => 'Application mobile responsive connectée à une API REST.',
                'category' => 'Mobile',
                'technologies' => ['Flutter', 'Dart'],
                'github_url' => null,
                'demo_url' => null,
            ],
            [
                'title' => 'Application web Laravel',
                'description' => 'Backend Laravel sécurisé avec authentification et tableau de bord.',
                'category' => 'Web',
                'technologies' => ['Laravel', 'PHP', 'MySQL'],
                'github_url' => null,
                'demo_url' => null,
            ],
            [
                'title' => 'Portfolio dynamique',
                'description' => 'Portfolio React + Laravel administrable via API REST.',
                'category' => 'Full Stack',
                'technologies' => ['React', 'Laravel', 'MySQL'],
                'github_url' => null,
                'demo_url' => null,
            ],
        ])->each(fn ($project) => Project::updateOrCreate(
            ['title' => $project['title']],
            $project
        ));

        Experience::updateOrCreate(
            ['company' => 'Projet personnel', 'position' => 'Développeur Full Stack'],
            [
                'location' => 'Remote',
                'start_date' => now()->subMonths(6)->toDateString(),
                'end_date' => null,
                'description' => 'Conception et développement de solutions web, mobiles et IoT orientées portfolio professionnel.',
            ]
        );

        Certification::updateOrCreate(
            ['title' => 'Certification à compléter'],
            [
                'issuer' => 'Organisme',
                'date' => now()->toDateString(),
                'url' => null,
                'description' => 'Remplacer cet exemple par une vraie certification depuis le dashboard.',
            ]
        );
    }
}
