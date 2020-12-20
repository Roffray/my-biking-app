import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Account from './components/Account';

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: window.locale, // set locale
    // messages, // set locale messages
});

new Vue({
    i18n,
    render(h) {
        return h(Account);
    },
}).$mount('#app-account');
