<template>
    <div
        id="route-select-group"
        class="form-group"
    >
        <label
            class="required"
            for="route-select"
        >
            {{ $t('label') }}
        </label>
<!--        <select-->
<!--            id="route-select"-->
<!--            v-model="selected"-->
<!--            name="route"-->
<!--            class="form-control mx-sm-3"-->
<!--            @change="change"-->
<!--        >-->
<!--            <option-->
<!--                v-for="route in routes"-->
<!--                :value="route['@id']"-->
<!--                :key="route['@id']"-->
<!--            >-->
<!--                {{ route.name }}-->
<!--            </option>-->
<!--        </select>-->
        <v-select
            id="route-select"
            class="mx-sm-3"
            :options="routes"
            label="name"
            :reduce="route => route['@id']"
            @search="search"
            @input="change"
        >
        </v-select>
    </div>
</template>

<script>
import axios from 'axios';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: 'RouteSelect',

    components: {
        vSelect,
        // 'v-select': vSelect,
    },

    data() {
        return {
            routes: [],
            selected: null,
        };
    },

    created() {
        axios.get(`/api/users/${window.userid}/routes.jsonld?properties[]=name`)
            .then((response) => {
                this.$data.routes = response.data['hydra:member'];
            });
    },

    methods: {
        change(selected) {
            this.$data.selected = selected;
            const routeSelectedEvent = new CustomEvent('route-selected', { 'detail': this.$data.selected });
            window.dispatchEvent(routeSelectedEvent);
        },
        search(filter, loading) {
            loading();
            axios.get(`/api/users/${window.userid}/routes.jsonld?properties[]=name&name=${filter}`)
                .then((response) => {
                    this.$data.routes = response.data['hydra:member'];
                })
                .finally(() => {
                    loading();
                });
        },
    },
};
</script>

<style scoped>
    #route-select-group {
        width: 100%;
    }
    #route-select {
        width: 40%;
    }
</style>

<i18n lang="yaml">
    en:
        label: Route name
    fr:
        label: Nom du parcours
</i18n>
