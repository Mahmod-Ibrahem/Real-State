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
- **URLs are kebab-case and plural**: `/admin/property-types`, `/admin/faqs`, never `/admin/propertyTypes`.
  Route **names** follow the URL with an `admin.` prefix: `route('admin.property-types.index')` (see
  CLAUDE.md, Routing; each module registers them inside `Route::admin()`). Admin resources have no `show` route, and
  modal-edited resources (§5) have no `create`/`edit` routes either: declare them with `->only([...])`.
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
- An item is active on all of its resource routes (`properties.index`, `properties.create`, `properties.edit`).
- Every page passes `title` (and optionally `description`) to the layout; the layout renders `<Head>`,
  the breadcrumb and the `PageHeader`. Page-level buttons go in the layout's `#actions` slot.

## 4. Components (`resources/js/Components/Admin/`)

Use these instead of hand-writing the markup. If a screen needs something they can't do, extend the
component (and this table), don't fork the styles.

**Custom inputs only.** The admin never renders a browser-native `<select>`, checkbox, radio or date picker,
and there is **no PrimeVue** (removed from both bundles). Choice controls are our own components, ported from
the Mr Egypt Tours admin's Chatwoot-style inputs (`admin/src/components/core/CustomSelect.vue`,
`CustomToggle.vue`, `shared/checkbox/Checkbox.vue`) and built on Headless UI for keyboard and screen-reader
support. Text, number, email and password fields stay real `<input>`/`<textarea>` elements, always through
`TextField`. Floating panels (dropdown lists) render through Headless UI's `Portal`, never Vue's `Teleport`:
`Modal` is a Headless `Dialog`, which makes everything outside it inert, and only a Headless `Portal` counts as
inside the dialog.

