# Clean code

## Single responsibility

- **One reason to change per class and per method.** If you can't describe it without "and", split it.
- Controllers connect HTTP to a service. Services hold one model's (or one concern's) business logic. Resources
  shape output. Requests validate. Listeners do one side effect. A Vue component does one thing.
- A method works at **one level of abstraction**: `create()` calls `storeImage()`, `syncFeatures()` and
  `addGallery()`. It doesn't mix those calls with raw query-building lines.

## Functions

- Small, with early returns, and `match` instead of `if`/`elseif` chains or `switch`.
- **No boolean flag parameters** (`save($data, true)`). Write two well-named methods.
- 3 parameters at most. Beyond that, pass the validated array or a model.
- Use `Rule::enum()` / `Rule::exists()->where()` over hand-written checks.
- **Route model binding already 404s.** Don't add `if (!$model->exists())` checks (that method checks the whole table
  anyway).

## Types and PHP style

- Type every parameter, return and property. Use constructor property promotion with `private readonly`.
- `final` on services, controllers, listeners and notifications unless something extends them.
- **No magic strings or numbers:** enums for value sets and permissions, named constants for limits (`PER_PAGE = 10`).
- Use `Model::query()` to start queries. Use `when()` instead of `if` around query clauses.

## Names say intent

- `paginateForAdmin()`, `filtersFrom()`, `allowedStatuses`, `isActive()`. Never `data`, `handle`, `temp`, `helper`,
  `manager`, `utils`.
- Booleans read as questions (`isPublished`, `hasChildren`). Events are past tense (`PropertyApproved`).
- New Vue components are PascalCase. Constants are UPPER_SNAKE at the top of `<script setup>` (`NAV_ITEMS`).

## Design patterns: only when they earn their place

Use a pattern only when it removes real duplication or branching that exists **now**. Each one here has a concrete
use in this project:

| Pattern | Use here | Don't |
|---|---|---|
| **Service layer** | All business logic behind thin controllers (`PropertyService`). | Build wrapper services that only forward one Eloquent call with no logic. |
| **Observer** (events + listeners) | Side effects of a write: `PropertySubmitted` → mail admins. Cache invalidation in model observers. | Put business workflows in model observers. |
| **Policy** | Record-level authorization (owner edits own property). | Use one for checks a `permission:` middleware already covers. |
| **Strategy** | Behaviour that varies by type, once there are 3+ branches (image resize/path per image type instead of growing `match` blocks in `ImagesUtility`). | Add an interface + one implementation "for later". |
| **Pipeline** (`Illuminate\Pipeline\Pipeline`) | Public listing filters once each filter has its own logic (one class per filter). | Use one for 2–3 simple `where`s; `when()` is enough. |
| **Enum with behaviour** | `PropertyStatus::allowedFor()`, `PropertyPermission::middleware()`, `label()`. | Put enum logic in helpers or controllers. |

**Not used in this project:** repositories (Eloquent + services are enough), DTO/action/command layers, DDD
aggregates, interfaces with a single implementation, abstract base classes created before a second subclass exists.

## Vue

- **Components do one thing.** Extract a component when markup repeats (e.g. `navbar/FavouritesLink.vue`) or a file
  mixes unrelated concerns. Pages compose components. Components don't fetch data.
- **Derive, don't duplicate.** Use `computed` for derived state, and `watch` only to reset dependent fields.

## Hygiene

- **No dead code:** remove unused imports, commented-out blocks you replaced, `console.log`, `dd()`, and CSS classes
  nothing uses.
- **No comments by default.** Names and types should make the code clear. Add a comment only when the code can't say
  why (a non-obvious workaround or business rule), and keep it to one line. No docblocks that repeat the signature,
  no section banners, no comments on migrations, enums or obvious code.
- **Delete what you replace** (old seeders, pages, routes, CSS) in the same change. Then grep for remaining references,
  because `route('old.name')` in Ziggy throws at runtime.
- **Boy-scout rule:** code you touch moves to the current conventions (thin controller, service, request, resource).
  Code you don't touch stays as it is in that change.
