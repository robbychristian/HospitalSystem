/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// Imports
import Vue from "vue";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";

// Import Bootstrap an BootstrapVue CSS files (order is important)
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

require("./bootstrap");

window.Vue = require("vue").default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "test-component",
    require("./components/test/TestComponent.vue").default
);

// Parts
Vue.component(
    "sidebar",
    require("./components/parts/SidebarComponent.vue").default
);
Vue.component(
    "topbar",
    require("./components/parts/TopbarComponent.vue").default
);
Vue.component(
    "doctor-component",
    require("./components/parts/DoctorComponent.vue").default
);
Vue.component(
    "announcement-component",
    require("./components/parts/AnnouncementComponent.vue").default
);

// Pages
Vue.component(
    "patient-component",
    require("./components/pages/PatientComponent.vue").default
);
Vue.component(
    "email-verification",
    require("./components/pages/EmailVerificationComponent.vue").default
);
Vue.component(
    "calendar-component",
    require("./components/pages/CalendarComponent.vue").default
);
Vue.component(
    "inquiry-component",
    require("./components/pages/InquiryComponent.vue").default
);
Vue.component(
    "login-component",
    require("./components/pages/LoginComponent.vue").default
);
Vue.component(
    "dashboard-component",
    require("./components/pages/DashboardComponent.vue").default
);

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
});
