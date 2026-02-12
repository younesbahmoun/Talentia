<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_belongs_to_conversation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);
        $msg = Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Hello']);

        $this->assertTrue($msg->conversation->is($conv));
    }

    public function test_message_belongs_to_sender(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);
        $msg = Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Hello']);

        $this->assertTrue($msg->sender->is($user1));
    }

    public function test_has_file_returns_true_when_file_path_exists(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);
        $msg = Message::create([
            'conversation_id' => $conv->id,
            'sender_id' => $user1->id,
            'body' => null,
            'file_path' => 'chat-files/photo.jpg',
            'file_name' => 'photo.jpg',
            'file_type' => 'image/jpeg',
        ]);

        $this->assertTrue($msg->hasFile());
    }

    public function test_has_file_returns_false_when_no_file(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);
        $msg = Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'No file']);

        $this->assertFalse($msg->hasFile());
    }

    public function test_is_image_returns_true_for_image_types(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        foreach ($imageTypes as $type) {
            $msg = Message::create([
                'conversation_id' => $conv->id,
                'sender_id' => $user1->id,
                'file_path' => 'test.jpg',
                'file_type' => $type,
            ]);
            $this->assertTrue($msg->isImage(), "Expected $type to be identified as image");
        }
    }

    public function test_is_image_returns_false_for_non_image_types(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);

        $msg = Message::create([
            'conversation_id' => $conv->id,
            'sender_id' => $user1->id,
            'file_path' => 'resume.pdf',
            'file_type' => 'application/pdf',
        ]);

        $this->assertFalse($msg->isImage());
    }

    public function test_message_defaults_unread(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conv = Conversation::create(['user_one_id' => $user1->id, 'user_two_id' => $user2->id]);
        $msg = Message::create(['conversation_id' => $conv->id, 'sender_id' => $user1->id, 'body' => 'Test']);

        $this->assertFalse($msg->fresh()->is_read);
    }
}
