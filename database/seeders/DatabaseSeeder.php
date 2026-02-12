<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Friend;
use App\Models\Offre;
use App\Models\Application;
use App\Models\Profile;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\FriendRequestNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── 1. Seed Roles & Permissions ──────────────────────────────
        $this->call(RoleSeeder::class);
        $this->command->info('✓ Roles & permissions seeded');

        // ─── 2. Create known test users ───────────────────────────────

        // Admin / Recruteur
        $admin = User::create([
            'name'              => 'Admin',
            'prenom'            => 'Talentia',
            'role'              => 'recruteur',
            'specialite'        => 'Directeur RH',
            'photo'             => 'https://ui-avatars.com/api/?name=Admin+Talentia&background=0d6efd&color=fff&size=128',
            'bio'               => 'Administrateur principal de la plateforme Talentia. Passionné par le recrutement tech et l\'innovation RH.',
            'email'             => 'admin@talentia.local',
            'email_verified_at' => now(),
            'password'          => 'password',
        ]);
        $admin->assignRole('recruteur');

        // Regular User / Candidat
        $testUser = User::create([
            'name'              => 'Bahmoun',
            'prenom'            => 'Younes',
            'role'              => 'candidat',
            'specialite'        => 'Développeur Full Stack',
            'photo'             => 'https://ui-avatars.com/api/?name=Younes+Bahmoun&background=198754&color=fff&size=128',
            'bio'               => 'Développeur Full Stack passionné par Laravel et React. Toujours à la recherche de nouveaux défis techniques.',
            'email'             => 'test.user@talentia.local',
            'email_verified_at' => now(),
            'password'          => 'password',
        ]);
        $testUser->assignRole('candidat');

        $this->command->info('✓ 2 known test users created (admin@talentia.local / test.user@talentia.local)');

        // ─── 3. Create additional recruteurs ──────────────────────────

        $recruteurs = [];
        $recruteurData = [
            ['name' => 'Dupont',   'prenom' => 'Marie',     'specialite' => 'Talent Acquisition Manager',  'entreprise' => 'TechCorp France',    'email' => 'marie.dupont@techcorp.com'],
            ['name' => 'Laurent',  'prenom' => 'Philippe',  'specialite' => 'Directeur Technique',         'entreprise' => 'DataFlow SAS',       'email' => 'philippe.laurent@dataflow.com'],
            ['name' => 'Bernard',  'prenom' => 'Isabelle',  'specialite' => 'Responsable RH',              'entreprise' => 'CloudNine Solutions','email' => 'isabelle.bernard@cloudnine.com'],
            ['name' => 'Moreau',   'prenom' => 'François',  'specialite' => 'CTO',                         'entreprise' => 'StartupLab',         'email' => 'francois.moreau@startuplab.com'],
            ['name' => 'Garcia',   'prenom' => 'Elena',     'specialite' => 'HR Business Partner',         'entreprise' => 'InnovateTech',       'email' => 'elena.garcia@innovatetech.com'],
        ];

        foreach ($recruteurData as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'prenom'            => $data['prenom'],
                'role'              => 'recruteur',
                'specialite'        => $data['specialite'],
                'photo'             => 'https://ui-avatars.com/api/?name=' . urlencode($data['prenom'] . ' ' . $data['name']) . '&background=' . fake()->randomElement(['0D8ABC', '6610f2', 'dc3545', 'fd7e14']) . '&color=fff&size=128',
                'bio'               => fake()->paragraph(2),
                'email'             => $data['email'],
                'email_verified_at' => now(),
                'password'          => 'password',
            ]);
            $user->assignRole('recruteur');
            $recruteurs[] = $user;
        }

        $this->command->info('✓ 5 recruteurs created');

        // ─── 4. Create candidats ──────────────────────────────────────

        $candidats = [$testUser];
        $candidatData = [
            ['name' => 'Martin',    'prenom' => 'Lucas',      'specialite' => 'Développeur PHP',         'email' => 'lucas.martin@email.com'],
            ['name' => 'Petit',     'prenom' => 'Sophie',     'specialite' => 'Designer UX/UI',          'email' => 'sophie.petit@email.com'],
            ['name' => 'Roux',      'prenom' => 'Alexandre',  'specialite' => 'Data Scientist',          'email' => 'alex.roux@email.com'],
            ['name' => 'Simon',     'prenom' => 'Emma',       'specialite' => 'Développeur Frontend',    'email' => 'emma.simon@email.com'],
            ['name' => 'Lefebvre',  'prenom' => 'Thomas',     'specialite' => 'DevOps Engineer',         'email' => 'thomas.lefebvre@email.com'],
            ['name' => 'Michel',    'prenom' => 'Clara',      'specialite' => 'Chef de projet',          'email' => 'clara.michel@email.com'],
            ['name' => 'Leroy',     'prenom' => 'Hugo',       'specialite' => 'Développeur Backend',     'email' => 'hugo.leroy@email.com'],
            ['name' => 'David',     'prenom' => 'Léa',        'specialite' => 'Marketing digital',       'email' => 'lea.david@email.com'],
            ['name' => 'Bertrand',  'prenom' => 'Maxime',     'specialite' => 'Architecte logiciel',     'email' => 'maxime.bertrand@email.com'],
            ['name' => 'Fontaine',  'prenom' => 'Camille',    'specialite' => 'Ingénieur QA',            'email' => 'camille.fontaine@email.com'],
            ['name' => 'Girard',    'prenom' => 'Antoine',    'specialite' => 'Développeur Full Stack',  'email' => 'antoine.girard@email.com'],
            ['name' => 'Bonnet',    'prenom' => 'Julie',      'specialite' => 'Scrum Master',            'email' => 'julie.bonnet@email.com'],
            ['name' => 'Mercier',   'prenom' => 'Nicolas',    'specialite' => 'Ingénieur Cloud',         'email' => 'nicolas.mercier@email.com'],
            ['name' => 'Robin',     'prenom' => 'Sarah',      'specialite' => 'Data Analyst',            'email' => 'sarah.robin@email.com'],
            ['name' => 'Duval',     'prenom' => 'Romain',     'specialite' => 'Consultant IT',           'email' => 'romain.duval@email.com'],
        ];

        foreach ($candidatData as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'prenom'            => $data['prenom'],
                'role'              => 'candidat',
                'specialite'        => $data['specialite'],
                'photo'             => 'https://ui-avatars.com/api/?name=' . urlencode($data['prenom'] . ' ' . $data['name']) . '&background=' . fake()->randomElement(['0D8ABC', '198754', '6610f2', '0d6efd', 'dc3545', 'fd7e14']) . '&color=fff&size=128',
                'bio'               => fake()->paragraph(2),
                'email'             => $data['email'],
                'email_verified_at' => now(),
                'password'          => 'password',
            ]);
            $user->assignRole('candidat');
            $candidats[] = $user;
        }

        $this->command->info('✓ 15 candidats created');

        // ─── 5. Create Profiles for some users ────────────────────────

        $allUsers = array_merge([$admin], $recruteurs, $candidats);
        foreach ($allUsers as $user) {
            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                'titre'       => $user->specialite,
                'formation'   => fake()->randomElement([
                    'Master Informatique - Université Paris-Saclay',
                    'DUT Informatique - IUT Lyon',
                    'École 42 - Paris',
                    'Licence Pro Web - Université de Bordeaux',
                    'Master MIAGE - Université de Nantes',
                    'Ingénieur INSA Lyon',
                    'Master Data Science - Polytechnique',
                    'BTS SIO - SLAM',
                    'MBA Digital Management - HEC Paris',
                    'Autodidacte - Certifications AWS & Google Cloud',
                ]),
                'experiences' => fake()->randomElement([
                    "3 ans chez Capgemini - Développeur Java\n2 ans chez Atos - Lead Developer",
                    "5 ans chez Orange - Chef de projet\n1 an freelance",
                    "2 ans chez Sopra Steria - Consultant IT\n3 ans chez BNP Paribas - Analyste",
                    "4 ans chez OVHcloud - Ingénieur DevOps\n1 an chez Scaleway",
                    "1 an stage chez Ubisoft - Game Developer\n2 ans chez Dassault Systèmes",
                    "6 ans chez Thales - Architecte logiciel",
                    "Freelance depuis 4 ans - Développement web & mobile",
                    "3 ans chez Société Générale - Data Analyst\n2 ans chez AXA - Data Scientist",
                ]),
                'competences' => fake()->randomElement([
                    'PHP, Laravel, MySQL, Docker, Git, REST API',
                    'React, TypeScript, Node.js, GraphQL, AWS',
                    'Python, TensorFlow, Pandas, SQL, Tableau',
                    'Java, Spring Boot, Microservices, Kubernetes',
                    'Figma, Adobe XD, Sketch, HTML/CSS, User Research',
                    'Vue.js, Nuxt.js, Tailwind CSS, PostgreSQL',
                    'C#, .NET, Azure, CI/CD, Terraform',
                    'Agile/Scrum, Jira, Management d\'équipe, Communication',
                    'JavaScript, Angular, MongoDB, Firebase, GCP',
                    'Linux, Ansible, Jenkins, Docker, Prometheus, Grafana',
                ]),
                'photo' => null,
            ]);
        }

        $this->command->info('✓ Profiles created for all users');

        // ─── 6. Create Job Offers ─────────────────────────────────────

        $offresData = [
            [
                'titre'        => 'Développeur Laravel Senior',
                'description'  => "Nous recherchons un développeur Laravel expérimenté pour rejoindre notre équipe technique. Vous travaillerez sur notre plateforme SaaS innovante utilisée par plus de 10 000 entreprises.\n\nResponsabilités :\n- Développer de nouvelles fonctionnalités\n- Maintenir et améliorer le code existant\n- Participer aux code reviews\n- Collaborer avec l'équipe produit\n\nProfil recherché :\n- 5+ ans d'expérience avec Laravel\n- Maîtrise de PHP 8+\n- Connaissance de PostgreSQL & Redis\n- Expérience avec Docker",
                'entreprise'   => 'TechCorp France',
                'type_contrat' => 'CDI',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Designer UX/UI - Application Mobile',
                'description'  => "Rejoignez notre studio de design pour concevoir des expériences utilisateur exceptionnelles pour nos applications mobiles.\n\nMissions :\n- Concevoir des maquettes et prototypes interactifs\n- Conduire des tests utilisateurs\n- Créer des systèmes de design cohérents\n- Collaborer avec les développeurs\n\nProfil :\n- 3+ ans en design UX/UI\n- Maîtrise de Figma\n- Portfolio démontrant vos réalisations\n- Sensibilité mobile-first",
                'entreprise'   => 'DataFlow SAS',
                'type_contrat' => 'CDI',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Data Scientist Junior',
                'description'  => "Intégrez notre équipe Data pour analyser et exploiter nos données business. Vous contribuerez à des projets de machine learning et d'intelligence artificielle.\n\nCompétences requises :\n- Python, Pandas, Scikit-learn\n- SQL avancé\n- Connaissance des statistiques\n- Curiosité et esprit analytique",
                'entreprise'   => 'CloudNine Solutions',
                'type_contrat' => 'CDD',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'DevOps Engineer',
                'description'  => "Nous cherchons un ingénieur DevOps pour automatiser et optimiser notre infrastructure cloud.\n\nEnvironnement technique :\n- AWS / GCP\n- Docker & Kubernetes\n- Terraform & Ansible\n- CI/CD avec GitLab\n\nSalaire : 50-65K€ selon expérience",
                'entreprise'   => 'StartupLab',
                'type_contrat' => 'CDI',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Stage Développeur Frontend React',
                'description'  => "Stage de 6 mois pour un étudiant passionné par le développement frontend.\n\nVous apprendrez :\n- React & Next.js\n- TypeScript\n- Tests unitaires avec Jest\n- Méthodologies Agile\n\nGratification : 1200€/mois\nTélétravail : 2 jours par semaine",
                'entreprise'   => 'InnovateTech',
                'type_contrat' => 'Stage',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Chef de Projet Digital',
                'description'  => "Pilotez des projets digitaux ambitieux pour nos clients grands comptes.\n\nResponsabilités :\n- Gérer le planning et le budget des projets\n- Coordonner les équipes techniques et créatives\n- Assurer la relation client\n- Garantir la qualité des livrables\n\nExpérience : 4+ ans en gestion de projet digital",
                'entreprise'   => 'TechCorp France',
                'type_contrat' => 'CDI',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Développeur PHP/Symfony',
                'description'  => "Rejoignez notre équipe pour développer des applications web robustes avec Symfony.\n\nStack technique :\n- PHP 8.2+, Symfony 7\n- PostgreSQL, Redis\n- RabbitMQ, Elasticsearch\n- Docker, GitLab CI\n\nAvantages : RTT, tickets restaurant, mutuelle premium",
                'entreprise'   => 'DataFlow SAS',
                'type_contrat' => 'CDI',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Alternance Marketing Digital',
                'description'  => "Recherche un alternant motivé pour notre équipe marketing.\n\nMissions :\n- Gestion des réseaux sociaux\n- Création de contenu\n- Campagnes SEO/SEA\n- Analyse de performance\n\nRythme : 3 semaines entreprise / 1 semaine école",
                'entreprise'   => 'CloudNine Solutions',
                'type_contrat' => 'Alternance',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Consultant IT - Transformation Digitale',
                'description'  => "Accompagnez nos clients dans leur transformation digitale. Missions variées dans différents secteurs (banque, assurance, retail).\n\nProfil :\n- Diplôme ingénieur ou école de commerce\n- 2+ ans d'expérience en conseil\n- Capacité d'analyse et de synthèse\n- Excellent relationnel",
                'entreprise'   => 'StartupLab',
                'type_contrat' => 'Freelance',
                'status'       => 'ouvert',
            ],
            [
                'titre'        => 'Architecte Cloud AWS',
                'description'  => "Poste clé pour concevoir et déployer notre infrastructure multi-cloud.\n\nExpertise attendue :\n- Certification AWS Solutions Architect\n- Expérience Terraform\n- Connaissance des bonnes pratiques de sécurité\n- Leadership technique\n\nPoste basé à Paris avec 3j de télétravail",
                'entreprise'   => 'InnovateTech',
                'type_contrat' => 'CDI',
                'status'       => 'ferme',
            ],
        ];

        $allRecruteurs = array_merge([$admin], $recruteurs);

        foreach ($offresData as $index => $data) {
            $recruiter = $allRecruteurs[$index % count($allRecruteurs)];
            Offre::create(array_merge($data, [
                'user_id' => $recruiter->id,
                'image'   => null,
            ]));
        }

        $this->command->info('✓ 10 job offers created');

        // ─── 7. Create Friend Relationships ───────────────────────────

        $friendPairs = [
            // testUser's connections
            [$testUser->id, $candidats[1]->id, 'accepted'],
            [$testUser->id, $candidats[2]->id, 'accepted'],
            [$testUser->id, $candidats[3]->id, 'accepted'],
            [$testUser->id, $candidats[4]->id, 'pending'],
            [$candidats[5]->id, $testUser->id, 'pending'],  // invitation received
            [$candidats[6]->id, $testUser->id, 'pending'],  // invitation received
            // Other connections
            [$candidats[1]->id, $candidats[3]->id, 'accepted'],
            [$candidats[2]->id, $candidats[4]->id, 'accepted'],
            [$candidats[3]->id, $candidats[5]->id, 'accepted'],
            [$candidats[7]->id, $candidats[8]->id, 'accepted'],
            [$candidats[9]->id, $candidats[10]->id, 'accepted'],
            [$candidats[11]->id, $candidats[12]->id, 'pending'],
            [$candidats[13]->id, $candidats[14]->id, 'rejected'],
            [$candidats[1]->id, $candidats[7]->id, 'accepted'],
            [$candidats[2]->id, $candidats[9]->id, 'accepted'],
        ];

        foreach ($friendPairs as [$userId, $friendId, $status]) {
            Friend::create([
                'user_id'   => $userId,
                'friend_id' => $friendId,
                'status'    => $status,
            ]);
        }

        $this->command->info('✓ 15 friend relationships created');

        // ─── 8. Create Applications ───────────────────────────────────

        $offres = Offre::where('status', 'ouvert')->get();

        // testUser applies to a few jobs
        Application::create(['user_id' => $testUser->id, 'offre_id' => $offres[0]->id, 'status' => 'pending']);
        Application::create(['user_id' => $testUser->id, 'offre_id' => $offres[3]->id, 'status' => 'accepted']);

        // Other candidats apply
        foreach ($offres as $offre) {
            $applicants = collect($candidats)->random(rand(2, 5));
            foreach ($applicants as $applicant) {
                Application::firstOrCreate(
                    ['user_id' => $applicant->id, 'offre_id' => $offre->id],
                    ['status' => fake()->randomElement(['pending', 'pending', 'pending', 'accepted', 'rejected'])]
                );
            }
        }

        $this->command->info('✓ Applications created');

        // ─── 9. Create Conversations & Messages ───────────────────────
        // testUser has accepted friends: candidats[1] (Lucas), candidats[2] (Sophie), candidats[3] (Alexandre)

        $lucas = $candidats[1];
        $sophie = $candidats[2];
        $alexandre = $candidats[3];

        // Conversation 1: testUser <-> Lucas Martin
        $conv1 = Conversation::between($testUser->id, $lucas->id);
        $conv1Messages = [
            [$lucas->id, 'Salut Younes ! Comment tu vas ?', now()->subHours(5)],
            [$testUser->id, 'Hey Lucas ! Ça va bien et toi ? Tu travailles sur quoi en ce moment ?', now()->subHours(4)->subMinutes(50)],
            [$lucas->id, 'Je suis sur un projet Laravel assez sympa, une plateforme de recrutement 😄', now()->subHours(4)->subMinutes(40)],
            [$testUser->id, 'Ah trop bien ! Moi aussi je bosse avec Laravel en ce moment. Tu utilises quelle version ?', now()->subHours(4)->subMinutes(30)],
            [$lucas->id, 'Laravel 12 avec Reverb pour le temps réel. C\'est vraiment puissant !', now()->subHours(4)->subMinutes(20)],
            [$testUser->id, 'Cool ! On devrait partager nos retours d\'expérience un de ces jours', now()->subHours(4)->subMinutes(10)],
            [$lucas->id, 'Bonne idée ! T\'es dispo cette semaine pour un call ?', now()->subHours(3)],
            [$lucas->id, 'Je pense qu\'on pourrait aussi parler de Livewire vs Inertia', now()->subMinutes(45)],
            [$lucas->id, 'Au fait, t\'as vu l\'offre de DevOps chez StartupLab ? Elle a l\'air intéressante', now()->subMinutes(10)],
        ];

        foreach ($conv1Messages as [$senderId, $body, $createdAt]) {
            Message::create([
                'conversation_id' => $conv1->id,
                'sender_id' => $senderId,
                'body' => $body,
                'is_read' => $senderId === $testUser->id ? true : false,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
        // Mark older messages as read, keep last 3 from Lucas unread
        Message::where('conversation_id', $conv1->id)
            ->where('sender_id', $lucas->id)
            ->where('created_at', '<', now()->subHours(2))
            ->update(['is_read' => true]);

        // Conversation 2: testUser <-> Sophie Petit
        $conv2 = Conversation::between($testUser->id, $sophie->id);
        $conv2Messages = [
            [$testUser->id, 'Bonjour Sophie ! J\'ai vu ton profil, tu fais du UX/UI c\'est ça ?', now()->subDays(2)->subHours(3)],
            [$sophie->id, 'Bonjour Younes ! Oui exactement, je suis designer UX/UI spécialisée en mobile', now()->subDays(2)->subHours(2)->subMinutes(45)],
            [$testUser->id, 'Super ! Je cherche justement quelqu\'un pour m\'aider sur un design d\'interface', now()->subDays(2)->subHours(2)->subMinutes(30)],
            [$sophie->id, 'Avec plaisir ! Tu peux m\'en dire plus ?', now()->subDays(2)->subHours(2)->subMinutes(20)],
            [$testUser->id, 'C\'est une app de messagerie en temps réel avec des indicateurs de présence', now()->subDays(2)->subHours(2)->subMinutes(10)],
            [$sophie->id, 'Oh ça me parle ! J\'ai justement travaillé sur des projets similaires avec Figma', now()->subDays(2)->subHours(2)],
            [$testUser->id, 'Parfait, je t\'envoie le brief demain matin 👍', now()->subDays(2)->subHours(1)->subMinutes(50)],
            [$sophie->id, 'Top ! J\'attends ça avec impatience 🎨', now()->subDays(2)->subHours(1)->subMinutes(45)],
        ];

        foreach ($conv2Messages as [$senderId, $body, $createdAt]) {
            Message::create([
                'conversation_id' => $conv2->id,
                'sender_id' => $senderId,
                'body' => $body,
                'is_read' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Conversation 3: testUser <-> Alexandre Roux
        $conv3 = Conversation::between($testUser->id, $alexandre->id);
        $conv3Messages = [
            [$alexandre->id, 'Hey Younes ! Tu connais un bon dataset pour du NLP en français ?', now()->subDays(1)->subHours(6)],
            [$testUser->id, 'Salut Alex ! Regarde GigaFrench ou CamemBERT, ils sont pas mal', now()->subDays(1)->subHours(5)->subMinutes(30)],
            [$alexandre->id, 'Merci je vais regarder ! Tu bosses aussi avec du Python ?', now()->subDays(1)->subHours(5)],
            [$testUser->id, 'Oui un peu pour les scripts d\'automatisation, mais surtout PHP côté web', now()->subDays(1)->subHours(4)->subMinutes(45)],
            [$alexandre->id, 'OK cool. Au fait j\'ai postulé à l\'offre Data Scientist chez CloudNine', now()->subDays(1)->subHours(4)->subMinutes(30)],
            [$testUser->id, 'Bonne chance ! Tiens-moi au courant 🤞', now()->subDays(1)->subHours(4)->subMinutes(20)],
            [$alexandre->id, 'Merci ! Je te dirai. À plus !', now()->subDays(1)->subHours(4)],
        ];

        foreach ($conv3Messages as [$senderId, $body, $createdAt]) {
            Message::create([
                'conversation_id' => $conv3->id,
                'sender_id' => $senderId,
                'body' => $body,
                'is_read' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('✓ 3 conversations with ' . (count($conv1Messages) + count($conv2Messages) + count($conv3Messages)) . ' messages created');

        // ─── 10. Create Notifications ──────────────────────────────────

        // Pending friend requests from candidats[5] and candidats[6] generate notifications
        $testUser->notify(new FriendRequestNotification($candidats[5]));
        $testUser->notify(new FriendRequestNotification($candidats[6]));

        $this->command->info('✓ 2 friend request notifications created');

        // ─── Summary ─────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('══════════════════════════════════════════════');
        $this->command->info('  🎉 Database seeded successfully!');
        $this->command->info('══════════════════════════════════════════════');
        $this->command->info('  📧 Admin login:  admin@talentia.local');
        $this->command->info('  📧 User login:   test.user@talentia.local');
        $this->command->info('  🔑 Password:     password');
        $this->command->info('  💬 3 conversations with messages');
        $this->command->info('  🔔 2 pending friend request notifications');
        $this->command->info('══════════════════════════════════════════════');
    }
}

