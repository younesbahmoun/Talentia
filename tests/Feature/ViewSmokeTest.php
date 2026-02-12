<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\Friend;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Role::firstOrCreate(['name' => 'candidat', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'recruteur', 'guard_name' => 'web']);
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);

        return $user;
    }

    public function test_authenticated_core_views_render_without_errors(): void
    {
        $user = $this->createUserWithRole('candidat');
        $friend = $this->createUserWithRole('candidat');

        Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => 'accepted',
        ]);

        $conversation = Conversation::between($user->id, $friend->id);

        $offre = Offre::create([
            'titre' => 'Developpeur Laravel',
            'description' => 'Mission full stack',
            'image' => 'https://example.com/offre.jpg',
            'entreprise' => 'Talentia',
            'type_contrat' => 'CDI',
            'status' => 'ouvert',
            'user_id' => $friend->id,
        ]);

        $this->actingAs($user);

        $urls = [
            route('dashboard'),
            route('profile.show'),
            route('profiles.all'),
            route('network'),
            route('notifications'),
            route('offres.index'),
            route('offres.detail', $offre->id),
            route('conversations.index'),
            route('conversations.show', $conversation->id),
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('Talentia');
            $response->assertSee('navbar-toggler');
        }
    }

    public function test_dashboard_contains_main_navigation_entries(): void
    {
        $user = $this->createUserWithRole('candidat');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Mon profil');
        $response->assertSee('Network');
        $response->assertSee('Offres');
        $response->assertSee('data-bs-toggle="collapse"', false);
        $response->assertSee('id="navbarNav"', false);
    }

    public function test_recruiter_candidates_view_uses_candidate_profile_links(): void
    {
        $recruiter = $this->createUserWithRole('recruteur');
        $candidate = $this->createUserWithRole('candidat');

        $offre = Offre::create([
            'titre' => 'Backend Engineer',
            'description' => 'API development',
            'image' => 'https://example.com/backend.jpg',
            'entreprise' => 'Talentia',
            'type_contrat' => 'CDI',
            'status' => 'ouvert',
            'user_id' => $recruiter->id,
        ]);

        Application::create([
            'user_id' => $candidate->id,
            'offre_id' => $offre->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($recruiter)->get(route('recruiter.candidats'));

        $response->assertOk();
        $response->assertSee(route('profile.detail', $candidate->id));
    }

    public function test_non_recruiter_cannot_access_recruiter_candidates_view(): void
    {
        $candidate = $this->createUserWithRole('candidat');

        $response = $this->actingAs($candidate)->get(route('recruiter.candidats'));

        $response->assertForbidden();
    }

    public function test_app_css_keeps_bootstrap_navbar_collapse_visible(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertStringContainsString('.navbar .navbar-collapse', $css);
        $this->assertStringContainsString('visibility: visible', $css);
    }
}
