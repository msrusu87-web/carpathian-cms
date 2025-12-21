# Client Portal System Documentation

## Overview
Complete client portal system with authentication, order tracking, and real-time support chat.

## Features Implemented

### 1. Authentication System
- **Login Page**: `/login`
- **Registration Page**: `/register`
- New users automatically receive the **Client** role upon registration
- Clients are redirected to `/client/dashboard` after login

### 2. User Roles & Permissions
- **Client Role** with permissions:
  - `view orders`
  - `view own orders`
  - `create support ticket`
  - `view support chat`

### 3. Client Dashboard (`/client/dashboard`)
- Overview statistics (total orders, completed orders, support tickets)
- Recent orders list
- Quick access links to Orders and Support

### 4. Orders Management
- **Orders List**: `/client/orders` - View all customer orders with status badges
- **Order Detail**: `/client/orders/{id}` - Detailed view with:
  - Order items with quantities and prices
  - Price breakdown (subtotal, tax, shipping, total)
  - Order status timeline
  - Payment information
  - Shipping address

### 5. Support Chat System (`/client/support`)
- List of support conversations
- Create new support conversations
- Real-time messaging with admin (polling every 3 seconds)
- Message read status tracking

### 6. Admin Features

#### Navigation Updates
- **View Website** link in Filament admin sidebar (opens in new tab)
- **Support Chat** link with unread message badge

#### Admin Chat Interface (`/admin-chat`)
- View all support conversations (from clients and guests)
- Statistics: Open, Pending, Closed conversations
- Conversation types: Support (logged users) vs Guest (homepage visitors)
- Real-time chat response interface
- Close conversation functionality

### 7. Homepage Chat Widget
- Floating chat button on homepage (bottom-right)
- For non-logged visitors:
  - Required: Name, Email, Message
  - Optional: Phone number
- Persists conversation via localStorage
- Real-time updates (polling every 3 seconds)
- Styled to match site branding

## File Structure

### Controllers
- `app/Http/Controllers/ClientController.php` - Client dashboard, orders, support
- `app/Http/Controllers/PublicChatController.php` - Homepage chat widget
- `app/Http/Controllers/Admin/ChatController.php` - Admin chat management

### Models
- `app/Models/ChatConversation.php` - Conversation model with relationships
- `app/Models/ChatMessage.php` - Message model with sender tracking

### Views
- `resources/views/client/layout.blade.php` - Client area layout with navigation
- `resources/views/client/dashboard.blade.php` - Dashboard view
- `resources/views/client/orders.blade.php` - Orders list
- `resources/views/client/order-detail.blade.php` - Order detail
- `resources/views/client/support.blade.php` - Support conversations list
- `resources/views/client/chat.blade.php` - Real-time chat view
- `resources/views/admin/chat/index.blade.php` - Admin conversations list
- `resources/views/admin/chat/show.blade.php` - Admin chat response view
- `resources/views/components/chat-widget.blade.php` - Homepage floating chat

### Database Tables
- `chat_conversations` - Stores conversation metadata
- `chat_messages` - Stores individual messages

## Routes

### Client Routes (requires authentication)
```
GET  /client/dashboard       - Dashboard
GET  /client/orders          - Orders list
GET  /client/orders/{id}     - Order detail
GET  /client/support         - Support conversations
GET  /client/chat/{id?}      - Chat view
POST /client/chat/new        - Create new conversation
POST /client/chat/{id}/send  - Send message
GET  /client/chat/{id}/messages - Get messages (AJAX)
```

### Admin Chat Routes (requires admin auth)
```
GET  /admin-chat             - Conversations list
GET  /admin-chat/{id}        - Chat view
POST /admin-chat/{id}/send   - Send message
POST /admin-chat/{id}/close  - Close conversation
GET  /admin-chat/{id}/messages - Get messages (AJAX)
GET  /admin-chat/api/unread-count - Get unread count
```

### Public Chat Routes (no auth required)
```
POST /chat/start     - Start new conversation
GET  /chat/messages  - Get messages
POST /chat/messages  - Send message
GET  /chat/check     - Check conversation status
```

## Usage

### For Clients
1. Register at `/register` or login at `/login`
2. Access dashboard at `/client/dashboard`
3. View orders at `/client/orders`
4. Get support at `/client/support`

### For Admins
1. Login to Filament admin panel at `/admin`
2. Click "Support Chat" in sidebar to manage conversations
3. Click "View Website" to open the main site

### For Homepage Visitors
1. Click the chat bubble (bottom-right corner)
2. Fill in Name, Email, and Message
3. Start chatting with support team

## Technical Notes

- Real-time updates via JavaScript polling (3 second intervals)
- Alpine.js for frontend interactivity
- TailwindCSS for styling
- Conversation persistence via localStorage (for guests)
- CSRF protection on all POST requests

## Security
- Routes protected by auth middleware
- Client role restrictions
- CSRF tokens for all forms
- Input validation on all endpoints

---
*Last Updated: December 2024*
