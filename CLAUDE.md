# CLAUDE.md

Rules for working in this repo. Follow them for every change. When a rule conflicts with a direct user request, ask.
Write any new convention into the right doc below **before** implementing it.

## Project

Qeima: a real-estate site (Egypt-focused) with a public site and an admin panel.

- **Backend:** Laravel 11, PHP 8.3, modular monolith (`nwidart/laravel-modules`) with a **service layer**. No DDD.
  Spatie Translatable (`en`, `ar`), Cviebrock Sluggable, Spatie Permission (**permissions, never roles**, in code).
- **Frontend:** Inertia + Vue 3 (`<script setup>`), Ziggy `route()`, Headless UI. No PrimeVue.
- **Styling:** **Tailwind only.** The admin bundle is pure Tailwind. The public bundle still loads Bootstrap as legacy
  that is being removed. Never add Bootstrap.
- **Status: not launched.** Edit existing migrations in place and delete unused code/routes/pages outright. No
  redirects, compat shims or data migrations.

## Rule docs

Each doc owns one topic. Each rule lives in exactly one of them.

| Doc | Covers |
|---|---|
| [ARCHITECTURE.md](ARCHITECTURE.md) | Modules, layers (controller → service → model), module layout and boundaries |
| [LARAVEL_CONVENTIONS.md](LARAVEL_CONVENTIONS.md) | Thin controllers, services, form requests, `Resource`/`ListResource`, routes with per-route middleware, permissions, events/notifications, errors |
| [CLEAN_CODE.md](CLEAN_CODE.md) | SRP, functions, naming, design patterns (when and when not), Vue, hygiene |
| [FRONTEND.md](FRONTEND.md) | Tailwind-only rules, Bootstrap removal, RTL, accessibility |
| [ADMIN_UI.md](ADMIN_UI.md) | Admin layout, tokens, components, page recipes |

@ARCHITECTURE.md

@LARAVEL_CONVENTIONS.md

@CLEAN_CODE.md

@FRONTEND.md

@ADMIN_UI.md

## Commands

```bash
php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder   # rebuild DB with demo data + real photos
php artisan cache:clear                                         # public lists are cached forever (see Services)
npm run build                                                   # must pass before you say frontend work is done
php -l <file>                                                   # lint every PHP file you touch
```

Test DB work against a throwaway SQLite file, never the user's MySQL DB:
`DB_CONNECTION=sqlite DB_DATABASE=/path/to/scratch.sqlite CACHE_STORE=array php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder --force`

Demo images come from Pexels (`PEXELS_API_KEY` in `.env`) and are downloaded once into `storage/app/demo-images`
(`database/seeders/Support/DemoImages.php`). Never commit the key.

`database/seeders` keeps the entry seeders (`DatabaseSeeder`, `DemoDatabaseSeeder`, `PermissionSeeder`, `UserSeeder`)
and `Support/DemoImages`, which call into the modules' seeders.

## Auth

- **Two login pages, one `users` table and guard.** Admins log in at `/admin/login` (`login`). Property owners sign
  up at `/supplier/register` (`supplier.register`) and log in at `/supplier/login` (`supplier.login`). Both pages render
  `Pages/Auth/Login.vue`. There is no admin self-registration.
- After login, and when a signed-in user opens a guest page, users go to `User::dashboardRoute()`: `admin.dashboard`
  if they can `view dashboard`, else `supplier.dashboard`. Guests hitting `/supplier/*` are sent to `supplier.login`,
  everything else to `login` (`bootstrap/app.php`).
- Password reset (`password.*`) stays under `/admin` and is shared by both.
- **Login:** both `POST` login routes carry Laravel's built-in `throttle:5,1` (5 attempts a minute per IP; more get a
  `429`). `LoginRequest`
  only validates and exposes `credentials()` / `remember()`. `App\Services\LoginService` checks the credentials and
  `is_active`, then signs the user in. It throws a `ValidationException` on `email`, so the form shows the message
  under the field. The controller only regenerates the session and redirects.
- **Account flags** on `users`: `is_active` (default `true`) and `under_review` (default `false`). An inactive user
  can't log in ("Your account is disabled."), and one who is already signed in is logged out on their next request
  (`EnsureAccountIsActive`). New supplier sign-ups start `under_review`. They can log in and add properties, but
  none of their properties are shown on the public site, even approved and published ones, until an admin clears
  the flag. Admins switch both flags from the `/admin/users` table. Users can't disable themselves, and Super Admin
  accounts can't be changed there.

## Domain rules

- Location is a hierarchy: **Country → City → Area** (`cities.country_id`, `areas.city_id`). Properties store all
  three ids, and the property request keeps them consistent. Dropdowns must cascade (parent filters children).
- Features are grouped: **Feature type → Feature** (`features.feature_type_id`). The property page lists a property's
  features under their type, so every feature must have one.
- Parent/child links are **required** foreign keys with `restrictOnDelete()`: never nullable, never `cascade` (a
  cascade silently deletes children). Deleting a parent that still has children is refused by the service with a
  `BusinessRuleException` ("This … still has …"), which the admin shows as an error toast (`errors.database`).
- `purpose` (sale/rent) and `status` (available/sold/rented) are separate enums. Sold only with Sale, Rented only
  with Rent.
- FAQs (question + answer, no categories) are shown **on the home page** (`Components/Users/FaqSection.vue`, anchor
  `/#faqs`), fed by `FaqService` through `HomePageService`. There is no public FAQ page or route; only the admin
  manages them at `/admin/faqs`.
- **Every property has an owner** (`owner_id`, required) and a **review status** (`ReviewStatus`: pending /
  approved / rejected):
  - Owners add properties from `/supplier`. A new property is **pending** and unpublished, and `PropertySubmitted`
    mails and notifies (dashboard bell) every user with `receive property submission notifications`. That permission controls who gets the
    mail; it is separate from `review properties` (who may approve or reject).
  - Admins approve (`PropertyApproved` notifies the owner by mail + database) or reject with a required reason
    (shown on the owner's dashboard, no notification). Properties an admin creates are approved at once.
  - Only approved properties can be published. The owner publishes and unpublishes them.
  - Owners can edit every field. **Any real change** (a field, features, cover or gallery) to an approved or
    rejected property sends it back to pending, unpublishes it, and mails admins again. A save that changes
    nothing keeps the current status. Editing a property that is already pending keeps it pending without a new
    mail.
  - `Property::active()` (the public site) means approved **and** published **and** its owner is not
    `under_review`. Changing an owner's `under_review` forgets the cached public lists.
  - Every public read uses `active()`, including the property page (`listing.show`, via `PropertyDetailService`):
    a property that isn't listed is a 404 there, and its related properties are listed ones only.
- Public listing = `ListingController@index` with query filters (`/listing?purpose=sale&city=3`). There are no
  separate Buy/Rent pages.

## Before you say it's done

1. `php -l` on changed PHP files. Grep for references to anything you renamed or deleted.
2. Controllers you touched contain no queries, `DB::`, `try/catch`, mail or events. That logic lives in a service.
3. Every route you added lists its middleware, and authorization uses permissions (no `role:`/`hasRole`).
4. Re-seed a throwaway SQLite DB if you touched migrations, models, factories, seeders or permissions.
5. `npm run build` passes.
6. Tell the user what you could not verify (e.g. a visual check in a browser) and which commands they must run
   (`migrate:fresh`, `cache:clear`, `queue:work`).
