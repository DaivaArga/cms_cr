# Role Migration Update

## Overview
Updated the roles table structure to match Indonesian field names with additional fields for better role management.

## 📊 Database Schema

### Roles Table

| Column | Type | Constraints | Description |
|--------|-------|-------------|-------------|
| id | bigInteger | Primary, Auto Increment | ID role |
| nama | string(255) | Unique | Nama role |
| alias | string(255) | Nullable | Alias untuk role |
| hak_akses | text | Nullable | Daftar hak akses role |
| created_at | timestamp | - | Waktu dibuat |
| updated_at | timestamp | - | Waktu diupdate |
| deleted_at | timestamp | Nullable | Soft delete timestamp |

## 🔄 Changes Made

### 1. Migration File
**File**: `database/migrations/2024_03_14_000001_update_users_table_add_master_fields.php`

**Changes**:
- ✅ Updated `roles` table structure
- ✅ Changed `name` → `nama`
- ✅ Removed `description`
- ✅ Added `alias` (nullable)
- ✅ Added `hak_akses` (nullable, text)
- ✅ Added `softDeletes()` for `deleted_at`

### 2. Model Updates
**File**: `app/Models/Role.php`

**Changes**:
- ✅ Added `SoftDeletes` trait
- ✅ Updated `$fillable`: `['nama', 'alias', 'hak_akses']`
- ✅ Updated `scopeSearch()` to search in `nama`, `alias`, `hak_akses`

### 3. Request Updates
**File**: `app/Http/Requests/Role/RoleRequest.php`

**Changes**:
- ✅ Validation rules: `nama` (required), `alias` (nullable), `hak_akses` (nullable)
- ✅ Updated messages to use Indonesian
- ✅ Added GET request validation (sort, pagination)
- ✅ Unique validation for `nama` field

### 4. Resource Updates
**File**: `app/Http/Resources/Role/RoleResource.php`

**Changes**:
- ✅ Return fields: `nama`, `alias`, `hak_akses`
- ✅ Added `deleted_at` to response
- ✅ Updated role name in users list

### 5. Controller Updates
**File**: `app/Http/Controllers/Role/RoleController.php`

**Changes**:
- ✅ Added sort mapping for `nama` field
- ✅ Supports sorting by: `nama`, `created_at`, `updated_at`

### 6. User Resource Updates
**File**: `app/Http/Resources/User/UserResource.php`

**Changes**:
- ✅ Updated role relationship to return `nama` and `alias` instead of `name`

### 7. Seeder Updates
**File**: `database/seeders/RoleSeeder.php`

**Default Roles**:
- ✅ **Super Admin** - Alias: `superadmin`, Akses: `all`
- ✅ **Admin** - Alias: `admin`, Akses: `users,roles,shifts,kode_akses,struk`
- ✅ **Manager** - Alias: `manager`, Akses: `laporan,kelola_produk`
- ✅ **Staff** - Alias: `staff`, Akses: `laporan`
- ✅ **Kasir** - Alias: `kasir`, Akses: `transaksi`

## 🚀 Running Migration

```bash
# Rollback if migration already ran
php artisan migrate:rollback

# Run migration
php artisan migrate

# Run seeder for default roles
php artisan db:seed --class=RoleSeeder

# Clear cache
php artisan route:clear
php artisan config:clear
```

## 📝 API Usage Examples

### Create Role
```bash
POST /api/roles
Content-Type: application/json

{
  "nama": "Finance Manager",
  "alias": "finance",
  "hak_akses": "laporan_keuangan,laporan_penjualan,laporan_pengeluaran"
}
```

### Update Role
```bash
PUT /api/roles/1
Content-Type: application/json

{
  "nama": "Finance Manager Updated",
  "alias": "finance",
  "hak_akses": "laporan_keuangan,laporan_penjualan"
}
```

### List Roles with Sort
```bash
GET /api/roles?sort_by=nama&sort_direction=asc&per_page=10
```

### Search Roles
```bash
GET /api/roles?search=admin
```
# Searches in: nama, alias, hak_akses

## 📋 Validation Rules

### Store (POST)
- `nama`: required, string, max:255, unique
- `alias`: nullable, string, max:255
- `hak_akses`: nullable, string

### Update (PUT/PATCH)
- `nama`: sometimes, required, string, max:255, unique (except current)
- `alias`: nullable, string, max:255
- `hak_akses`: nullable, string

### List (GET)
- `search`: nullable, string, max:255
- `sort_by`: nullable, in:nama,name,created_at,updated_at
- `sort_direction`: nullable, in:asc,desc
- `per_page`: nullable, integer, min:1, max:100
- `page`: nullable, integer, min:1

## 📄 Response Format

```json
{
  "success": true,
  "message": "Berhasil mengambil data roles",
  "data": {
    "data": [
      {
        "id": 1,
        "nama": "Super Admin",
        "alias": "superadmin",
        "hak_akses": "all",
        "users_count": 2,
        "users": [...],
        "created_at": "2024-03-14 10:00:00",
        "updated_at": "2024-03-14 10:00:00",
        "deleted_at": null
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

## ⚠️ Notes

1. **Field Names**: All field names in database use Indonesian (`nama` instead of `name`)
2. **Soft Delete**: Roles support soft delete - use `deleted_at` instead of permanent delete
3. **Hak Akses**: Stored as comma-separated string or JSON string
4. **Alias**: Optional field for code-friendly role identification
5. **Sort Compatibility**: API accepts both `nama` and `name` in `sort_by` parameter
