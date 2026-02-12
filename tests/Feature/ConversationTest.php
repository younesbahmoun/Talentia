<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function createFriendship(User $user1, User $user2, string $status = 'accepted'): Friend
    {
        return Friend::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
            'status' => $status,
        ]);
    }

    public function test_authenticated_user_can_view_conversation_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/conversations');
        $response->assertStatus(200);
    }

    public function test_conversation_list_shows_existing_conversations(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2);

        $conversation = Conversation::between($user1->id, $user2->id);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Hello!',
        ]);

        $response = $this->actingAs($user1)->get('/conversations');
        $response->assertStatus(200);
        $response->assertSee($user2->name);
    }

    public function test_user_can_start_conversation_with_accepted_friend(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2, 'accepted');

        $response = $this->actingAs($user1)->post('/conversations', [
            'user_id' => $user2->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations', [
            'user_one_id' => min($user1->id, $user2->id),
            'user_two_id' => max($user1->id, $user2->id),
        ]);
    }

    public function test_user_cannot_start_conversation_with_non_friend(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->post('/conversations', [
            'user_id' => $user2->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('conversations', [
            'user_one_id' => min($user1->id, $user2->id),
            'user_two_id' => max($user1->id, $user2->id),
        ]);
    }

    public function test_user_cannot_start_conversation_with_pending_friend(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2, 'pending');

        $response = $this->actingAs($user1)->post('/conversations', [
            'user_id' => $user2->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_start_conversation_with_self(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/conversations', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_view_own_conversation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2);
        $conversation = Conversation::between($user1->id, $user2->id);

        $response = $this->actingAs($user1)->get('/conversations/' . $conversation->id);
        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_users_conversation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $this->createFriendship($user1, $user2);
        $conversation = Conversation::between($user1->id, $user2->id);

        $response = $this->actingAs($user3)->get('/conversations/' . $conversation->id);
        $response->assertStatus(403);
    }

    public function test_conversation_shows_messages(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2);
        $conversation = Conversation::between($user1->id, $user2->id);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Test message content',
        ]);

        $response = $this->actingAs($user1)->get('/conversations/' . $conversation->id);
        $response->assertStatus(200);
        $response->assertSee('Test message content');
    }

    public function test_viewing_conversation_marks_messages_as_read(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->createFriendship($user1, $user2);
        $conversation = Conversation::between($user1->id, $user2->id);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Hello',
            'is_read' => false,
        ]);

        // User2 views the conversation - messages from user1 should be marked as read
        $this->actingAs($user2)->get('/conversations/' . $conversation->id);

        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_unauthenticated_user_cannot_access_conversations(): void
    {
        $response = $this->get('/conversations');
        $response->assertRedirect('/login');
    }
}
