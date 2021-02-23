<template>
    <div style="height: 500px;">
        <l-map
            ref="map"
            v-if="showMap"
            :zoom="zoom"
            :center="center"
            :options="mapOptions"
            style="height: 80%"
            @update:center="centerUpdate"
            @update:zoom="zoomUpdate"
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

import { LMap, LTileLayer, LMarker, LPopup, LTooltip } from "vue2-leaflet";
import 'leaflet-routing-machine';
import 'lrm-graphhopper';

export default {
    name: "Map",


    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
        LTooltip
    },


    data() {
        return {
            zoom: 16,
            center: L.latLng(46.4314814, 30.7405042),
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            office: L.latLng(46.4314814, 30.7405042),
            currentZoom: 11.5,
            currentCenter: L.latLng(46.4314814, 30.7405042),
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: false,

            addresses: [
                'Тополева 14',
                'Академика Королёва 44',
                'Костанди 203',
            ],
            waypoints: [],
        };
    },


    methods: {
        zoomUpdate(zoom) {
            this.currentZoom = zoom;
        },
        centerUpdate(center) {
            this.currentCenter = center;
        },
        showLongText() {
            this.showParagraph = !this.showParagraph;
        },


        async geolocateAddresses() {
            const requests = [];
            this.addresses.forEach(address => {
                const geolocate = axios.get('https://nominatim.openstreetmap.org/search', {
                    params: {
                        q: `${address}, Одесса`,
                        country: 'Украина',
                        limit: 1,
                        format: 'json',
                        countrycodes: 'ua',
                    }
                });

                requests.push(geolocate);
            });

            const response = await Promise.all(requests);
            response.forEach((resp, index) => {
                const address = resp.data[0];
                if (!address) {
                    alert(`Address not found: ${this.addresses[index]}`);
                    throw new Error('Adress not found');
                }

                this.waypoints.push(L.latLng(address.lat, address.lon));
            });

            this.waypoints.push(this.office);

            this.showMap = true;
            this.$nextTick(() => this.drawRoute());
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
</style>
