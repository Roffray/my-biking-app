<template>
    <div>
        <Route
            v-for="route in routes"
            :key="route['@id']"
            :id="route.id"
            :path="route['@id']"
            :name="route.name"
        >
        </Route>
    </div>
</template>

<script>
import axios from 'axios';
import Route from './Route';

import 'leaflet/dist/leaflet.js';
import 'leaflet-routing-machine/dist/leaflet-routing-machine.js';

export default {
    name: 'Routes',
    components: { Route },
    data() {
        return {
            routes: [],
        };
    },

    created() {
        axios.get(`/api/users/${window.userid}/routes.jsonld?properties[]=name&properties[]=id`)
            .then((response) => {
                this.$data.routes = response.data['hydra:member'];
            });
    },
};
</script>

<!--<style lang="css">-->
<!--    @import '../../../../node_modules/leaflet/dist/leaflet.css';-->
<!--    @import '../../../../node_modules/leaflet-routing-machine/dist/leaflet-routing-machine.css';-->
<!--</style>-->

<style scoped>
    @import '../../../../node_modules/leaflet/dist/leaflet.css';
    @import '../../../../node_modules/leaflet-routing-machine/dist/leaflet-routing-machine.css';
</style>
