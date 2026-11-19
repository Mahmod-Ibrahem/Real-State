// Admin bundle: pure Tailwind, no Bootstrap or public theme CSS. See ADMIN_UI.md.
import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import {definePreset} from '@primevue/themes';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';

// PrimeVue widgets (MultiSelect, Editor) use the admin accent instead of Aura's emerald.
const AdminPreset = definePreset(Aura, {
    semantic: {
        primary: Object.fromEntries(
            [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950].map(shade => [shade, `{indigo.${shade}}`])
        ),
    },
});

createInertiaApp({
    title: (title) => (title ? `${title} · Qeima Admin` : 'Qeima Admin'),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob(['./Pages/Admin/**/*.vue', './Pages/Auth/**/*.vue', './Pages/Profile/**/*.vue', './Pages/404.vue']),
        ),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {theme: {preset: AdminPreset, options: {darkModeSelector: false}}})
            .mount(el);
    },
    progress: {
        color: '#4f46e5', // indigo-600
    },
});
