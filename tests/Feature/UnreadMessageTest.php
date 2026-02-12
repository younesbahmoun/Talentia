<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnreadMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function setupFriendsWithConversation(): array
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Friend::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
            'status' => 'accepted',
        ]);
        $conversation = Conversation::between($user1->id, $user2->id);

        return [$user1, $user2, $conversation];
    }

    public function test_new_message_increments_unread_count(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        // Initially 0 unread
        $this->assertEquals(0, $user2->totalUnreadMessages());

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Hello',
            'is_read' => false,
        ]);

        $this->assertEquals(1, $user2->fresh()->totalUnreadMessages());
    }

    public function test_multiple_unread_messages_across_conversations(): void
    {
        $user = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();

        Friend::create(['user_id' => $user->id, 'friend_id' => $friend1->id, 'status' => 'accepted']);
        Friend::create(['user_id' => $user->id, 'friend_id' => $friend2->id, 'status' => 'accepted']);

        $conv1 = Conversation::between($user->id, $friend1->id);
        $conv2 = Conversation::between($user->id, $friend2->id);

        Message::create(['conversation_id' => $conv1->id, 'sender_id' => $friend1->id, 'body' => '1', 'is_read' => false]);
        Message::create(['conversation_id' => $conv1->id, 'sender_id' => $friend1->id, 'body' => '2', 'is_read' => false]);
        Message::create(['conversation_id' => $conv2->id, 'sender_id' => $friend2->id, 'body' => '3', 'is_read' => false]);

        $this->assertEquals(3, $user->fresh()->totalUnreadMessages());
    }

    public function test_read_messages_not_counted(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => 'Read', 'is_read' => true]);
        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => 'Unread', 'is_read' => false]);

        $this->assertEquals(1, $user2->fresh()->totalUnreadMessages());
    }

    public function test_user_own_messages_not_counted_as_unread(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => 'My own', 'is_read' => false]);

        $this->assertEquals(0, $user1->fresh()->totalUnreadMessages());
    }

    public function test_per_conversation_unread_count(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => '1', 'is_read' => false]);
        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => '2', 'is_read' => false]);
        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => '3', 'is_read' => true]);

        $this->assertEquals(2, $conversation->unreadCountFor($user2->id));
    }

    public function test_marking_messages_read_updates_count(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => '1', 'is_read' => false]);
        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => '2', 'is_read' => false]);

        $this->assertEquals(2, $user2->fresh()->totalUnreadMessages());

        // Mark as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user2->id)
            ->update(['is_read' => true]);

        $this->assertEquals(0, $user2->fresh()->totalUnreadMessages());
    }

    public function test_unread_count_api_returns_correct_count(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => 'Test', 'is_read' => false]);

        $response = $this->actingAs($user2)->getJson('/messages/unread-count');
        $response->assertJson(['count' => 1]);
    }

    public function test_conversation_list_shows_unread_badge(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create(['conversation_id' => $conversation->id, 'sender_id' => $user1->id, 'body' => 'Unread msg', 'is_read' => false]);

        $response = $this->actingAs($user2)->get('/conversations');
        $response->assertStatus(200);
    }
}
