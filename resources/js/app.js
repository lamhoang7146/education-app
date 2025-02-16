import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp,Link,Head } from '@inertiajs/vue3'
import Layout from './Layouts/Layout.vue';
import {setThemeOnLoad} from "./theme.js";
import {ZiggyVue} from 'ziggy-js'
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || Layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("Link", Link)
            .component("Head", Head)
            .mount(el)
    },
})
setThemeOnLoad()
