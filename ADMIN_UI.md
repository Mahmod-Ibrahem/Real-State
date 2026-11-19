# Admin UI guide

Every admin screen (`/admin/*`: dashboard, CRUD pages, auth, profile) follows this guide. The style is
modelled on the Mr Egypt Tours admin (`D:\All Project\RealProject\Mr Egypt Tours\admin`), which itself
follows Chatwoot: a calm, dense, tool-like dashboard.

**Premium restraint, not AI-generated.** No gradients, glows, blobs, glassmorphism, tinted or coloured
shadows, icon-in-circle decorations, emoji, or bouncy/scale hover effects. Hierarchy comes from type
weight, spacing and a single accent colour, not decoration.

## 1. Bundle: the admin has no Bootstrap

- Admin pages use their own entry, `resources/js/admin.js` + `resources/css/admin.css`, rendered by
  `resources/views/admin.blade.php`. Bootstrap and the public theme SCSS are **never** imported there.
- All admin routes live under the `/admin` prefix. `HandleInertiaRequests` picks the `admin` root view and a
  separate asset version for `/admin/*`, so crossing between site and admin is always a full page load and
  the bundles never mix.
- Consequence: the Bootstrap-collision rules in `CLAUDE.md` do **not** apply to admin code. Use any Tailwind
  utility (`py-3`, `gap-4`, `shadow-sm`, `border`, …) normally. Still no `!` important hacks.
- Admin-only components live in `resources/js/Components/Admin/`. Never import them from public pages, and
  never import public (`Components/Users/*`) components into admin pages.

## 2. Tokens

| Token | Value | Use |
|---|---|---|
| Page background | `bg-[#F7F7F8]` | `<body>` of the admin layout only |
| Surface | `bg-white` | cards, sidebar, table, modals |
| Border | `border-slate-200` (dividers `divide-slate-100`/`200`) | every card and table |
| Radius | `rounded-xl` cards/panels · `rounded-lg` buttons, inputs, nav items · `rounded-md` icon buttons · `rounded-full` badges | |
| Accent | `indigo-600` (hover `indigo-700`, tint `indigo-50`, text on tint `indigo-700`) | primary buttons, active nav, focus rings, links. The only brand colour in the admin; to rebrand, re-point `indigo` in `tailwind.config.js`. |
| Text | `slate-900` headings/values · `slate-700` body · `slate-500` secondary · `slate-400` muted/icons | Never pure black. |
| Danger | `red-600` text, `red-50` hover tint | delete actions, errors |
| Status | badges: `bg-{green,amber,red,blue,slate}-50/100` + `text-{…}-700` | see `StatusBadge` |
| Font | Figtree (`font-sans`), `antialiased` | |
| Type scale | page title `text-lg font-semibold` · panel title `text-sm font-semibold` · body/table `text-sm` · labels/table headers `text-xs font-medium text-slate-500` · metric `text-3xl font-semibold tabular-nums` · nav group label `text-[11px] font-semibold uppercase tracking-wider text-slate-400` | |
| Shadow | none on cards (border only); `shadow-sm` on primary buttons; `shadow-lg` only on floating layers (dropdowns, toasts, modals) | |
| Focus | `focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30` (inputs: `focus:ring-2 focus:ring-indigo-500/15 focus:border-indigo-500`) | every interactive element |
| Motion | `transition-colors` (150–200ms). No scale/translate hovers. | |

## 3. Layout anatomy (`Layouts/Admin/AuthenticatedLayout.vue`)

```
┌ TopBar h-14: [☰ toggle] │ Group › Page · Action ............ [Language] [Profile ▾] ┐
├ SideBar w-60 white card (rounded-xl border, ml-3 mb-3)  │ main (px-4 md:px-6, scrolls) ┤
│  GROUP LABEL                                            │  PageHeader: title + desc    │
│  ▸ nav item (h-9, icon 18px slate-400)                  │              + #actions      │
│  ▸ active: bg-indigo-50 text-indigo-700 font-semibold   │  content (cards / tables)    │
└─────────────────────────────────────────────────────────┴──────────────────────────────┘
```

