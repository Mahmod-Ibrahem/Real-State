# Laravel conventions

The shape of every backend building block. Where each one lives is in [ARCHITECTURE.md](ARCHITECTURE.md).

## Request lifecycle

```
Route (+ middleware) → FormRequest (authorize + validate) → Controller → Service → Model(s)
                     → Controller wraps the result in a Resource → Inertia::render(...) or redirect
```

## Naming

| Thing | Folder | Pattern | Example |
|---|---|---|---|
| Controller | `Http/Controllers/(Admin/ \| Owner/)` | `{Model}Controller`; single action → `{Thing}Controller` + `__invoke` | `PropertyController`, `PropertyListingStateController` |
| Form request | `Http/Requests/` | `{Model}Request`, or `Store/Update/Index{Model}Request` | `CityRequest`, `StorePropertyRequest` |
| Single-record resource | `Http/Resources/` | `{Model}Resource` | `PropertyResource` |
| List-row resource | `Http/Resources/` | `{Model}ListResource` | `PropertyListResource` |
| Public-site resource | `Http/Resources/Users/` | same names, public shape | `Users\PropertyResource` |
| Service | `Services/` | `{Model}Service`, or `{Model}{Concern}Service` when split | `PropertyService`, `PropertyGalleryService` |
| Policy | `Policies/` | `{Model}Policy` | `PropertyPolicy` |
| Permission enum | `Enums/` | `{Module}Permission` | `PropertyPermission` |
| Event | `Events/` | past tense | `PropertySubmitted`, `PropertyApproved` |
| Listener | `Listeners/` | what it does | `NotifyAdminsOfSubmittedProperty` |
| Notification | `Notifications/` | `{Event}Notification` | `PropertyApprovedNotification` |
| Model | `Models/` | singular PascalCase | `Property`, `FeatureType` |

New models are singular. `PropertyClassifications` and `PropertyImages` are legacy names. Don't copy them.

## Controllers: thin

A controller method has three steps: **take the validated request → call one service method → respond.**

