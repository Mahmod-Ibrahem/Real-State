# CLAUDE.md

Rules for working in this repo. Follow them for every change; when a rule conflicts with a direct user request, ask.

## Project

Qeima — real-estate site (Egypt-focused) with a public site and an admin panel.

- **Backend:** Laravel 11, PHP 8.3. Spatie Translatable (`en`, `ar`), Cviebrock Sluggable, Spatie Permission.
- **Frontend:** Inertia + Vue 3 (`<script setup>`), Ziggy `route()`, PrimeVue in admin.
- **Styling:** Tailwind 3. Two Vite bundles: the **public site** (`resources/js/app.js`) still loads Bootstrap 5 CSS + the template's SCSS for old pages — see Tailwind rules; the **admin** (`resources/js/admin.js`, all routes under `/admin`) is pure Tailwind — see `ADMIN_UI.md`.
- **Status: not launched.** Edit existing migrations in place, delete unused code/routes/pages outright. No redirects, compat shims, or data migrations.

## Commands

```bash
php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder   # rebuild DB with demo data + real photos
php artisan cache:clear                                         # public lists are cached forever (see Caching)
npm run build                                                   # must pass before you say frontend work is done
php -l <file>                                                   # lint every PHP file you touch
```

Test DB work against a throwaway SQLite file, never the user's MySQL DB:
`DB_CONNECTION=sqlite DB_DATABASE=/path/to/scratch.sqlite CACHE_STORE=array php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder --force`

Demo images come from Pexels (`PEXELS_API_KEY` in `.env`) and are downloaded once into `storage/app/demo-images` (`database/seeders/Support/DemoImages.php`). Never commit the key.

## Modules (`nwidart/laravel-modules`)

