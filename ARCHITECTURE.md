# Architecture: modular monolith + service layer

One Laravel app, one database, one deploy, split into **modules** (`nwidart/laravel-modules`). A module owns its
models, business logic, HTTP layer and routes. This is **not DDD**: there are no actions, DTOs, repositories or
domain layers. The layering is plain Laravel with a **service layer** that keeps controllers thin.

## Modules and the shared kernel

| Module | Owns |
|---|---|
| `Location` | Country → City → Area |
| `Property` | Listings, gallery, types, classifications, features, favourites, sell page, the owner dashboard (`/supplier`) and the review flow |
| `Lead` | Property leads, contact leads, contact page, client inquiry form |
| `Content` | FAQs, brokers, basic content, About / Terms / Privacy pages |
| `Dashboard` | Admin dashboard (reads other modules through their services) |

`app/` is the **shared kernel**. It holds only code that is shared or spans modules: `User`, auth (Breeze) and
profile, the base `Controller`, middleware, shared traits, and the home page (`HomeController` + `HomePageService`).
New business code never goes in `app/`. If it belongs to a module, it goes there.

## Layers

```
routes/web.php     URL → middleware → controller           (every route lists its middleware, see LARAVEL_CONVENTIONS.md)
Http/Requests      validation + authorization
Http/Controllers   thin: request in → one service call → Resource / redirect out
Services           business logic, queries, transactions, caching, dispatching events
Models / Enums     relations, casts, scopes, value-based behaviour
Events → Listeners → Notifications / Mail     side effects (emails, alerts) decoupled from the service
Observers          cache invalidation only
```

- **Controllers call services, services call models.** A controller never queries a model, opens a transaction,
  stores a file or sends mail.
- **Services have no HTTP.** They never use `Request`, `request()`, `Inertia`, `redirect()`, `back()`, the session or
  Resources. They take validated arrays / models / typed values and return models, collections or paginators.
- **Side effects go through events.** A service dispatches an event (`PropertySubmitted`). Listeners send the
  notifications. The service doesn't know who is notified.

## Module layout

`app_folder` is empty, so a module has no `app/` level. **Folder names match namespaces exactly**: PSR-4 is
case-sensitive on Linux, so a lowercase `database/` folder breaks autoloading in production.

```
Modules/Property/
  module.json
  Providers/PropertyServiceProvider.php    # migrations, observers, event → listener map, policies
  Providers/RouteServiceProvider.php       # loads routes/web.php in the `web` middleware group
  routes/web.php
  Http/Controllers/(Admin/)  Http/Requests/  Http/Resources/(Users/)
  Services/        PropertyService.php  PropertyGalleryService.php  PropertyTypeService.php (cached reads)
  Models/  Enums/  Policies/  Events/  Listeners/  Notifications/  Observers/  Mail/
  Database/Migrations/  Database/Factories/  Database/Seeders/
```

Create a folder only when its first class exists.

## Module boundaries

- A module may use another module's **models, enums, services and events**.
- It never imports another module's **controllers, requests or resources** (`Http/*` is private). If `Property` needs
  cities on a page, it writes its own resource in `Modules/Property/Http/Resources/`.
- **Writes into another module go through that module's service** (`CityService`), never `City::create()` from
  outside `Location`.
- **Reacting to another module** means listening to its event from your own service provider.
- Composite pages (home, dashboard) are built by a service in the consuming module (or `app/` for home) that calls
  each owning module's services.
- Foreign keys across modules are fine (one database). Migrations keep their timestamps so cross-module FKs run in
  order.
- **Inertia pages stay in `resources/js/Pages`** (one Vite build per bundle). Only PHP lives in modules.

## Adding a module

1. Copy an existing module's `module.json` and `Providers/`, rename the namespaces, add it to `modules_statuses.json`.
2. Add a row to the table above.
3. Models outside `App\Models` point at their factory: `protected static function newFactory()`.

## Legacy code

Older controllers (e.g. `Admin/PropertyController`, `ListingController`) still query models, open transactions and
use `Location` resources from `Property`. Don't copy that shape. When a change touches one, move the touched logic
into a service in the same change (boy-scout rule, [CLEAN_CODE.md](CLEAN_CODE.md)).
