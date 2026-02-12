<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\MessageRead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_user_can_send_text_message(): void
    {
        Event::fake([MessageSent::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $response = $this->actingAs($user1)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Hello, how are you?',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Hello, how are you?',
        ]);
    }

    public function test_sending_message_dispatches_broadcast_event(): void
    {
        Event::fake([MessageSent::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $this->actingAs($user1)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Broadcast test',
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) {
            return $event->message->body === 'Broadcast test';
        });
    }

    public function test_user_cannot_send_message_to_unowned_conversation(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();
        $user3 = User::factory()->create();

        $response = $this->actingAs($user3)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Should not work',
        ]);

        $response->assertStatus(403);
    }

    public function test_message_body_is_required(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $response = $this->actingAs($user1)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_user_can_upload_image_file(): void
    {
        Storage::fake('public');
        Event::fake([MessageSent::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $file = UploadedFile::fake()->image('photo.jpg', 640, 480);

        $response = $this->actingAs($user1)->post('/messages/upload', [
            'conversation_id' => $conversation->id,
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'file_name' => 'photo.jpg',
        ]);
    }

    public function test_user_can_upload_document_file(): void
    {
        Storage::fake('public');
        Event::fake([MessageSent::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $file = UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user1)->post('/messages/upload', [
            'conversation_id' => $conversation->id,
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'file_name' => 'resume.pdf',
        ]);
    }

    public function test_file_upload_rejects_invalid_types(): void
    {
        Storage::fake('public');

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $file = UploadedFile::fake()->create('malware.exe', 500, 'application/x-executable');

        $response = $this->actingAs($user1)->post('/messages/upload', [
            'conversation_id' => $conversation->id,
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_mark_messages_as_read(): void
    {
        Event::fake([MessageRead::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        // User1 sends messages
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Message 1',
            'is_read' => false,
        ]);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Message 2',
            'is_read' => false,
        ]);

        // User2 marks as read
        $response = $this->actingAs($user2)->post('/messages/' . $conversation->id . '/read');
        $response->assertJson(['status' => 'ok', 'updated' => 2]);

        // Check all messages are marked as read
        $this->assertEquals(0, Message::where('conversation_id', $conversation->id)->where('is_read', false)->count());
    }

    public function test_mark_as_read_dispatches_event(): void
    {
        Event::fake([MessageRead::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Unread message',
            'is_read' => false,
        ]);

        $this->actingAs($user2)->post('/messages/' . $conversation->id . '/read');

        Event::assertDispatched(MessageRead::class);
    }

    public function test_unread_count_endpoint(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        // Create unread messages from user1 to user2
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Unread 1',
            'is_read' => false,
        ]);
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'Unread 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user2)->getJson('/messages/unread-count');
        $response->assertJson(['count' => 2]);
    }

    public function test_unread_count_excludes_own_messages(): void
    {
        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        // User1 sends a message (should not count for user1's unread)
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user1->id,
            'body' => 'My own message',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user1)->getJson('/messages/unread-count');
        $response->assertJson(['count' => 0]);
    }

    public function test_sending_message_creates_notification(): void
    {
        Event::fake([MessageSent::class]);

        [$user1, $user2, $conversation] = $this->setupFriendsWithConversation();

        $this->actingAs($user1)->post('/messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Notification test',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user2->id,
            'notifiable_type' => User::class,
        ]);
    }
}
