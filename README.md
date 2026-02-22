# Post Approval System

A clean Laravel 12 REST API for role-based post submission and approval. Authors submit posts, Managers and Admins review them, and every action is logged immutably in the database.

## 🚀 Features

- **Secure Authentication**: Integrated via Laravel Sanctum with custom UI
- **Role-Based Access Control**: Three user roles (Author, Manager, Admin)
- **Post Management**: Full CRUD operations with approval workflow
- **Activity Logging**: Immutable append-only audit log for all actions
- **Modern UI/UX**: Bootstrap 5, jQuery validation, SweetAlert2 popups
- **RESTful API**: Clean API endpoints for external integrations

## 🛠️ Setup Instructions

### 1. Prerequisites

- PHP 8.2+
- Composer
- MySQL
- Node.js (optional for assets)

### 2. Installation

```
bash
# Clone or navigate to project
cd post_approval_system

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env
```

### 3. Configuration

Update `.env` with database credentials:

CREATE DATABASE post_approval_system;
```
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=post_approval_system
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key:

```
bash
php artisan key:generate
```

### 4. Database Setup

Run migrations:

```
bash
php artisan migrate
```

Seed demo users:

```
bash
php artisan db:seed
```

### 5. Finalize Setup

```
bash
# Start the server
php artisan serve
```

Access the application at: http://127.0.0.1:8000

## 📖 Default Test Credentials

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| manager@example.com | password | Manager |
| author@example.com | password | Author |

## 🗄️ Database Schema

### users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | Display name |
| email | string | Unique |
| password | string | Bcrypt hashed |
| role | enum | 'author', 'manager', 'admin' |

### posts
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| title | string | Max 255 chars |
| body | longText | Post content |
| status | enum | 'pending', 'approved', 'rejected' |
| author_id | bigint | FK to users |
| approved_by | bigint | FK to users (nullable) |
| rejected_reason | text | Set on rejection |
| deleted_at | timestamp | Soft delete |

### post_logs
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| post_id | bigint | FK to posts |
| user_id | bigint | FK to users |
| action | enum | 'created', 'approved', 'rejected', 'deleted' |
| meta | json | Additional data |
| created_at | timestamp | No updated_at |

## 📡 API Documentation

All API endpoints require Bearer token authentication.

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/auth/register | Register & get token |
| POST | /api/auth/login | Login & get token |
| POST | /api/auth/logout | Revoke token |

### Posts

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | /api/posts | List all posts | All roles |
| POST | /api/posts | Create new post | All roles |
| GET | /api/posts/{id} | Get single post | All roles |
| POST | /api/posts/{id}/approve | Approve post | Manager, Admin |
| POST | /api/posts/{id}/reject | Reject with reason | Manager, Admin |
| DELETE | /api/posts/{id} | Delete post | Admin only |
| GET | /api/posts/{id}/logs | Get post activity logs | All roles |

## 👤 User Roles

| Role | Permissions |
|------|-------------|
| Author | Create posts, edit own pending posts, view own posts |
| Manager | View all posts, approve, reject (with reason) |
| Admin | All Manager permissions + delete any post |

## 🧪 Tech Stack

- **Backend**: Laravel 12 (Eloquent, API Resources, Service Layer)
- **Frontend**: Bootstrap 5, jQuery, SweetAlert2
- **Authentication**: Laravel Sanctum
- **Database**: MySQL

## License

MIT License
