<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversation_belongs_to_user_one(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $this->assertTrue($conv->userOne->is($user1));
    }

    public function test_conversation_belongs_to_user_two(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $this->assertTrue($conv->userTwo->is($user2));
    }

    public function test_conversation_has_messages(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Hello']);
        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user2->id, 'body' => 'Hi!']);

        $this->assertEquals(2, $conv->messages()->count());
    }

    public function test_conversation_latest_message(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'First']);
        sleep(1);
        $latest = Message::create(['conversation_id' => $conv->id, 'sender_id' => $user2->id, 'body' => 'Second']);

        $this->assertEquals($latest->id, $conv->latestMessage->id);
    }

    public function test_between_creates_conversation_if_not_exists(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->assertEquals(0, Conversation::count());

        $conv = Conversation::between($user1->id, $user2->id);

        $this->assertEquals(1, Conversation::count());
        $this->assertNotNull($conv);
    }

    public function test_between_returns_existing_conversation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conv1 = Conversation::between($user1->id, $user2->id);
        $conv2 = Conversation::between($user2->id, $user1->id);

        $this->assertEquals(1, Conversation::count());
        $this->assertEquals($conv1->id, $conv2->id);
    }

    public function test_has_participant(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $this->assertTrue($conv->hasParticipant($user1->id));
        $this->assertTrue($conv->hasParticipant($user2->id));
        $this->assertFalse($conv->hasParticipant($user3->id));
    }

    public function test_get_other_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $this->assertTrue($conv->getOtherUser($user1->id)->is($user2));
        $this->assertTrue($conv->getOtherUser($user2->id)->is($user1));
    }

    public function test_unread_count_for(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Unread 1', 'is_read' => false]);
        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Unread 2', 'is_read' => false]);
        Message::create(['conversation_id' => $conv->id, 'sender_id' => $user2->id, 'body' => 'Read', 'is_read' => true]);

        // User2 should see 2 unread messages from user1
        $this->assertEquals(2, $conv->unreadCountFor($user2->id));
        // User1 should see 0 unread (own messages don't count)
        $this->assertEquals(0, $conv->unreadCountFor($user1->id));
    }
}