| Component | Purpose |
|---|---|
| `ui/Button.vue` | `variant`: `primary` (indigo, shadow-sm) · `secondary` (white, slate-300 border) · `ghost` (no border) · `danger`. `size`: `sm` (h-8) · `md` (h-9). `href` renders an Inertia `Link`. `icon` prop for a leading heroicon. `loading` disables and shows a spinner. |
| `ui/Panel.vue` | White `rounded-xl border` card with optional header (`title`, `hint`, `#action` slot). Dashboard sections and form sections. |
| `ui/TableCard.vue` | List screens: toolbar (“Found **N** cities”, search box, create button: a link with `create-href`, or with `creatable` a button emitting `create` for modal resources) + `<table>` slot + empty state + `Pagination`. Paginated lists search on the server (reloads with `?search=`); unpaginated lists pass `client-search` + `v-model:search` and filter their rows with `Composables/useClientSearch.js`. `meta` accepts a resource's `meta` or a raw Laravel paginator (both have `links`, `from`, `to`, `total`). |
| `ui/TableHeadingCell.vue` | `<th>`: `px-4 py-2.5 text-xs font-medium text-slate-500 text-left whitespace-nowrap`. |
| `ui/TableCell.vue` | `<td>` with the recipe built in: `px-4 py-3 text-sm`; `variant`: `default` (slate-600) · `primary` (font-medium slate-900, the row's name) · `muted` (slate-500, ids/dates); `align="right"` for actions. |
| `ui/RowActions.vue` | Right-aligned icon buttons (h-8 w-8 `rounded-md`): edit (pencil) and delete (trash, red hover). Edit is a `Link` when given `edit-href`, or a button emitting `edit` when given `editable` (modal resources). Always with `aria-label`/`title`. |
| `ui/Pagination.vue` | “Showing X–Y of Z” + prev/numbered/next buttons from a Laravel paginator `meta`. Hidden when there is one page. |
| `ui/StatusBadge.vue` | `tone`: `success`·`warning`·`danger`·`info`·`neutral`. `rounded-full px-2 py-0.5 text-xs font-medium`. |
| `ui/RowTitle.vue` | The name in a table's first column: `font-medium text-slate-900`, `hover:text-indigo-600`. `href` → Inertia `Link` to the edit page; otherwise a `<button>` emitting `click` (opens the edit modal). |
| `ui/ToggleButton.vue` | Inline two-value field in a table: looks like a `StatusBadge` of the current value plus a swap icon; clicking emits `toggle`. `loading` shows a spinner; `aria-pressed` and a `title` say what a click changes it to. Never a switch. |
| `ui/InlineSelect.vue` | Inline 3+-value field in a table: a Headless UI `Listbox` whose button is a `StatusBadge`-style pill (`h-7 rounded-full text-xs font-medium`, tinted by `tone`, chevron) and whose list is the same floating panel as `SelectField`; emits the new value; `loading` disables it and shows a spinner. |
| `ui/FormCard.vue` | Form screens: `Panel` with the fields in a `space-y-5` body and a footer bar (`border-t bg-slate-50/80`): Cancel (ghost, left) · submit (primary, right, “Saving…” while processing). |
| `ui/Modal.vue` | The only dialog shell (Headless UI `Dialog`: focus trap, Esc/backdrop close, nested dialogs). Backdrop `bg-slate-900/50`, panel `bg-white rounded-xl shadow-lg`, header title `text-base font-semibold` + optional description + close (×). `size`: `sm` (max-w-md) · `md` (max-w-lg) · `lg` (max-w-2xl) · `xl` (max-w-3xl). Fade + slight scale, 150–200ms. |
| `ui/FormModal.vue` | `Modal` + `<form>`: fields in a `space-y-5` body, footer bar identical to `FormCard` (Cancel · submit). Used by every modal-edited resource. Closing is blocked while saving. |
| `ui/FormSection.vue` | Groups fields in long forms (property): title `text-sm font-semibold text-slate-900` + optional description, fields in `grid gap-5 sm:grid-cols-2`; sections after the first are separated by `border-t border-slate-200 pt-6`. Use when a form has more than ~6 fields. |
| `ui/TextField.vue` | Label above (`text-sm font-medium text-slate-700`), input `h-10 rounded-lg border-slate-300 px-3 text-sm`, hint `text-xs text-slate-500`, error `text-xs text-red-600` + red border. `type="textarea"` for multi-line. |
| `ui/SelectField.vue` | Every choice field (single or multiple), same frame as `TextField`. A Headless UI `Listbox` (port of Mr Egypt Tours' `CustomSelect`): button `min-h-10 rounded-lg border-slate-300` showing the chosen label or a slate-400 `placeholder`, chevron-up-down at the right; the list is a floating `rounded-lg border shadow-lg` panel (`Portal`, `position: fixed`, opens upwards near the bottom of the screen, max-h-64 scroll), active row `bg-slate-100`, chosen row indigo check. `options` are `{id, name}` (`option-value` / `option-label` to change). `multiple`: `v-model` is an array, rows get indigo checkbox squares, the button shows chips (first `max-chips`, then “+N more”), and the list starts with “Clear selection”. `searchable` adds a filter box at the top of the list (use it for long lists such as features). Ids keep their type (numbers stay numbers). |
| `ui/ToggleField.vue` | Switch for booleans (`is_published`): Headless UI `Switch`, `h-5 w-9`, indigo when on; label and description to the right. |
| `ui/Checkbox.vue` | Chatwoot checkbox: `h-4 w-4 rounded` with an indigo fill and white tick drawn in SVG (`appearance-none`, so it looks the same in every browser); `v-model` boolean, the label in the default slot. For a single yes/no next to text (Login's “Remember me”); in forms prefer `ToggleField`. |
| `ui/RichTextField.vue` | Rich-text field (property description): Quill 2 used directly, same frame as `TextField`. The toolbar (bold, italic, underline, headings, bullet/numbered lists, link, clear formatting) sits in a `bg-slate-50` bar inside one `rounded-lg border-slate-300` box with the indigo focus ring; `v-model` is HTML (`''` when empty). Restyled in `admin.css`; no Quill theme colours leak in. |
| `ui/ImageField.vue` | Single image, Mr Egypt Tours style: a dashed `rounded-xl` box at the image's real `aspect-ratio` (“Click to upload · you can crop it next”); picking a file opens `ImageCropDialog`; the cropped result fills the box with a “Change image” hover overlay and an “Adjust crop” link. Emits the cropped `File` (JPEG). `aspect-ratio`: 4/3 for property and type covers, 1 for broker photos. |
| `ui/ImagesField.vue` | Gallery grid: `aspect-[4/3] rounded-lg` tiles + a dashed “Add photos” tile. Picked photos go through a crop queue (“Crop & next”, “Skip”, “Cancel all”, “2 of 5”); cropped ones show as dashed-indigo “New” tiles, removable before upload. `v-model` is the cropped `File[]`; `saved` (`[{id, url}]`) renders already-saved tiles first with a remove button (`@remove`). Thumbnails open `ImageLightbox`. |
| `ui/ImageCropDialog.vue` | `Modal` around `vue-advanced-cropper`: fixed aspect frame, rotate left/right, output capped at 1600px, JPEG 0.9. Only used through `ImageField`/`ImagesField`. |
| `ui/ImageLightbox.vue` | Full-screen preview (`bg-slate-900/85`, `object-contain`), closes on click or Esc. |
| Gallery | The property gallery lives **inside the property form** (`Properties/PropertyGallery.vue`, Media section), mirroring Mr Egypt Tours' `TourGalleryManager`. It has **no controller or routes of its own**: everything is saved by `PropertyController` with the property. New cropped photos go as `propertyImages[]`; removing a saved photo hides its tile and adds its id to `removed_image_ids[]` (“N to remove on save”, with Undo), and the server deletes those files on update. While saving, an indigo progress bar shows the upload (`form.progress`). |
| `ui/Toast.vue` + `Composables/useToast.js` | Top-right, white, `rounded-xl border shadow-lg`, green/red left icon. Every create/update/delete shows one. |
| `Composables/useResourceForm.js` | `saveResource(form, 'admin.cities', 'city', {id, multipart, onSuccess})`: `post` to `.store` or `put` to `.update` (multipart updates are sent as POST + `_method=put` so files upload), then toasts “City created/updated”; server `errors.database` → error toast. Forms send `locale: page.props.locale` (the content language chosen in the top bar), never a hard-coded `'en'`. `resetFormOnOpen(form, () => props.open, () => fields)` refills a modal form with the record being edited (or blanks) and clears errors each time it opens. |
| `Composables/useResourceModal.js` | List-page modal state: `{isOpen, record, openCreate(), openEdit(row), close()}`. `record` is `null` when creating. |
| `Composables/useConfirmDelete.js` | `confirmDelete(routeName, id, noun)`: confirm dialog → `router.delete` → success or error toast (server errors come back as `errors.database`). Tables never re-implement this. |

## 5. Page recipes

- **List page** = `AuthenticatedLayout title="Cities"` → `TableCard` → table rows:
  `divide-y divide-slate-100`, rows `hover:bg-slate-50`, cells `px-4 py-3 text-sm`; others `text-slate-600`;
  images as `h-10 w-14 rounded-md object-cover` (portraits `h-10 w-10 rounded-full`); last column
  `RowActions`. Empty state text says what to do (“No cities yet. Add your first city.” / “No cities match ‘x’.”).
  - **No id columns or `#12` labels** anywhere in admin tables: ids are internal. Refer to related records by
    name (a lead shows its property's title, not `#5`).
  - **The first column is the row's name**, rendered with `ui/RowTitle.vue`: it opens the row's edit page
    (`href`) or its edit modal (`@click`). A thumbnail may sit to its left inside the same cell; a secondary
    line (type, job title, email) sits under it in `text-xs text-slate-500`.
  - **Quick edits in the table**: a field with **two** values (visibility Published/Draft, purpose Sale/Rent)
    is a `ui/ToggleButton.vue` (a badge-styled button that flips to the other value on click, never a
    switch); a field with **three or more** values (property status) is a `ui/InlineSelect.vue` (a pill-shaped
    dropdown). Both save immediately through a small `PATCH` endpoint, show a spinner while saving and
    toast the result.
- **Modal create/edit** for small resources: property types, classifications, features, feature types,
  countries, cities, areas, FAQs, brokers. The list page owns a `useResourceModal()`; the toolbar's “Add …” button
  (`TableCard creatable @create`) calls `openCreate()`, `RowActions editable @edit` calls `openEdit(row)`; one
  `XForm.vue` renders `FormModal` (title “New city” / “Edit Cairo”). On success the modal closes and the list
  refreshes in place; on validation errors it stays open with the errors under the fields. The list
  controller passes the select options the form needs (e.g. `countries` for cities), and `store`/`update`
  `redirect()->back()` so page and search survive. The row resource must carry every field the form edits.
- **Form page** for big forms (properties, lead details): layout with `title="New property"` /
  `"Edit …"` → `FormCard` (fills the content width, no `max-w-*` cap on forms or their wrappers) → fields.
  Related fields sit in a `grid gap-5 sm:grid-cols-2`. Cancel returns to the list. Validation errors render
  under each field from `form.errors`.
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
