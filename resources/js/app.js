import './bootstrap';
import Toast from "vue-toastification";
import tawk from "@/plugins/tawk.js";
import "vue-toastification/dist/index.css";
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import 'bootstrap/dist/css/bootstrap.css';
import '@/assets/scss/style.scss'
import bootsratp from 'bootstrap/dist/js/bootstrap.bundle.js';
import '@/assets/libs/@iconscout/unicons/css/line.css';
import '@/assets/libs/@mdi/font/css/materialdesignicons.min.css';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import '@/assets/css/fonts.css'


createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            // Admin, auth and profile pages belong to the admin bundle (admin.js).
            import.meta.glob(['./Pages/**/*.vue', '!./Pages/Admin/**', '!./Pages/Auth/**', '!./Pages/Profile/**']),
        ),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(Toast)
            .use(tawk)
            .use(ZiggyVue)
            .mount(el)

    },
    progress: false,
});
