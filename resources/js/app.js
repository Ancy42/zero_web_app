import "./bootstrap";

import { createApp } from "vue";

import App from "./App.vue";

import router from "./router";

import VueAwesomePaginate from "vue-awesome-paginate";
import "vue-awesome-paginate/dist/style.css";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

import localization from "./localization";
import ChatBox from "./pages/Messages.vue";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp(App);

app.component("v-select", VueSelect);
localization.fetchLocalizationData();

app.use(localization.i18n);
app.use(pinia);
app.use(router);
app.use(VueAwesomePaginate);
app.use(Toast);
app.component("chat-box", ChatBox);
app.mount("#chat-app");
app.mount("#app");
