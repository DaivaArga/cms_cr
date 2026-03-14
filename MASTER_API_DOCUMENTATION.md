# Master API Documentation

## Overview
Complete CRUD APIs for User and Role management following Laravel standards with Indonesian error messages.

## 📁 Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Master/
│   │   │   └── RoleController.php
│   │   └── User/
│   │       └── UserController.php
│   ├── Requests/
│   │   ├── Master/
│   │   │   └── RoleRequest.php (Single file for Create & Update)
│   │   ├── UserStoreRequest.php
│   │   ├── UserUpdateRequest.php
│   │   └── UserListRequest.php
│   └── Resources/
│       ├── Master/
│       │   ├── RoleResource.php
│       │   └── RoleCollection.php
│       ├── UserResource.php
│       └── UserCollection.php
├── Helper/
│   └── UserHelper.php
└── Models/
    ├── User.php
    └── Role.php
```

## 🚀 API Endpoints Summary

### User API
```
GET    /api/users              - List users (with filters & search)
POST   /api/users              - Create user
GET    /api/users/{id}         - Get user details
PUT    /api/users/{id}         - Update user
PATCH  /api/users/{id}         - Update user (partial)
DELETE /api/users/{id}         - Soft delete user
POST   /api/users/{id}/restore - Restore deleted user
DELETE /api/users/{id}/force   - Force delete user
```

### Role API
```
GET    /api/roles        - List roles (with search)
POST   /api/roles        - Create role
GET    /api/roles/{id}   - Get role details
PUT    /api/roles/{id}   - Update role
PATCH  /api/roles/{id}   - Update role (partial)
DELETE /api/roles/{id}   - Delete role
```

## 📊 Comparison: User vs Role API

| Feature | User API | Role API |
|---------|----------|----------|
| **Controller** | `User\UserController` | `Master\RoleController` |
| **Request Files** | 3 separate files (Store, Update, List) | 1 single file (for Create & Update) |
| **Resource Folder** | Root Resources folder | `Master` subfolder |
| **Request Folder** | Root Requests folder | `Master` subfolder |
| **Soft Delete** | ✅ Yes | ❌ No |
| **Restore** | ✅ Yes | ❌ No |
| **Force Delete** | ✅ Yes | ❌ No |
| **Search Fields** | name, email, phone | name, description |
| **Filters** | status, role_id | none |
| **Relationship** | role (BelongsTo) | users (HasMany) |

## 🔥 Key Features

### User API
- ✅ Complete CRUD operations
- ✅ Soft delete with restore
- ✅ Force delete capability
- ✅ Advanced filtering (status, role_id)
- ✅ Search across multiple fields
- ✅ Pagination with sorting
- ✅ Load relationships on demand

### Role API
- ✅ Complete CRUD operations
- ✅ Single Request file (handles create & update)
- ✅ Protection against deleting roles with users
- ✅ Search functionality
- ✅ Users count in response
- ✅ Pagination with sorting

## 📝 Installation

```bash
# Run migrations
php artisan migrate

# Run seeders for default roles
php artisan db:seed --class=RoleSeeder

# Clear cache
php artisan route:clear
php artisan config:clear
```

## 🎯 Pattern Reference

### Pattern 1: User API (Separate Request Files)
```
Requests/
├── UserStoreRequest.php   (for POST)
├── UserUpdateRequest.php  (for PUT/PATCH)
└── UserListRequest.php   (for GET with filters)
```

### Pattern 2: Role API (Single Request File)
```
Requests/Master/
└── RoleRequest.php        (handles both POST, PUT, PATCH)
```

**When to use each pattern:**

- **Use Separate Files** when:
  - You have complex validation rules
  - Create and update have different requirements
  - You need different field sets for each operation
  - List operation needs its own validation parameters

- **Use Single File** when:
  - Validation rules are similar for create and update
  - Only unique validation differs (excludes current ID on update)
  - Simpler operations with fewer fields
  - Want to reduce file count

## 📄 Documentation

- **User API**: See `USER_API_README.md` for detailed documentation
- **Role API**: See `ROLE_API_README.md` for detailed documentation

## 🌐 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success message in Indonesian",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message in Indonesian",
  "errors": {
    "field": ["error message"]
  }
}
```

## 🔒 Status Codes

- `200`: Success (GET, PUT, PATCH, DELETE)
- `201`: Created (POST)
- `400`: Bad Request (validation error, business logic error)
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `500`: Internal Server Error

## 🛠️ Helper Functions (UserHelper)

Available in `app/Helper/UserHelper.php`:

```php
UserHelper::successResponse($message, $data, $statusCode = 200)
UserHelper::errorResponse($message, $statusCode = 400, $errors = [])
UserHelper::validationErrorResponse($errors)
UserHelper::notFoundResponse($message = 'Data tidak ditemukan')
UserHelper::unauthorizedResponse($message = 'Unauthorized')
UserHelper::forbiddenResponse($message = 'Forbidden')
UserHelper::serverErrorResponse($message = 'Terjadi kesalahan pada server')
```

## 💡 Tips for Adding New Master APIs

1. **Follow the folder structure pattern**:
   - Controller: `app/Http/Controllers/Master/{Name}Controller.php`
   - Request: `app/Http/Requests/Master/{Name}Request.php`
   - Resource: `app/Http/Resources/Master/{Name}Resource.php`
   - Collection: `app/Http/Resources/Master/{Name}Collection.php`

2. **Choose Request pattern**:
   - Use separate files if validation differs significantly
   - Use single file if validation is similar (like Role API)

3. **Use UserHelper** for consistent responses

4. **Add routes** to `routes/api.php` with proper prefix

5. **Update models** with relationships and query scopes

6. **Handle errors** with try-catch and proper logging

## 📞 Support

For questions or issues, refer to the individual documentation files:
- `USER_API_README.md` - Complete User API documentation
- `ROLE_API_README.md` - Complete Role API documentation