- Use resourceful methods only (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`). Anything else
  (approve, reject, publish) is a single-action controller with `__invoke`.
- Inject the service through the constructor (`private readonly PropertyService $properties`). Type every return
  (`Inertia\Response`, `RedirectResponse`).
- **Never in a controller:** Eloquent queries (`Model::where/query/all/create`), `DB::`, `try/catch`, `request()`,
  `$request->validate()`, business-rule `if`s, `$model->exists()` (route model binding already 404s), file storage,
  cache calls, `Mail::`/`notify()`, `event()`.
- Mapping models to Resources is the controller's job. When `create` and `edit` share props, a private `formProps()`
  in the controller is fine.
- Aim for under 10 lines per method.

Before (legacy `Admin/PropertyController@store`): validation result checks, `DB::beginTransaction()`, image storage,
gallery and features, and `try/catch` with `handleDbErrors`, all in the controller.

After:

```php
final class PropertyController extends Controller
{
    public function __construct(private readonly PropertyService $properties) {}

    public function index(IndexPropertyRequest $request): Response
    {
        return Inertia::render('Admin/Properties/Index', [
            'properties' => PropertyListResource::collection($this->properties->paginateForAdmin($request->search())),
            'purposes' => ListingPurpose::options(),
            'statuses' => PropertyStatus::options(),
        ]);
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $this->properties->create($request->validated());

        return to_route('admin.properties.index')->with('success', __('Property created successfully.'));
    }
}
```

## Services

```php
namespace Modules\Property\Services;

final class PropertyService
{
    public function __construct(private readonly PropertyGalleryService $gallery) {}

    public function paginateForAdmin(?string $search): LengthAwarePaginator
    {
        return Property::query()
            ->with(['city', 'area', 'propertyType'])
            ->when($search, fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Property
    {
        return DB::transaction(function () use ($data): Property {
            $property = Property::create(Arr::except($data, ['feature_ids', 'gallery_images']));
            $property->features()->sync($data['feature_ids']);
            $this->gallery->add($property, $data['gallery_images']);

            return $property;
        });
    }
}
```

- **One service per model by default** (`PropertyService`). Split by concern as soon as a service mixes unrelated
  jobs or passes ~200 lines (`PropertyGalleryService`, `PropertyReviewService`, `ListingSearchService`).
- Methods are named for the use case: `paginateForAdmin()`, `create()`, `approve()`, `publish()`. Not `handle()`,
  `process()` or `doStuff()`.
- Input is `$request->validated()` (an array), a model, or typed scalars. Output is a model, collection, paginator,
  bool or `void`. **Never a response, Resource or Inertia page.**
- **The service owns transactions** (`DB::transaction(fn () => …)`) whenever it writes more than one row or file.
- **The service dispatches events** after the write succeeds (`PropertySubmitted::dispatch($property)`). It never
  sends mail or notifications itself.
- Business rules that aren't validation throw an exception (see Errors). Don't return `false` or error arrays.
- Services are resolved by the container. Inject other services through the constructor and never `new` them.
- **Cached public reads** (`PropertyTypeService::all()`) use `Cache::rememberForever`. Every cache key is forgotten in
  the model's `Observers/*Observer.php`.
- Image storage goes through `App\Traits\ImagesUtility::storeImage()` (stores a full URL in the `image` column) and
  is used in services, not controllers.

## Form requests

- **All validation lives here.** That includes cross-field rules (city belongs to country, status allowed for
  purpose) and uniqueness (`Rule::unique(...)->ignore($this->route('property'))`). Don't check uniqueness in code
  and don't catch duplicate-key exceptions.
- **One `{Model}Request`** when store and update rules are identical (e.g. `CityRequest` for a modal form).
  **Split into `Store{Model}Request` / `Update{Model}Request`** as soon as they differ. Never branch on
  `isMethod('POST')`.
- **Every request is standalone:** it extends `FormRequest` directly, with no inheritance between requests and no
  shared rule traits. Each one lists all of its rules, so you can read what an endpoint accepts in one file, and
  changing one request can't silently change another. Separate audiences get separate requests
  (`Owner\UpdateOwnerPropertyRequest` vs `UpdatePropertyRequest`).
- Query-string input (search, filters) gets an `Index{Model}Request` with typed accessors (`search(): ?string`,
  `filters(): array`). Never read `request('search')` in a controller.
- Prefer `Rule::enum()`, `Rule::exists()->where()` and `Rule::unique()` over string rules when a rule has conditions.
  Custom messages go in `messages()`.
- `authorize()` returns `true` only when the route's middleware already checks the permission. Ownership checks
  ("is this my property?") use the policy: `return $this->user()->can('update', $this->route('property'));`.

## API resources

- **`{Model}Resource`** is one record with the full shape a show page or edit form needs.
- **`{Model}ListResource`** is a lean row for tables and lists. Include only the columns shown, relation *names*, and
  raw enum values for quick edits (see `PropertyListResource`).
- **`Resources/Users/`** holds the public-site shape. Admin and public shapes differ, so don't share one resource with
  `if ($isAdmin)` branches.
- **Never pass a model, a collection of models or `->toArray()` to Inertia.** Always use `Resource::make()` /
  `Resource::collection()` (paginators keep their meta).
- **No queries in a resource.** The service eager-loads (`with([...])`). Read optional relations with
  `$this->whenLoaded()`.
- A module only uses its **own** resources.
- **The wire format is snake_case** in both directions. Request input keys (form fields) and resource output keys
  match the column names: `property_type_id`, `review_status`, `rejection_reason`, `gallery_images`,
  `removed_image_ids`. Vue props and `useForm()` fields use the same snake_case keys, while JS variables and
  functions stay camelCase. Legacy camelCase keys (`propertyTypeId`, `countryId`) are converted when their resource
  is touched.

## Routes: middleware on every route

Every route is written on its own line and **lists its middleware**, so `routes/web.php` gives a bird's-eye view of
who can reach what. Groups may set only `prefix` and `name`, never middleware.

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('properties', [PropertyController::class, 'index'])
        ->name('properties.index')->middleware(['auth', PropertyPermission::View->middleware()]);
    Route::post('properties', [PropertyController::class, 'store'])
        ->name('properties.store')->middleware(['auth', PropertyPermission::Create->middleware()]);
    Route::patch('properties/{property}/approve', ApprovePropertyController::class)
        ->name('properties.approve')->middleware(['auth', PropertyPermission::Review->middleware()]);
});
```

- Protected routes are explicit `Route::get/post/patch/delete` lines (not `Route::resource`), because each action
  needs its own permission. Names and URLs still follow resource conventions (`properties.index`,
  `properties.store`, …).
- **Route names are dot-separated, each segment kebab-case**, matching the URL (`/admin/property-types` →
  `admin.property-types.index`). Admin routes are named `admin.…` and owner-dashboard routes `supplier.…`.
- A public route without middleware is public at a glance. Don't add the `web` group by hand (the module's
  `RouteServiceProvider` already applies it).
- Ownership-scoped routes add the policy check on the route: `->middleware(['auth', 'can:update,property'])`.
- Each module registers its own routes in `Modules/<Name>/routes/web.php`. `routes/web.php` keeps only app-level
  routes (home, locale switch, profile) and the fallback.
- Legacy: `Route::admin()` (in `AppServiceProvider::register()`) still hides `auth` + `role:Super Admin` inside a
  group. Replace it with explicit per-route middleware when its routes are touched.

## Authorization: permissions, never roles

- **Code checks permissions only:** the `permission:` middleware, `$user->can('properties.approve')`, policies,
  `@can`. Never `role:` middleware, `hasRole()`, or role names in code or in the frontend.
- **Roles only group permissions.** They are defined in `PermissionSeeder` (`Super Admin` gets every permission,
  `Property Owner` gets `PropertyPermission::forOwners()`). Code names a role in only two places:
  `App\Services\OwnerRegistrationService::ROLE`, which gives new sign-ups their default role, and
  `PermissionSeeder::SUPER_ADMIN`, which the `Users` module uses to hide Super Admin (the role and its accounts) from
  `/admin/users` and refuse to assign, edit or delete it there. Never use a role in an authorization check.
- **Permission names are `{verb} {resource}` in lowercase words**, one per action: `view cities`,
  `create cities`, `edit cities`, `delete cities`, `review properties`, `publish own properties`. The verbs are
  `view`, `create`, `edit`, `delete`, plus a domain verb where an action is special (`review`, `publish`).
- **Notification permissions are `receive {topic} notifications`** and live in `App\Enums\NotificationPermission`:
  `receive notifications` (in-dashboard notifications for any user) and one per admin alert, e.g.
  `receive property submission notifications`, `receive property lead notifications`.
- **Each module defines its permissions in one enum** (`Modules/Property/Enums/PropertyPermission`), the single
  source of truth. Enums use the `App\Enums\Concerns\IsPermission` trait, and routes call `->middleware()`:
  ```php
  enum PropertyPermission: string
  {
      use IsPermission;

      case View = 'view properties';
      case Review = 'review properties';
  }
  ```
- **`PermissionSeeder` is safe to re-run in production:** it creates any missing permission, always syncs
  `Super Admin` to every permission, gives other roles their defaults only when the role is new, and afterwards only
  adds the permissions that were just created, so role edits survive.
- Record-level rules (owners touch only their own properties) go in a **policy** (`PropertyPolicy`) that checks the
  permission **and** ownership. Register it in the module's service provider.
- Share the signed-in user's permission names with Inertia (`HandleInertiaRequests`) so Vue hides actions the user
  can't perform. The route middleware still enforces it.

## Events, listeners, notifications

- A service dispatches an **event** after a successful write. Events carry the model and nothing else:
  `final class PropertySubmitted { use Dispatchable, SerializesModels; public function __construct(public Property $property) {} }`.
- **Listeners** do one thing each (`NotifyAdminsOfSubmittedProperty`), implement `ShouldHandleEventsAfterCommit`
  (not `ShouldQueue`), and are registered in the module's service provider (`Event::listen(...)`). Listeners in
  `app/Listeners` are auto-discovered by Laravel, so they are never registered by hand (they would run twice). They only pick the
  recipients and hand off to the notification, which owns the queueing.
- Listeners send **notifications** (`Illuminate\Notifications\Notification`, `via()` → `['mail']` or
  `['mail', 'database']`). Notifications implement `ShouldQueue` and route the `database` channel to the `sync`
  connection in `viaConnections()`, so the bell row is written instantly while mail waits for the queue worker:
  `return ['mail' => config('queue.default'), 'database' => 'sync'];`. **Recipients are chosen by a `receive … notifications` permission**, never by role and
  never by reusing an action permission:
  `User::permission(NotificationPermission::PropertySubmissions->value)->get()`. A listener that notifies one user
  (the owner) skips them unless they `can(NotificationPermission::Receive->value)`. Admins can then opt out of a mail
  without losing the right to act on it.
- **Database notifications feed the top-bar bell** (admin and supplier). Their `toArray()` returns `message` (one
  translated sentence) and `url` (the page opening it should go to). Pages share nothing about notifications. The bell
  fetches the list and unread count from its own JSON endpoint, `notifications.index` (`NotificationResource`
  collection + `meta.unread`), with `axios` every 60 seconds. Opening one goes through `notifications.open`,
  which marks it read and redirects to its `url`. `notifications.read-all` (also `axios`) marks everything read and
  returns `204`.
- Notification text goes through `__()` with Arabic strings in `lang/ar.json`. Mail links use `route()`.
- Run mail locally with `MAIL_MAILER=log`. Queued mail needs a queue worker (`composer dev` starts one); database
  notifications don't.
- **Exception: one-time codes.** A verification code is sent by the service that issues it, straight to the user
  (`$user->notify(new EmailVerificationCodeNotification($code))`), with no event and **no queue** (the notification
  does not implement `ShouldQueue`), so the code never sits in an event payload or the `jobs` table and arrives at
  once.

## Errors

- **Validation errors** come from form requests (Inertia shows them on the field).
- **Business-rule violations** (e.g. publishing a property that isn't approved, deleting a parent that still has
  children) are thrown from the service as an exception extending `App\Exceptions\BusinessRuleException`. It is
  rendered once in `bootstrap/app.php`:
  ```php
  $exceptions->render(fn (BusinessRuleException $e) => back()->withErrors(['database' => $e->getMessage()]));
  ```
  This keeps the admin's error-toast contract (`errors.database`) without `try/catch` in controllers. Messages go
  through `__()`. Create the base class when the first one is needed.
- **Too many requests** (a `throttle:` route over its limit) are rendered the same way, never as Laravel's `429`
  page: `back()->withErrors(['database' => 'Too many attempts. Please try again in N seconds.'])`. JSON requests
  still get the plain `429`. Pages without the toast (login) show `errors.database` under their first field.
- **Unexpected errors** are not caught. Let them reach the handler. The legacy `handleDbErrors` trait is not used in
  new code.

## Models, enums, observers

| Concern | Rule |
|---|---|
| Models | Relations (typed return), casts (enums cast), scopes (`active()`), `$fillable`, `$translatable`. No HTTP and no business workflows. |
| Mass assignment | Services pass `$request->validated()` straight to `create()` / `fill()` / `createMany()`; `$fillable` decides which keys are columns, so don't strip non-column keys (`feature_ids`, `payment_plans`) by hand. Columns the service sets itself (an uploaded `image` it stores) stay **out** of `$fillable`, so a `null` in the request can't overwrite them. |
| Enums (`Modules/<Name>/Enums/`) | If code branches on a value, it's an enum, not an admin-editable table. Expose `label()` (translated) and `options()` for selects. Value-dependent behaviour lives on the enum (`PropertyStatus::allowedFor()`). |
| Observers | Cache invalidation only. Business side effects use events + listeners. |
| Translations | `$translatable` on models. UI strings use `__()` with `lang/ar.json`; add the Arabic string with every new label or message. |