Domain code lives in modules under `Modules/` (namespace `Modules\`, autoloaded by the root `composer.json`
`"Modules\\": "Modules/"`). Each module owns its models, migrations, factories, seeders, observers, services,
enums, requests, resources, controllers (admin **and** public) and routes.

| Module | Owns |
|---|---|
| `Location` | Country → City → Area |
| `Property` | Property, gallery (`PropertyImages`), PropertyType, PropertyClassifications, Feature, FeatureType, `ListingPurpose`/`PropertyStatus` enums, listing pages, favourites, sell page |
| `Lead` | PropertyLead, ContactLead, the contact page and client-inquiry form |
| `Content` | Faq, Broker, BasicContent, the About / FAQ / Terms / Privacy pages |
| `Dashboard` | The admin dashboard (reads other modules' models) |

`app/` keeps only what is shared or spans modules: `User`, auth (Breeze) and profile, the base `Controller`,
middleware, `ImagesUtility`/`handleDbErrors` traits, `HomeController` + `HomePageService` (the home page
composes several modules) and `LanguageController`. `database/seeders` keeps the entry seeders
(`DatabaseSeeder`, `DemoDatabaseSeeder`, `PermissionSeeder`, `UserSeeder`) and `Support/DemoImages`, which call
into the modules.

Module layout (lean, like the Hotel-Managment project; `app_folder` is empty so there is no `app/` level):

```
Modules/Property/
  module.json
  Providers/PropertyServiceProvider.php   # migrations, observers, registers RouteServiceProvider
  Providers/RouteServiceProvider.php      # loads routes/web.php in the `web` middleware group
  routes/web.php                          # public routes + an admin group
  Http/Controllers/(Admin/)  Http/Requests/  Http/Resources/(Users/)
  Models/  Enums/  Observers/  Services/
  Database/Migrations/  Database/Factories/  Database/Seeders/
```

- **Folder names match namespaces exactly** (`Database/Seeders` ↔ `Modules\Property\Database\Seeders`).
  PSR-4 is case-sensitive on Linux, so a lowercase `database/` folder would break autoloading in production.
- Migrations keep their timestamps when they move; Laravel sorts migrations from all paths by filename, so
  cross-module foreign keys still run in order.
- Models outside `App\Models` must point at their factory: `protected static function newFactory()`.
- A module may use another module's **models and enums** (Property uses Location's `City`); it must not
  reach into another module's controllers, requests or resources.
- **Inertia pages stay in `resources/js/Pages`** (one Vite build per bundle); only PHP lives in modules.
- New module: copy an existing one's `module.json` + providers, add it to `modules_statuses.json`.

## Routing

- **Route names are dot-separated, each segment kebab-case**, matching the URL:
  `/admin/property-types` → `admin.property-types.index`; never camelCase (`propertyTypes`) or PascalCase.
- **Every admin route is named `admin.…`**: register admin routes inside `Route::admin()->group(…)` (a macro in
  `AppServiceProvider::register()` = `auth` + `role:Super Admin` + `/admin` prefix + `admin.` name prefix), so
  admin and public names never collide (`faqs.index` is the public FAQ page, `admin.faqs.index` the admin list).
  Breeze auth routes (`login`, `register`, `password.*`) keep their standard names; signed-in users who open a
  guest page are sent to `admin.dashboard` (`redirectUsersTo` in `bootstrap/app.php`).
- Resource names come from the URL: `Route::resource('property-types', …)` (no `->names()` overrides).
- Each module registers its own routes in `Modules/<Name>/routes/web.php`; `routes/web.php` keeps only the
  app-level routes (home, locale switch, admin redirect, profile) and the fallback.

## Architecture (follow existing patterns)

| Concern | Where | Rule |
|---|---|---|
| Validation | `Modules/<Name>/Http/Requests/*Request.php` | Never validate in controllers. Cross-field rules live here (e.g. area must belong to city). |
| Output shape | `Modules/<Name>/Http/Resources/` (admin) and `Resources/Users/` (public) | Never pass models straight to Inertia. |
| Fixed value sets | `Modules/Property/Enums/` (`ListingPurpose`, `PropertyStatus`) | If code branches on a value, it's an enum, not an admin-editable table. Expose `label()` (translated) and `options()` for selects. |
| Cached public data | `Modules/<Name>/Services/*Service.php` (`Cache::rememberForever`) | Every cache key must be forgotten in the model's `Observers/*Observer.php`. |
| Image upload | `App\Traits\ImagesUtility::storeImage()` | Stores a full URL in the `image` column. |
| Translations | `$translatable` on models; UI strings via `__()` with `lang/ar.json` | Add the Arabic string whenever you add a user-facing label. |

Domain rules:
- Location is a hierarchy: **Country → City → Area** (`cities.country_id`, `areas.city_id`). Properties store all three ids and `PropertyRequest` keeps them consistent. Dropdowns must cascade (parent filters children).
- Features are grouped: **Feature type → Feature** (`features.feature_type_id`). The property page lists a property's features under their type, so every feature must have one.
- Parent/child links are **required** foreign keys with `restrictOnDelete()`: never nullable, never `cascade` (a cascade silently deletes children). Deleting a parent that still has children is refused with `back()->withErrors(['database' => 'This … still has …'])`, which the admin shows as an error toast.
- `purpose` (sale/rent) and `status` (available/sold/rented) are separate enums; Sold only with Sale, Rented only with Rent.
- Public listing = `ListingController@index` with query filters (`/listing?purpose=sale&city=3`). There are no separate Buy/Rent pages.

## Clean code & SRP

- **One reason to change per unit.** A controller action orchestrates; querying, filtering, SEO text and formatting go in small private methods, services, or resources. Split a method when it does more than one of these.
- **Vue components do one thing.** Extract a component when markup repeats (e.g. `navbar/FavouritesLink.vue`) or a file mixes unrelated concerns. Pages compose components; components don't fetch data.
- **Names say intent:** `filtersFrom()`, `allowedStatuses`, `isActive()` — not `data`, `handle`, `temp`. New Vue components are PascalCase.
- **Derive, don't duplicate.** Use `computed` for derived state and `watch` only to reset dependent fields. Constants (`NAV_ITEMS`) in UPPER_SNAKE at the top of `<script setup>`.
- **Small, typed PHP:** typed parameters/returns, early returns, `match` over `if` chains, `Rule::enum()` / `Rule::exists()->where()` over hand-written checks.
- **Route model binding already 404s** — don't add `if (!$model->exists())` checks (that method checks the whole table anyway).
- **No dead code:** remove unused imports, commented-out blocks you replaced, `console.log`, and CSS classes nothing uses.
- **Comments explain *why*,** not what. One line above non-obvious logic; none on obvious code.
- **Delete what you replace** (old seeders, pages, routes, CSS) in the same change, and grep for remaining references (`route('old.name')` in Ziggy throws at runtime).

## Admin UI

Every admin screen follows the admin design guide (layout, tokens, components, page recipes):

@ADMIN_UI.md

## Tailwind rules

Any component you create or substantially edit must be **Tailwind-only**. The Bootstrap-collision rules below apply to the **public site only**; the admin bundle has no Bootstrap.

- No Bootstrap classes (`btn`, `row`, `col-*`, `d-flex`, `card`, `form-control`, `badge`, `nav-*`, `list-unstyled`, …) and no new SCSS / `@apply` component classes. Utilities go in the template.
- No `!` (important) hacks. If something needs `!`, it's colliding with Bootstrap — fix it with the rules below.
- **Bootstrap CSS is still global, and its utilities are `!important`.** Avoid Tailwind classes whose names Bootstrap also defines with different values:
  - Spacing scale **3, 4, 5** in `m-*`, `p-*`, `gap-*`, `ms-*`/`me-*`/`ps-*`/`pe-*` (Bootstrap: 1rem/1.5rem/3rem vs Tailwind 0.75/1/1.25rem). Values `0`, `1`, `2`, and `6`+ are safe; otherwise use arbitrary values (`py-[12px]`).
  - `shadow`, `shadow-sm`, `shadow-lg` → use `shadow-[…]`. `rounded` → use `rounded-md`/`rounded-full`. `border` with a color class → use a side (`border-b`) or `border-[1px]`. Never `container` or `collapse`.
  - Links: add `no-underline` and an explicit text color. Use `<span>`, not `<p>`, inside nav items (Bootstrap adds `p` margins). Reset lists with `m-0 p-0 list-none`.
- Brand primary is `sky-400` (`#38bdf8`, same as the theme's `$primary`).
- Mobile-first: base classes for mobile, `md:` for desktop. Check both.
- Arabic (RTL) is supported: prefer `start`/`end` (`inset-inline-start`, `text-start`) over `left`/`right` for layout that should mirror.
- Accessibility: `aria-label` on icon-only buttons/links, `aria-expanded`/`aria-controls` on toggles, `aria-current="page"` on the active nav link, meaningful `alt` text.

## Before you say it's done

1. `php -l` on changed PHP files; grep for references to anything you renamed or deleted.
2. Re-seed a throwaway SQLite DB if you touched migrations, models, factories or seeders.
3. `npm run build` passes.
4. Tell the user what you could not verify (e.g. visual check in a browser) and which commands they must run (`migrate:fresh`, `cache:clear`).