- Desktop (`lg`+): sidebar is a card beside the page; the toggle collapses it. Below `lg`: it is a drawer
  over the page with a `bg-slate-900/30` backdrop, closed after navigating.
- Navigation is data, not markup: `Components/Admin/navigation.js` exports `NAV_GROUPS`
  (`{label, items:[{label, route, icon}]}`) and `findNavItem(routeName)`. Sidebar, active state and the
  breadcrumb all read from it. A new admin section = one entry there.
- An item is active on all of its resource routes (`cities.index`, `cities.create`, `cities.edit`).
- Every page passes `title` (and optionally `description`) to the layout; the layout renders `<Head>`,
  the breadcrumb and the `PageHeader`. Page-level buttons go in the layout's `#actions` slot.

## 4. Components (`resources/js/Components/Admin/`)

Use these instead of hand-writing the markup. If a screen needs something they can't do, extend the
component (and this table), don't fork the styles.

| Component | Purpose |
|---|---|
| `ui/Button.vue` | `variant`: `primary` (indigo, shadow-sm) · `secondary` (white, slate-300 border) · `ghost` (no border) · `danger`. `size`: `sm` (h-8) · `md` (h-9). `href` renders an Inertia `Link`. `icon` prop for a leading heroicon. `loading` disables and shows a spinner. |
| `ui/Panel.vue` | White `rounded-xl border` card with optional header (`title`, `hint`, `#action` slot). Dashboard sections and form sections. |
| `ui/TableCard.vue` | List screens: toolbar (“Found **N** cities”, search box, create button) + `<table>` slot + empty state + `Pagination`. Paginated lists search on the server (reloads with `?search=`); unpaginated lists pass `client-search` + `v-model:search` and filter their rows with `Composables/useClientSearch.js`. `meta` accepts a resource's `meta` or a raw Laravel paginator (both have `links`, `from`, `to`, `total`). |
| `ui/TableHeadingCell.vue` | `<th>`: `px-4 py-2.5 text-xs font-medium text-slate-500 text-left whitespace-nowrap`. |
| `ui/TableCell.vue` | `<td>` with the recipe built in: `px-4 py-3 text-sm`; `variant`: `default` (slate-600) · `primary` (font-medium slate-900, the row's name) · `muted` (slate-500, ids/dates); `align="right"` for actions. |
| `ui/RowActions.vue` | Right-aligned icon buttons (h-8 w-8 `rounded-md`): edit (pencil, `Link`) and delete (trash, red hover). Always with `aria-label`/`title`. |
| `ui/Pagination.vue` | “Showing X–Y of Z” + prev/numbered/next buttons from a Laravel paginator `meta`. Hidden when there is one page. |
| `ui/StatusBadge.vue` | `tone`: `success`·`warning`·`danger`·`info`·`neutral`. `rounded-full px-2 py-0.5 text-xs font-medium`. |
| `ui/FormCard.vue` | Form screens: `Panel` with the fields in a `space-y-5` body and a footer bar (`border-t bg-slate-50/80`): Cancel (ghost, left) · submit (primary, right, “Saving…” while processing). |
| `ui/FormSection.vue` | Groups fields in long forms (property): title `text-sm font-semibold text-slate-900` + optional description, fields in `grid gap-5 sm:grid-cols-2`; sections after the first are separated by `border-t border-slate-200 pt-6`. Use when a form has more than ~6 fields. |
| `ui/TextField.vue` | Label above (`text-sm font-medium text-slate-700`), input `h-10 rounded-lg border-slate-300 px-3 text-sm`, hint `text-xs text-slate-500`, error `text-xs text-red-600` + red border. `type="textarea"` for multi-line. |
| `ui/SelectField.vue` | Same frame as `TextField` for a native `<select>`; `options` are `{id, name}`; `placeholder` is the disabled first option. Use for every single-choice field (cascading location selects included). |
| `ui/ToggleField.vue` | Switch for booleans (`is_published`), label to the right. |
| `ui/ImageField.vue` | File input with current-image preview (`rounded-lg border`, object-cover) and “Replace” affordance. |
| `ui/ImagesField.vue` | Multi-file variant: dashed drop area (`border-dashed border-slate-300 rounded-xl`) + removable `h-20 w-20 rounded-lg` thumbnails of the files picked so far. Property gallery uploads. |
| `ui/Toast.vue` + `Composables/useToast.js` | Top-right, white, `rounded-xl border shadow-lg`, green/red left icon. Every create/update/delete shows one. |
| PrimeVue `MultiSelect`, `Editor` | Only where a native control can't do the job (multi-choice features, rich-text description). Themed in `admin.js` with `primary` = Tailwind indigo so they match; wrap them in the same label/error frame as `TextField`. No other PrimeVue widgets. |
| `Composables/useResourceForm.js` | `saveResource(form, 'cities', 'city', {id, multipart})`: `post` to `.store` or `put` to `.update` (multipart updates are sent as POST + `_method=put` so files upload), then toasts “City created/updated”; server `errors.database` → error toast. Forms send `locale: page.props.locale` (the content language chosen in the top bar), never a hard-coded `'en'`. |
| `Composables/useConfirmDelete.js` | `confirmDelete(routeName, id, noun)`: confirm dialog → `router.delete` → success or error toast (server errors come back as `errors.database`). Tables never re-implement this. |

## 5. Page recipes

- **List page** = `AuthenticatedLayout title="Cities"` → `TableCard` → table rows:
  `divide-y divide-slate-100`, rows `hover:bg-slate-50`, cells `px-4 py-3 text-sm`; the primary column
  `font-medium text-slate-900`, others `text-slate-600`; ids as `#12 text-slate-500`; images as
  `h-10 w-14 rounded-md object-cover`; last column `RowActions`. Empty state text says what to do
  (“No cities yet. Add your first city.” / “No cities match ‘x’.”).
- **Form page** (create & edit share one `XForm.vue`) = layout with `title="New city"` / `"Edit city"` →
  `FormCard` (fills the content width, no `max-w-*` cap on forms or their wrappers) → fields. Related fields sit in a `grid gap-5 sm:grid-cols-2`.
  Cancel returns to the list. Validation errors render under each field from `form.errors`.
- **Dashboard** = `grid gap-4` of `Panel`s; numbers use the metric style; each metric links to its list.
- **Auth pages** (`GuestLayout`) = centered `max-w-sm` white card, logo above, same fields and buttons.
- **Logo** (`@/assets/images/logo.jpg`) is a wide wordmark: set a height only (`h-8 w-auto object-contain`);
  never force it into a square box or use `object-cover`, which crops it to "EIM".
- **Login is the exception** (owner's choice): it keeps the original template look, a full-screen photo
  (`assets/images/bg/01.jpg`) under a dark gradient, a centered white card with the logo, "Login" heading,
  inline "Remember me / Forgot password?" row, full-width button and "Don't have an account? Sign Up". Still
  Tailwind only and still uses `TextField`/`Button`, so validation and focus states match the rest of the admin.
  Don't "normalise" it back to `GuestLayout`.

## 6. States & behaviour

- Loading: Inertia's progress bar (indigo) for navigation; `Button loading` for submits. No full-page spinners.
- Empty and “no search results” states are always handled (see recipes).
- A server flash `error` (`->with('error', …)`) is shown as an error toast by the layout; controllers that
  refuse an action redirect with it instead of rendering a page with an error prop.
- Destructive actions always confirm (`useConfirmDelete`) and say what blocks them (e.g. “This city still has areas”).
- Every interactive element has a visible focus style and an accessible name; icon-only buttons need `aria-label`.
- Tables scroll horizontally inside their card on small screens (`overflow-x-auto`), never the page.
