# Real-Time Testing Guide

## Test Users for Real-Time Functionalities

Two dedicated users have been created specifically for testing real-time features like messaging, presence indicators, and notifications.

### 🧪 Test User Credentials

| User  | Email                   | Password | Role     | Specialty              |
|-------|-------------------------|----------|----------|------------------------|
| Alice | alice@talentia.local    | password | candidat | Développeur Frontend   |
| Bob   | bob@talentia.local      | password | candidat | Développeur Backend    |

### 🔗 Existing Relationships

- **Alice & Bob**: Friends (accepted) - They have an active conversation
- **Alice & Younes** (test.user@talentia.local): Friends (accepted)
- **Bob & Younes**: Pending friend request (Bob sent request to Younes)

### 💬 Active Conversation

Alice and Bob have an active conversation with **11 recent messages** (from the last 30 minutes). Bob's last 2 messages are **unread** by Alice, which is perfect for testing:
- Real-time message delivery
- Unread message indicators
- Notification badges
- Typing indicators
- Presence status

### 🧪 How to Test Real-Time Features

#### 1. **Real-Time Messaging**
   - Open two browser windows/tabs
   - Login as Alice in one window
   - Login as Bob in another window
   - Navigate to their conversation
   - Send messages and watch them appear in real-time

#### 2. **Presence Indicators**
   - Login as Alice
   - Check if Bob appears online when he logs in
   - Test the "last seen" feature when users go offline

#### 3. **Typing Indicators**
   - While in a conversation, start typing
   - The other user should see a "typing..." indicator

#### 4. **Notifications**
   - Login as Alice
   - Send a message from Bob's account
   - Alice should receive a real-time notification

#### 5. **Unread Message Badges**
   - Login as Alice
   - Check that Bob's 2 unread messages show a badge
   - Mark them as read and verify the badge disappears

#### 6. **Friend Requests**
   - Login as Younes (test.user@talentia.local)
   - Check the pending friend request from Bob
   - Accept/reject it and verify real-time updates

### 📋 Other Test Users

| User   | Email                      | Password | Role      |
|--------|----------------------------|----------|-----------|
| Admin  | admin@talentia.local       | password | recruteur |
| Younes | test.user@talentia.local   | password | candidat  |

### 🚀 Quick Start

0. **Recommended (single command)**:
   ```bash
   composer dev
   ```
   This now starts Laravel server, queue worker, Reverb, logs, and Vite together.

1. **Reset Database** (if needed):
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Start Laravel Reverb** (for WebSocket):
   ```bash
   php artisan reverb:start
   ```

3. **Start Queue Worker** (for notifications):
   ```bash
   php artisan queue:work
   ```

4. **Login and Test**:
   - Open http://talentia.test (or your local URL)
   - Login with Alice or Bob credentials
   - Start testing real-time features!

### 🎯 Test Scenarios

#### Scenario 1: Multi-Tab Messaging
- Open 3 tabs
- Login as Alice in tabs 1 & 2
- Login as Bob in tab 3
- Send messages from Bob
- Verify both Alice tabs receive messages instantly

#### Scenario 2: Notification Flow
- Login as Alice
- Logout or close the conversation
- Send messages from Bob
- Login as Alice again
- Verify notification count and unread badges

#### Scenario 3: Friend Request Real-Time
- Login as Younes
- Login as Bob in another window
- Bob sends a friend request to someone
- Verify Younes sees his pending request to accept/reject

### 📝 Notes

- All test users use the password: **password**
- The conversation between Alice & Bob contains realistic testing messages
- Messages are timestamped from 1-30 minutes ago for realistic testing
- Bob's last 2 messages are intentionally unread for notification testing
