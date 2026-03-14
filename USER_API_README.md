# User API Documentation

## Overview
Complete CRUD API for User management following Laravel standards with Indonesian error messages.

## Files Created

### 1. Controllers
- **`app/Http/Controllers/User/UserController.php`**
  - `index()` - Get paginated list of users with filtering and search
  - `store()` - Create new user
  - `show($id)` - Get single user details
  - `update($id)` - Update user data
  - `destroy($id)` - Soft delete user
  - `restore($id)` - Restore soft deleted user
  - `forceDelete($id)` - Permanently delete user

### 2. Models
- **`app/Models/User.php`** - Updated with:
  - Additional fillable fields: phone, role_id, status
  - SoftDeletes trait
  - Role relationship
  - Query scopes: active(), inactive(), suspended(), search()

- **`app/Models/Role.php`** - New model for user roles

### 3. Requests
- **`app/Http/Requests/UserStoreRequest.php`** - Validation for creating users
- **`app/Http/Requests/UserUpdateRequest.php`** - Validation for updating users
- **`app/Http/Requests/UserListRequest.php`** - Validation for listing users with filters

### 4. Resources
- **`app/Http/Resources/UserResource.php`** - Single user resource transformer
- **`app/Http/Resources/UserCollection.php`** - Paginated user collection transformer

### 5. Helpers
- **`app/Helper/UserHelper.php`** - Helper functions for API responses:
  - `successResponse()` - Standard success response
  - `errorResponse()` - Standard error response
  - `validationErrorResponse()` - Validation error response
  - `notFoundResponse()` - 404 response
  - `unauthorizedResponse()` - 401 response
  - `forbiddenResponse()` - 403 response
  - `serverErrorResponse()` - 500 response

### 6. Migrations
- **`database/migrations/2024_03_14_000001_update_users_table_add_master_fields.php`**
  - Adds: phone, role_id, status columns to users table
  - Creates: roles table
  - Enables: soft deletes

### 7. Seeders
- **`database/seeders/RoleSeeder.php`** - Default roles:
  - Super Admin
  - Admin
  - Manager
  - Staff
  - Kasir

### 8. Routes
- **`routes/api.php`** - API endpoints:
  ```
  GET    /api/users              - List users
  POST   /api/users              - Create user
  GET    /api/users/{id}         - Get user details
  PUT    /api/users/{id}         - Update user
  PATCH  /api/users/{id}         - Update user
  DELETE /api/users/{id}         - Delete user (soft)
  POST   /api/users/{id}/restore - Restore user
  DELETE /api/users/{id}/force   - Force delete user
  ```

## Installation

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Run Seeders (Optional)
```bash
php artisan db:seed --class=RoleSeeder
```

### 3. Clear Cache
```bash
php artisan route:clear
php artisan config:clear
```

## API Usage Examples

### Get Users List
```bash
GET /api/users?per_page=15&page=1&status=active&role_id=1&search=john&sort_by=name&sort_direction=asc
```

**Response:**
```json
{
  "success": true,
  "message": "Berhasil mengambil data users",
  "data": {
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "08123456789",
        "email_verified_at": null,
        "status": "active",
        "role": {
          "id": 1,
          "name": "Admin"
        },
        "created_at": "2024-03-14 10:00:00",
        "updated_at": "2024-03-14 10:00:00"
      }
    ],
    "meta": {
      "total": 100,
      "per_page": 15,
      "current_page": 1,
      "last_page": 7,
      "from": 1,
      "to": 15
    }
  }
}
```

### Create User
```bash
POST /api/users
Content-Type: application/json

{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "08987654321",
  "role_id": 2,
  "status": "active"
}
```

### Update User
```bash
PUT /api/users/1
Content-Type: application/json

{
  "name": "John Updated",
  "email": "john.updated@example.com",
  "phone": "08123456789",
  "role_id": 1,
  "status": "active"
}
```

### Delete User
```bash
DELETE /api/users/1
```

### Restore User
```bash
POST /api/users/1/restore
```

### Force Delete User
```bash
DELETE /api/users/1/force
```

## Validation Rules

### UserStoreRequest
- `name`: required, string, max:255
- `email`: required, string, email, max:255, unique
- `password`: required, confirmed
- `phone`: nullable, string, max:20
- `role_id`: nullable, exists:roles,id
- `status`: nullable, in:active,inactive,suspended

### UserUpdateRequest
- `name`: sometimes, required, string, max:255
- `email`: sometimes, required, string, email, max:255, unique (except current)
- `password`: nullable, confirmed
- `phone`: nullable, string, max:20
- `role_id`: nullable, exists:roles,id
- `status`: nullable, in:active,inactive,suspended

### UserListRequest
- `search`: nullable, string, max:255 (searches name, email, phone)
- `status`: nullable, in:active,inactive,suspended
- `role_id`: nullable, exists:roles,id
- `per_page`: nullable, integer, min:1, max:100 (default: 15)
- `page`: nullable, integer, min:1 (default: 1)
- `sort_by`: nullable, in:id,name,email,created_at,updated_at (default: created_at)
- `sort_direction`: nullable, in:asc,desc (default: desc)

## Error Responses

All errors follow this format:
```json
{
  "success": false,
  "message": "Error message in Indonesian",
  "errors": {
    "field": ["error message"]
  }
}
```

## Features

✅ Complete CRUD operations
✅ Soft delete support
✅ Restore deleted users
✅ Force delete
✅ Pagination
✅ Search functionality (name, email, phone)
✅ Filtering (status, role)
✅ Sorting
✅ Relationships (user -> role)
✅ Indonesian error messages
✅ Standard JSON response format
✅ Validation
✅ Error handling with logging
