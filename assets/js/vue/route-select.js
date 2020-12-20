import Vue from 'vue';
import VueI18n from 'vue-i18n';
import RouteSelect from './components/RouteSelect';

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: window.locale, // set locale
});

let routeselected = new Vue({
    i18n,
    render(h) {
        return h(RouteSelect);
    },
}).$mount('#app-route-select');
