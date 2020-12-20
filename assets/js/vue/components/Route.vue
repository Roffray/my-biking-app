<template>
    <div>
        <h4>
            <a href="/?route=">{{ name }}</a>
        </h4>
        <i class="fa fa-trash-alt text-danger"></i>
        <div
            :id="mapId"
            class="map"
            style="height: 400px; width: 500px;"
        >
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Route',

    props: {
        'id': {
            'type': Number,
            'required': true,
        },
        'name': {
            'type': String,
            'required': true,
        },
        'path': {
            'type': String,
            'required': true,
        },
    },

    data() {
        return {
            route: null,
            mapId: `map-${this.$props.id}`,
        };
    },

    async created() {
        const response = await axios.get(`${this.$props.path}?properties[]=data`);
        this.$data.route = response.data.data;
        if (this.$data.route.coordinates) {
            const map = L.map(`map-${this.$props.id}`, {
                center: [
                    this.$data.route.inputWaypoints[0].latLng.lat,
                    this.$data.route.inputWaypoints[0].latLng.lng,
                ],
                zoom: 13,
                zoomControl: false,
                doubleClickZoom: false,
                // dragging: false,
            });

            L.tileLayer('https://tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey={accessToken}', {
                attribution: '&copy; Roffray | Maps &copy; <a href="https://www.thunderforest.com">Thunderforest</a>, data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18,
                accessToken: '00adbf50ad17476887004fde852de897', // TODO: use config var
            }).addTo(map);

            this.$data.route.inputWaypoints[0].latLng = new L.latLng(
                this.$data.route.inputWaypoints[0].latLng.lat,
                this.$data.route.inputWaypoints[0].latLng.lng,
                this.$data.route.inputWaypoints[0].latLng.alt,
            );
            this.$data.route.inputWaypoints[1].latLng = new L.latLng(
                this.$data.route.inputWaypoints[1].latLng.lat,
                this.$data.route.inputWaypoints[1].latLng.lng,
                this.$data.route.inputWaypoints[1].latLng.alt,
            );

            // console.log(this.$data.route);
            const plan = L.routing.plan({});
            const routingControl = L.routing.control({
                waypoints: [
                    this.$data.route.inputWaypoints[0].latLng,
                    this.$data.route.inputWaypoints[1].latLng,
                ],
                router: {},
                plan: plan,
            });
            routingControl.addTo(map);
            routingControl.hide();
            routingControl.options.autoRoute = false;
            // plan.setWaypoints(this.$data.route.inputWaypoints);
            routingControl.setAlternatives([this.$data.route]);
        }
    },

    // mounted() {
    //     axios.get(`${this.$props.path}?properties[]=data`)
    //         .then((response) => {
    //             this.$data.route = response.data.data;
    //
    //             if (this.$data.route) {
    //                 const map = L.map(`map-${this.$props.id}`, {
    //                     center: [48.856614, 2.3522219],
    //                     zoom: 13,
    //                     zoomControl: false,
    //                 });
    //
    //                     L.tileLayer('https://tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey={accessToken}', {
    //                     attribution: '&copy; Roffray | Maps &copy; <a href="https://www.thunderforest.com">Thunderforest</a>, data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    //                     maxZoom: 18,
    //                     accessToken: '00adbf50ad17476887004fde852de897', // TODO: use config var
    //                 }).addTo(map);
    //
    //                 console.log(this.$data.route);
    //             }
    //         });
    // },
};
</script>

<style scoped>
.map {
    height: 100px;
}
</style>
