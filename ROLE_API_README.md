# Role API Documentation

## Overview
Complete CRUD API for Role management following Laravel standards with Indonesian error messages. Single Request file handles both create and update operations.

## Files Created

### 1. Controllers
- **`app/Http/Controllers/Master/RoleController.php`**
  - `index()` - Get paginated list of roles with search
  - `store()` - Create new role
  - `show($id)` - Get single role details with users
  - `update($id)` - Update role data
  - `destroy($id)` - Delete role (with validation for existing users)

### 2. Models
- **`app/Models/Role.php`** - Updated with:
  - Fillable fields: name, description
  - Users relationship
  - Query scope: search()

### 3. Requests (Single File)
- **`app/Http/Requests/Master/RoleRequest.php`** - Single request class for:
  - **Create (POST)** - Validates: name (required, unique), description (nullable)
  - **Update (PUT/PATCH)** - Validates: name (sometimes, unique except current), description (nullable)

### 4. Resources
- **`app/Http/Resources/Master/RoleResource.php`** - Single role resource transformer
- **`app/Http/Resources/Master/RoleCollection.php`** - Paginated role collection transformer

### 5. Routes
- **`routes/api.php`** - API endpoints:
  ```
  GET    /api/roles        - List roles
  POST   /api/roles        - Create role
  GET    /api/roles/{id}   - Get role details
  PUT    /api/roles/{id}   - Update role
  PATCH  /api/roles/{id}   - Update role
  DELETE /api/roles/{id}   - Delete role
  ```

## API Usage Examples

### Get Roles List
```bash
GET /api/roles?per_page=15&page=1&search=admin&sort_by=name&sort_direction=asc
```

**Response:**
```json
{
  "success": true,
  "message": "Berhasil mengambil data roles",
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Super Admin",
        "description": "Memiliki akses penuh ke semua fitur",
        "users_count": 2,
        "users": [
          {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "status": "active"
          },
          {
            "id": 2,
            "name": "Jane Smith",
            "email": "jane@example.com",
            "status": "active"
          }
        ],
        "created_at": "2024-03-14 10:00:00",
        "updated_at": "2024-03-14 10:00:00"
      }
    ],
    "meta": {
      "total": 5,
      "per_page": 15,
      "current_page": 1,
      "last_page": 1,
      "from": 1,
      "to": 5
    }
  }
}
```

### Create Role
```bash
POST /api/roles
Content-Type: application/json

{
  "name": "Finance Manager",
  "description": "Memiliki akses ke fitur keuangan dan laporan"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Berhasil menambahkan role baru",
  "data": {
    "id": 6,
    "name": "Finance Manager",
    "description": "Memiliki akses ke fitur keuangan dan laporan",
    "users_count": 0,
    "users": [],
    "created_at": "2024-03-14 12:00:00",
    "updated_at": "2024-03-14 12:00:00"
  }
}
```

### Update Role (Full Update - PUT)
```bash
PUT /api/roles/1
Content-Type: application/json

{
  "name": "Administrator",
  "description": "Memiliki akses penuh ke semua fitur sistem"
}
```

### Update Role (Partial Update - PATCH)
```bash
PATCH /api/roles/1
Content-Type: application/json

{
  "description": "Updated description for admin role"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Berhasil mengupdate data role",
  "data": {
    "id": 1,
    "name": "Administrator",
    "description": "Updated description for admin role",
    "users_count": 2,
    "users": [...],
    "created_at": "2024-03-14 10:00:00",
    "updated_at": "2024-03-14 12:30:00"
  }
}
```

### Delete Role
```bash
DELETE /api/roles/1
```

**Success Response:**
```json
{
  "success": true,
  "message": "Berhasil menghapus role"
}
```

**Error Response (Role has users):**
```json
{
  "success": false,
  "message": "Role tidak dapat dihapus karena masih digunakan oleh user",
  "errors": []
}
```

## Validation Rules

### RoleRequest (Create & Update)

#### Create (POST) - All fields required
- `name`: required, string, max:255, unique
- `description`: nullable, string, max:1000

#### Update (PUT/PATCH) - All fields optional
- `name`: sometimes, required, string, max:255, unique (except current role)
- `description`: nullable, string, max:1000

### List Parameters
- `search`: nullable, string, max:255 (searches name and description)
- `per_page`: nullable, integer, min:1, max:100 (default: 15)
- `page`: nullable, integer, min:1 (default: 1)
- `sort_by`: nullable, in:id,name,description,created_at,updated_at (default: created_at)
- `sort_direction`: nullable, in:asc,desc (default: desc)

## Error Responses

### Validation Error
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "name": [
      "Nama role wajib diisi"
    ]
  }
}
```

### Not Found
```json
{
  "success": false,
  "message": "Role tidak ditemukan",
  "errors": []
}
```

### Business Logic Error
```json
{
  "success": false,
  "message": "Role tidak dapat dihapus karena masih digunakan oleh user",
  "errors": []
}
```

### Server Error
```json
{
  "success": false,
  "message": "Terjadi kesalahan saat menghapus role",
  "errors": []
}
```

## Custom Messages (Indonesian)

- `name.required`: "Nama role wajib diisi"
- `name.max`: "Nama role tidak boleh lebih dari 255 karakter"
- `name.unique`: "Nama role sudah digunakan"
- `description.max`: "Deskripsi tidak boleh lebih dari 1000 karakter"

## Features

✅ Complete CRUD operations
✅ Single Request file for both create and update
✅ Paginated listing with search and sorting
✅ Load relationship (users) on demand
✅ Users count in resource
✅ Protection against deleting roles with users
✅ Indonesian error messages
✅ Standard JSON response format
✅ Validation
✅ Error handling with logging

## Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Master/
│   │       └── RoleController.php
│   ├── Requests/
│   │   └── Master/
│   │       └── RoleRequest.php (Single file for Create & Update)
│   └── Resources/
│       └── Master/
│           ├── RoleResource.php
│           └── RoleCollection.php
├── Models/
│   └── Role.php
routes/
└── api.php
```

## Notes

1. **Single Request File**: `RoleRequest` handles both create and update operations:
   - For **create**: All fields are required (name, description optional)
   - For **update**: All fields are optional using `sometimes` rule
   - Unique validation for `name` excludes current role on update

2. **Delete Protection**: Roles cannot be deleted if they have users assigned

3. **Relationship Loading**: Users are loaded when explicitly requested (e.g., `show()` method)

4. **Search**: Searches both `name` and `description` fields

5. **Status Codes**:
   - 200: Success (update, delete)
   - 201: Created (store)
   - 400: Validation error or business logic error
   - 404: Not found
   - 500: Server error
