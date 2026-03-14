# Master API Folder Structure

## Overview
Complete CRUD APIs for User and Role management with organized folder structure by feature (direct menu name).

## 📁 Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Role/                    ✅ Folder per menu/feature
│   │   │   └── RoleController.php
│   │   └── User/                    ✅ Folder per menu/feature
│   │       └── UserController.php
│   ├── Requests/
│   │   ├── Role/                    ✅ Folder per menu/feature
│   │   │   └── RoleRequest.php
│   │   └── User/                    ✅ Folder per menu/feature
│   │       ├── UserListRequest.php
│   │       ├── UserStoreRequest.php
│   │       └── UserUpdateRequest.php
│   └── Resources/
│       ├── Role/                    ✅ Folder per menu/feature
│       │   ├── RoleCollection.php
│       │   └── RoleResource.php
│       └── User/                    ✅ Folder per menu/feature
│           ├── UserCollection.php
│           └── UserResource.php
├── Helper/
│   └── UserHelper.php
└── Models/
    ├── User.php
    └── Role.php
```

## 🎯 Pattern Explanation

### Structure
```
Controllers/
├── Role/              ← Folder per menu/feature (no Master folder)
│   └── RoleController.php
└── User/              ← Folder per menu/feature
    └── UserController.php

Requests/
├── Role/              ← Folder per menu/feature (no Master folder)
│   └── RoleRequest.php
└── User/              ← Folder per menu/feature
    ├── UserListRequest.php
    ├── UserStoreRequest.php
    └── UserUpdateRequest.php

Resources/
├── Role/              ← Folder per menu/feature (no Master folder)
│   ├── RoleCollection.php
│   └── RoleResource.php
└── User/              ← Folder per menu/feature
    ├── UserCollection.php
    └── UserResource.php
```

## 🔑 Key Benefits

1. **Direct Menu Structure** - Each menu item has its own folder
2. **Easier Navigation** - Find files by menu/feature name directly
3. **Scalability** - Easy to add new features
4. **Consistency** - Same pattern for all features
5. **No Master Folder** - Direct access to feature folders

## 📝 Namespace Examples

### Role API
- Controller: `App\Http\Controllers\Role\RoleController`
- Request: `App\Http\Requests\Role\RoleRequest`
- Resource: `App\Http\Resources\Role\RoleResource`
- Collection: `App\Http\Resources\Role\RoleCollection`

### User API
- Controller: `App\Http\Controllers\User\UserController`
- Request: `App\Http\Requests\User\UserStoreRequest`
- Resource: `App\Http\Resources\User\UserResource`
- Collection: `App\Http\Resources\User\UserCollection`

## 🚀 Adding New Features

To add a new feature (e.g., "Shift"):

```bash
# Create folders
mkdir -p app/Http/Controllers/Shift
mkdir -p app/Http/Requests/Shift
mkdir -p app/Http/Resources/Shift

# Create files
touch app/Http/Controllers/Shift/ShiftController.php
touch app/Http/Requests/Shift/ShiftRequest.php
touch app/Http/Resources/Shift/ShiftResource.php
touch app/Http/Resources/Shift/ShiftCollection.php
```

## 📋 File Naming Convention

### Controllers
- Pattern: `{FeatureName}Controller.php`
- Namespace: `App\Http\Controllers\{FeatureName}`

### Requests
- Pattern: `{FeatureName}Request.php` (single file for CRUD)
  or `{FeatureName}{Action}Request.php` (separate files)
- Namespace: `App\Http\Requests\{FeatureName}`

### Resources
- Pattern: `{FeatureName}Resource.php` and `{FeatureName}Collection.php`
- Namespace: `App\Http\Resources\{FeatureName}`

## 🔄 Migration Guide

When adding new APIs or refactoring existing ones, follow this pattern:

1. Create feature folder directly in Controllers/Requests/Resources
2. Use proper namespaces based on folder structure
3. Follow naming conventions
4. Update routes with correct controller class
5. Clear route cache: `php artisan route:clear`

## 📄 Related Documentation

- `USER_API_README.md` - User API documentation
- `ROLE_API_README.md` - Role API documentation
- `MASTER_API_DOCUMENTATION.md` - Overall API documentation
