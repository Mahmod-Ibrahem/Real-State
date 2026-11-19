// Admin bundle: pure Tailwind, no Bootstrap or public theme CSS. See ADMIN_UI.md.
import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';

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
            .mount(el);
    },
    progress: {
        color: '#4f46e5', // indigo-600
    },
});
