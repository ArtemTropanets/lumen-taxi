<template>
    <div style="height: clamp(400px, 65vh, 600px);">
        <div
            v-if="loader"
            class="map-loader d-flex justify-content-center align-items-center h-100"
        >
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>


        <l-map
            ref="map"
            v-if="showMap"
            :options="mapOptions"
            style="height: 100%"
        >
            <l-tile-layer
                :url="url"
            />
        </l-map>
    </div>
</template>


<script>
import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

import { LMap, LTileLayer } from "vue2-leaflet";
import 'leaflet-routing-machine';
import 'lrm-graphhopper';

export default {
    props: {
        routingAddresses: Array,
        type: String,
    },


    components: {
        LMap,
        LTileLayer,
    },


    data() {
        return {
            loader: false,

            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            office: L.latLng(46.4314814, 30.7405042),
            currentCenter: L.latLng(46.4314814, 30.7405042),
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: false,

            addresses: this.routingAddresses,
            waypoints: [],
            cachedWaypoints: {},
        };
    },


    methods: {
        showLongText() {
            this.showParagraph = !this.showParagraph;
        },


        async geolocateAddresses() {
            this.loader = true;

            const requests = [];
            this.addresses.forEach(address => {
                const geolocate = axios.get('https://nominatim.openstreetmap.org/search', {
                    params: {
                        q: `${address}, Одеса`,
                        country: 'Україна',
                        limit: 1,
                        format: 'json',
                        countrycodes: 'ua',
                    }
                });

                requests.push(geolocate);
            });

            let response;
            try {
                response = await Promise.all(requests);
            } catch (error) {
                this.loader = false;
            }

            response.forEach((resp, index) => {
                const address = resp.data[0];
                if (!address) {
                    alert(`Address not found: ${this.addresses[index]}`);
                    throw new Error('Adress not found');
                }

                this.waypoints.push(L.latLng(address.lat, address.lon));
            });

            if (this.waypoints.length <= 4) {
                if (this.type === 'morning') {
                    this.waypoints.push(this.office);
                } else {
                    this.waypoints.unshift(this.office);
                }
            }

            this.loader = false;
            this.showMap = true;
            this.$nextTick(() => {
                if (!this.$refs.map) return;

                this.drawRoute();
            });
        },

        drawRoute() {
            const control = L.Routing.control({
                waypoints: this.waypoints,
                routeWhileDragging: false,
                router: L.Routing.graphHopper('41b11856-c406-4f19-865a-d71c1a95330d'),
                show: false,
                autoRoute: false,
            }).addTo(this.$refs.map.mapObject);
            control.route();

/*            setTimeout(() => {
                control.setWaypoints(control.options.waypoints.slice(0, 2));
                control.route();
            }, 5000);*/
        },
    },


    mounted() {
        this.geolocateAddresses();
    },
};
</script>

<style>
.leaflet-default-shadow-path {
    display: none;
}

.map-loader .spinner-border {
    width: 6rem;
    height: 6rem;
}
</style>
