# AI Agent Instructions for web-enroll

This is a Laravel-based web application for course enrollment management. Here's what you need to know to work effectively with this codebase:

## Project Architecture

- **Core Domain**: Subject enrollment system with users and subjects as primary entities
- **Framework**: Laravel 12.0 with PHP 8.2+
- **Frontend**: Laravel Blade templates with Vite for asset compilation

### Key Components

- `app/Models/` - Contains Eloquent models (e.g., `Subject.php`, `User.php`)
- `app/Http/Controllers/` - Controllers following RESTful patterns
- `resources/views/` - Blade templates organized by feature
- `routes/web.php` - Web route definitions using controller grouping pattern

## Development Workflow

### Setup and Installation
```bash
composer run setup  # Installs dependencies, sets up .env, generates key, runs migrations
```

### Development Server
```bash
composer run dev   # Starts development servers with hot reload
```
This runs concurrent processes:
- Laravel development server
- Queue listener
- Log watcher (Pail)
- Vite dev server

### Testing
```bash
composer run test  # Clears config cache and runs tests
```

## Project Conventions

1. **Routing**:
   - Routes are grouped by controller using the `Route::controller()` pattern
   - Consistent route naming using prefixes (e.g., `subjects.list`, `subjects.view`)
   - Example in `routes/web.php`:
   ```php
   Route::controller(SubjectController::class)
       ->prefix('/subjects')
       ->name('subjects.')
       ->group(static function (): void {
           Route::get('', 'list')->name('list');
           Route::get('/{subject}', 'view')->name('view');
       });
   ```

2. **Models**:
   - Use `protected $fillable` for mass-assignment protection
   - Follow Laravel Eloquent conventions for relationships

3. **Controllers**:
   - Return type hints for methods (View, RedirectResponse, etc.)
   - Use type-safe method parameters
   - Constants defined for pagination/limits (e.g., `MAX_ITEMS`)

4. **Views**:
   - Organized in feature-specific directories under `resources/views/`
   - Use Blade templating with layouts

## Common Tasks

- Adding a new subject-related feature:
  1. Add route in `routes/web.php`
  2. Create/update method in `SubjectController`
  3. Create corresponding Blade view in `resources/views/subjects/`

- Database changes:
  1. Create migration in `database/migrations/`
  2. Update relevant model in `app/Models/`
  3. Run `php artisan migrate`

## Integration Points

- PSR-7 HTTP Message integration via `nyholm/psr7`
- Queue system for background jobs
- Vite for asset compilation and bundling

For detailed Laravel framework documentation, refer to [Laravel Docs](https://laravel.com/docs).