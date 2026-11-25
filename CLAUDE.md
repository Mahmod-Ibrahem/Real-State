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
- **Login:** both `POST` login routes carry Laravel's built-in `throttle:5,1` (5 attempts a minute per IP; more are
  refused with "Too many attempts…", see LARAVEL_CONVENTIONS.md, Errors). `LoginRequest`
  only validates and exposes `credentials()` / `remember()`. `App\Services\LoginService` checks the credentials and
  `is_active`, then signs the user in. It throws a `ValidationException` on `email`, so the form shows the message
  under the field. The controller only regenerates the session and redirects.
- **Account flags** on `users`: `is_active` (default `true`) and `under_review` (default `false`). An inactive user
  can't log in ("Your account is disabled."), and one who is already signed in is logged out on their next request
  (`EnsureAccountIsActive`). New supplier sign-ups start `under_review`. They can log in and add properties, but
  none of their properties are shown on the public site, even approved and published ones, until an admin clears
  the flag. Admins switch both flags from the `/admin/users` table. Users can't disable themselves, and Super Admin
  accounts can't be changed there.
- **Phone** on `users`: `phone` (E.164, unique, e.g. `+201001234567`), `phone_country` (ISO-2) and
  `phone_verified_at`. Supplier sign-up requires a **mobile** number: the country is picked with `PhoneField.vue`
  (a searchable `SelectField` of every country with its dialing code, from `libphonenumber-js`) and
  `OwnerRegistrationRequest` normalises the number to E.164 before validating (`propaganistas/laravel-phone`), so
  the unique check compares like with like. An unverified supplier can log in and work. Their dashboard shows a
  notice linking to their profile (`supplier.profile.show`, read-only name, email and phone for now), where the
  phone's **Verify** button opens a dropdown that sends a 6-digit code and checks it, typed into `OtpField.vue`
  (`supplier.phone.code`, `throttle:3,10`; `supplier.phone.verify`, `throttle:5,10`).
- **Phone codes** go through `App\Services\PhoneVerification\PhoneVerificationService`, which uses a `PhoneVerifier`
  picked by `PHONE_VERIFICATION_DRIVER`: `twilio` (Twilio Verify creates, sends, expires and checks the code; keys in
  `TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_VERIFY_SID`), `whatsapp` (Meta's WhatsApp Cloud API sends the code in an
  authentication template with a copy-code button; keys in `WHATSAPP_TOKEN`, `WHATSAPP_PHONE_NUMBER_ID`,
  `WHATSAPP_OTP_TEMPLATE`, `WHATSAPP_TEMPLATE_LANGUAGE`; Meta's free test number reaches up to 5 verified recipients)
  or `log` (default for local work: the code is written to the log). `whatsapp` and `log` issue and check the code with
  `App\Services\VerificationCodes` (a hash in the cache for 10 minutes). The code itself is never stored in the
  database. When Twilio or WhatsApp refuses a send, the driver reports the error and throws `PhoneCodeNotSent`.
- **Email** is verified the same way, with a 6-digit code instead of Laravel's signed link (`User` does not implement
  `MustVerifyEmail`). It mirrors the phone: `App\Services\EmailVerification\EmailVerificationService` uses an
  `EmailVerifier` picked by `EMAIL_VERIFICATION_DRIVER`: `mail` (mails the code with
  `EmailVerificationCodeNotification`) or `log` (default for local work: writes "Email verification code for
  {email}: {code}" to the log and sends nothing). Both keep a hash of the code in the cache for 10 minutes through
  `App\Services\VerificationCodes` (shared with the phone's `log` driver). If the mail server fails, the `mail`
  driver reports the error (it lands in the log) and throws `EmailCodeNotSent`, so the supplier gets an error toast
  ("We couldn't send a code to this email address. Please try again later.") instead of an error page, like
  `PhoneCodeNotSent`. The profile's email row has the same Verify dropdown
  (`Components/Owner/VerifyCodeDropdown.vue`, used by both rows) posting to `supplier.email.code` (`throttle:3,10`)
  and `supplier.email.verify` (`throttle:5,10`). An unverified email blocks nothing; the dashboard notice names
  whatever is still unverified.
- **Impersonation:** users with `impersonate users` can **Log in as** a supplier from `/admin/users`
  (`admin.users.impersonate`). Only suppliers can be impersonated: never yourself, a Super Admin, a staff account
  (one that can `view dashboard`) or a disabled account, and never while already impersonating.
  `App\Services\ImpersonationService` parks the admin's id in the session (`impersonator_id`) and signs the session
  in as the supplier. Every dashboard page then shows an amber bar with **Return to my account**
  (`impersonation.leave`), which signs the admin back in without a password. While impersonating, the
  `not-impersonating` middleware refuses actions taken in the user's name (sending and checking phone or email
  codes, changing a password or profile). Logging out ends the whole session. Start and stop dispatch
  `ImpersonationStarted` / `ImpersonationEnded`; for now `LogImpersonation` writes them to the log, and the planned
  audit log will listen to the same events.

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
- **Property leads** (`Modules/Lead`, `PropertyLead`): a visitor contacts the owner of a **listed** property from
  its page (`listing.leads.store`, `throttle:5,1`; any other property is a 404) in one of three ways, saved as
  `source` (`LeadSource`): the enquiry **form** (name, email, phone, message), or the **Call** / **WhatsApp**
  buttons, which ask for name and phone, save the lead, then hand back the owner's `tel:` / `wa.me` link
  (`flash.contact_url`). The owner's number is never in the page's props until a lead is saved, and the buttons are
  hidden when the owner has no phone (`owner_reachable`). Phones are stored in E.164.
  - Each lead stores `owner_id`, copied from the property when it arrives (who it was sent to), and a `status`
    (`LeadStatus`: new → contacted → closed). `PropertyLeadReceived` notifies the owner (mail + bell) and every user
    with `receive property lead notifications`.
  - Owners work their own leads at `/supplier/leads`, admins see every lead at `/admin/property-leads`. Both pages
    switch between a **Board** (Kanban: drag a card to change its status) and a **Table** (`?view=board|table`).
  - A lead's contact details and message are never edited; only its status changes (or an admin deletes it). A
    property that still has leads can't be deleted.
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
