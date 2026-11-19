import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'; //creates vue app //h creates the vue render function
import { createInertiaApp } from '@inertiajs/vue3'; //starts inertia integration with vue3
import { ZiggyVue } from 'ziggy-js';

async function verifySession() {
    // no need to check the login or registration pages
    if (['/login', '/register', '/'].includes(window.location.pathname)) {
        return;
    }

    try {
        const response = await fetch('/session-check', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
            },
            cache: 'no-store',
        });

        //session has expired or user has logged out
        if (response.status === 401) {
            window.location.replace('/login');
        }
    } catch (error) {
        console.error('Unable to verify session:', error);
    }
}

//browser restores a page from its back-forward cache
// window.addEventListener('pageshow', (event) => {
//     if (event.persisted) {
//         verifySession();
//     }
// });

//browser navigates backwards or forwards
// window.addEventListener('popstate', () => {
//     verifySession();
// });

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
