import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'; //creates vue app //h creates the vue render function
import { createInertiaApp } from '@inertiajs/vue3'; //starts inertia integration with vue3
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', {
            eager: true,
        });

        return pages[`./Pages/${name}.vue`];
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
