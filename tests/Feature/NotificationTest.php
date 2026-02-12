<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Offre;
use App\Models\Application;
use App\Events\NewNotification;
use App\Notifications\FriendRequestNotification;
use App\Notifications\NewMessageNotification;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_friend_request_creates_notification(): void
    {
        Event::fake([NewNotification::class]);

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->actingAs($sender)->get('/friends/add?friend_id=' . $receiver->id);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $receiver->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_friend_request_broadcasts_notification(): void
    {
        Event::fake([NewNotification::class]);

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->actingAs($sender)->get('/friends/add?friend_id=' . $receiver->id);

        Event::assertDispatched(NewNotification::class, function ($event) use ($receiver) {
            return $event->userId === $receiver->id && $event->type === 'friend_request';
        });
    }

    public function test_friend_acceptance_broadcasts_notification(): void
    {
        Event::fake([NewNotification::class]);

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        Friend::create([
            'user_id' => $sender->id,
            'friend_id' => $receiver->id,
            'status' => 'pending',
        ]);

        $this->actingAs($receiver)->get('/friends/accept?friend_id=' . $sender->id);

        Event::assertDispatched(NewNotification::class, function ($event) use ($sender) {
            return $event->userId === $sender->id && $event->type === 'friend_accepted';
        });
    }

    public function test_message_creates_notification_for_receiver(): void
    {
        Event::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Friend::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
            'status' => 'accepted',
        ]);
        $conversation = Conversation::between($user1->id, $user2->id);

        $this->actingAs($user1)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Test notification',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user2->id,
        ]);
    }

    public function test_unread_notification_count_endpoint(): void
    {
        $user = User::factory()->create();
        $sender = User::factory()->create();

        // Create a notification
        $user->notify(new FriendRequestNotification($sender));

        $response = $this->actingAs($user)->getJson('/notifications/unread-count');
        $response->assertJson(['count' => 1]);
    }

    public function test_viewing_notifications_marks_them_as_read(): void
    {
        $user = User::factory()->create();
        $sender = User::factory()->create();

        $user->notify(new FriendRequestNotification($sender));
        $this->assertEquals(1, $user->unreadNotifications->count());

        $this->actingAs($user)->get('/notifications');
        $user->refresh();
        $this->assertEquals(0, $user->unreadNotifications->count());
    }

    public function test_notification_page_displays_correctly(): void
    {
        $user = User::factory()->create();
        $sender = User::factory()->create();
        $user->notify(new FriendRequestNotification($sender));

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
        $response->assertSee('demande d\'ami');
    }
}
