<?php

namespace Tests\Feature;

use App\Models\User;
use App\Events\UserStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserPresenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_marked_online_on_login(): void
    {
        Event::fake([UserStatusChanged::class]);

        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $user->refresh();
        $this->assertTrue($user->is_online);
        $this->assertNotNull($user->last_seen_at);
    }

    public function test_login_dispatches_status_changed_event(): void
    {
        Event::fake([UserStatusChanged::class]);

        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        Event::assertDispatched(UserStatusChanged::class, function ($event) use ($user) {
            return $event->userId === $user->id && $event->isOnline === true;
        });
    }

    public function test_user_is_marked_offline_on_logout(): void
    {
        Event::fake([UserStatusChanged::class]);

        $user = User::factory()->create(['is_online' => true]);

        $this->actingAs($user)->post('/logout');

        $user->refresh();
        $this->assertFalse($user->is_online);
        $this->assertNotNull($user->last_seen_at);
    }

    public function test_logout_dispatches_status_changed_event(): void
    {
        Event::fake([UserStatusChanged::class]);

        $user = User::factory()->create(['is_online' => true]);

        $this->actingAs($user)->post('/logout');

        Event::assertDispatched(UserStatusChanged::class, function ($event) use ($user) {
            return $event->userId === $user->id && $event->isOnline === false;
        });
    }

    public function test_mark_online_updates_last_seen_at(): void
    {
        $user = User::factory()->create(['is_online' => false, 'last_seen_at' => null]);
        $user->markOnline();

        $user->refresh();
        $this->assertTrue($user->is_online);
        $this->assertNotNull($user->last_seen_at);
    }

    public function test_mark_offline_updates_last_seen_at(): void
    {
        $user = User::factory()->create(['is_online' => true]);
        $user->markOffline();

        $user->refresh();
        $this->assertFalse($user->is_online);
        $this->assertNotNull($user->last_seen_at);
    }
}
