# Clandent PIM - Laravel Setup

## Quick Start

### 1. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup

**Option A: MySQL (Recommended)**
1. Create database:
   ```sql
   CREATE DATABASE clandent_pim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Update `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=clandent_pim
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. Run schema (use `database/schema.sql` or migrations):
   ```bash
   php artisan migrate
   ```

**Option B: Manual SQL Import**
```bash
mysql -u root -p clandent_pim < database/schema.sql
```

### 3. Environment Variables

Add to `.env`:
```
WC_STORE_URL=https://your-woocommerce-store.com
WC_CONSUMER_KEY=ck_xxxx
WC_CONSUMER_SECRET=cs_xxxx

WP_USERNAME=admin
WP_APP_PASSWORD=xxxx

IMAGIFY_API_KEY=xxxx
OPENAI_API_KEY=sk-xxxx

MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=465
MAIL_USERNAME=your-email@zoho.com
MAIL_PASSWORD=xxxx
MAIL_FROM_ADDRESS=your-email@zoho.com
MAIL_FROM_NAME="Clandent PIM"
```

### 4. Create Admin User

```bash
php artisan tinker
>>> $user = App\Models\User::create([
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'full_name' => 'Administrator',
    'role' => 'admin',
    'is_active' => true,
]);
>>> exit
```

### 5. Run Application

```bash
npm install
npm run dev     # Frontend assets
php artisan serve  # Or use web server
```

Visit: http://localhost:8000

---

## Project Structure

```
app/
  ├── Models/              # Eloquent models
  ├── Http/
  │   ├── Controllers/     # Route handlers
  │   └── Middleware/      # Auth, admin checks
  ├── Policies/            # Authorization
  └── Services/            # Business logic (WC, Imagify, OpenAI, etc.)

resources/views/
  ├── layouts/             # Base template
  ├── products/            # Product CRUD views
  └── users/               # User management (admin)

database/
  ├── migrations/          # Schema migrations
  └── schema.sql           # Complete schema dump (for manual setup)

routes/web.php             # All routes defined here
```

---

## Key Features Implemented

✅ **Products**
- CRUD (Create, Read, Update, Delete)
- Simple & Variable types
- Duplicate functionality
- Export to WooCommerce

✅ **Users** (Admin only)
- User management
- Roles: admin, user
- Active/inactive status

✅ **Services**
- WooCommerceService: API integration with caching
- ImagifyService: Image compression
- OpenAIService: Product description generation
- ImageUploadService: Local file handling

✅ **Security**
- Laravel Breeze auth (if installed)
- Policy-based authorization
- Admin middleware
- CSRF protection

✅ **Database**
- 10 tables with proper relationships
- Foreign key constraints
- Timestamps on all models

---

## Configuration Notes

### WooCommerce API
- Stored in `config/services.php`
- Uses consumer_key/secret from `.env`
- Retry logic: 3x with exponential backoff
- Cache: 3600 seconds (file or Redis)

### Services
All services are in `app/Services/`:
- **WooCommerceService**: Wraps WC REST API v3
- **ImagifyService**: Image optimization API
- **OpenAIService**: GPT-4 for descriptions
- **ImageUploadService**: Local storage handling

---

## Missing/TODO

- [ ] Migrations won't run without MySQL/SQLite driver
- [ ] Authentication views (use Breeze: `php artisan breeze:install`)
- [ ] Activity logging (insert hooks in controllers)
- [ ] Export logs tracking
- [ ] Image upload/compression pipeline
- [ ] Tests (unit, feature)

---

## Troubleshooting

### No database driver
Environment missing PDO extensions. Install PHP extensions or use Docker.

### Migrations fail
Run schema.sql directly: `mysql -u root -p clandent_pim < database/schema.sql`

### Authentication not working
Install Breeze: `php artisan breeze:install`

---

## Next Steps

1. **Set up authentication** (Breeze or custom)
2. **Create initial admin user**
3. **Configure WooCommerce credentials**
4. **Test product export workflow**
5. **Add activity logging**
6. **Set up email notifications**
