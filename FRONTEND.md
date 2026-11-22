# Frontend: Tailwind only

Inertia + Vue 3 (`<script setup>`), Ziggy `route()`, Headless UI. **Tailwind 3 is the only styling system.**

## No Bootstrap

- **Never add Bootstrap**: no classes (`btn`, `row`, `col-*`, `d-flex`, `card`, `form-control`, `badge`, `nav-*`,
  `list-unstyled`, …), no Bootstrap JS/components, no new SCSS, no `@apply` component classes. Utilities go in the
  template.
- No other CSS or UI framework. Use Headless UI plus our own components only. **No PrimeVue.**
- Admin controls are our custom inputs in `Components/Admin/ui/`, never a native `<select>` or checkbox
  ([ADMIN_UI.md](ADMIN_UI.md) §4).

## Bundles

| Bundle | Entry | Status |
|---|---|---|
| Admin (all `/admin` routes) | `resources/js/admin.js` | Pure Tailwind, design rules in [ADMIN_UI.md](ADMIN_UI.md) |
| Public site | `resources/js/app.js` | Tailwind, but **legacy** Bootstrap 5 CSS/JS + template SCSS still load for old pages |

**Removing Bootstrap from the public bundle:** any public page or component you create or substantially edit is
Tailwind-only, and you convert its Bootstrap markup in the same change. When no template uses Bootstrap any more,
delete the `bootstrap` imports and the template SCSS from `app.js` and drop `bootstrap` from `package.json`.

## Bootstrap-collision rules (public bundle, until Bootstrap is removed)

Bootstrap CSS is still global on the public site, and its utilities are `!important`. Avoid Tailwind classes whose
names Bootstrap also defines with different values:

- Spacing scale **3, 4, 5** in `m-*`, `p-*`, `gap-*`, `ms-*`/`me-*`/`ps-*`/`pe-*` (Bootstrap: 1rem/1.5rem/3rem vs
  Tailwind 0.75/1/1.25rem). Values `0`, `1`, `2` and `6`+ are safe. Otherwise use arbitrary values (`py-[12px]`).
- `shadow`, `shadow-sm`, `shadow-lg` → use `shadow-[…]`. `rounded` → use `rounded-md`/`rounded-full`. `border` with a
  color class → use a side (`border-b`) or `border-[1px]`. Never `container` or `collapse`.
- Links: add `no-underline` and an explicit text color. Use `<span>`, not `<p>`, inside nav items (Bootstrap adds `p`
  margins). Reset lists with `m-0 p-0 list-none`.
- **No `!` (important) hacks.** If something needs `!`, it's colliding with Bootstrap, so fix it with the rules above.

## Rules for every bundle

- Brand primary is `sky-400` (`#38bdf8`, same as the theme's `$primary`).
- Mobile-first: base classes for mobile, `md:` for desktop. Check both.
- Arabic (RTL) is supported: prefer `start`/`end` (`inset-inline-start`, `text-start`, `ms-*`) over `left`/`right`
  for layout that should mirror.
- Accessibility: `aria-label` on icon-only buttons and links, `aria-expanded`/`aria-controls` on toggles,
  `aria-current="page"` on the active nav link, meaningful `alt` text.
- Hide actions the user lacks permission for, using the permission names shared by `HandleInertiaRequests`. Never
  check role names in Vue.
