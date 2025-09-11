# Copilot Instructions for Mini LMS

## Architecture Overview
- **Controller**: Handles HTTP requests and responses. Example: `app/Http/Controllers/Admin/`
- **Service**: Contains business logic. Example: `app/Services/`
- **Repository**: Handles DB access (not always separated, Eloquent models are used directly).
- **Event/Listener**: Used for side effects (e.g., sending mail on program registration). See `app/Events/`, `app/Listeners/`, and `app/Mail/`.
- **Models**: Eloquent ORM models in `app/Models/` define relationships and accessors/mutators.
- **Views**: Blade templates in `resources/views/`.
- **Routes**: Defined in `routes/web.php`.

## Key Patterns & Conventions
- **Role-based Access**: Admin and Manager roles are enforced via Gates and Middleware. See `app/Providers/AdminServiceProvider.php` and `app/Http/Middleware/AdminAuthMiddleware.php`.
- **Approval Status**: Only admins can modify `approval_status` on programs. Managers cannot see or edit this field in forms or routes.
- **Mailing**: Mail sending is handled via events and listeners. Use `Mail::to(...)->queue(...)` for async mail. Mailtrap is used for development.
- **Soft Deletes**: Models like `Program` use `SoftDeletes`.
- **Testing**: PHPUnit is configured (`phpunit.xml`). Tests are in `tests/Unit` and `tests/Feature`.

## Developer Workflows
- **Run the app**: Use `php artisan serve`.
- **Run tests**: Use `php artisan test` or `vendor/bin/phpunit`.
- **Migrate DB**: `php artisan migrate` (MariaDB by default).
- **Queue**: For async jobs, run `php artisan queue:work`.
- **Mail**: Configure `.env` for Mailtrap or local SMTP. For dev, use `MAIL_MAILER=log` or `MAIL_MAILER=smtp` with Mailtrap.

## Project-Specific Details
- **Admin/Manager distinction**: Only admins can access instructor management routes and see related menu items. Managers are restricted at both route and view level.
- **Program Approval**: `approval_status` is settable only by admins. Managers' program submissions default to 'pending'.
- **Blade Layouts**: Main admin layout is in `resources/views/admin/layouts/app.blade.php`.
- **Events**: Program registration triggers `StoreProgramEvent`, handled by `SendEmailListener`.

## Integration Points
- **Mailtrap**: Used for email testing in development.
- **Vite**: Frontend assets are managed via `vite.config.js`.
- **Composer**: PHP dependencies managed via `composer.json`.

## Examples
- To add a new admin-only feature, protect routes with `'can:is-admin'` and check `auth()->user()->role` in Blade.
- To add a new event-driven side effect, create an Event in `app/Events/`, a Listener in `app/Listeners/`, and register in `app/Providers/EventServiceProvider.php`.

---

If you are unsure about a pattern, check the relevant directory for examples. For new features, follow the existing separation of concerns and role-based access patterns.
