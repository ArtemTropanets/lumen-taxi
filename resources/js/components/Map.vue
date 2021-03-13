<template>
    <div class="position-relative" style="height: clamp(400px, 65vh, 600px);">
        <div
            v-if="loader"
            class="map-loader position-absolute d-flex justify-content-center align-items-center h-100 w-100 bg-white"
            style="z-index: 10000"
        >
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>


        <div class="d-flex h-100">
            <div style="width: 280px; margin-right: 10px;">
                <AddressesListInMapModal
                    :notFoundAddresses="notFoundAddresses"
                    :notFoundAddressesAssoc="notFoundAddressesAssoc"
                    :routingAddresses="routingAddresses"
                    :type="type"
                />
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
    </div>
</template>


<script>
import L from 'leaflet';
import {LMap, LTileLayer} from "vue2-leaflet";
import 'leaflet-routing-machine';
import 'lrm-graphhopper';
import AddressesListInMapModal from "./AddressesListInMapModal";

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
    props: {
        routingAddresses: Array,
        type: String,
    },


    components: {
        AddressesListInMapModal,
        LMap,
        LTileLayer,
    },


    data() {
        return {
            loader: false,

            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: false,

            office: L.latLng(46.4314814, 30.7405042),

            routeControl: null,

            waypoints: [],
            cachedWaypointsByAddressName: {},
            markers: [],
            notFoundAddresses: [],
        };
    },


    computed: {
        notFoundAddressesAssoc() {
            return _.keyBy(this.notFoundAddresses);
        }
    },


    watch: {
        routingAddresses() {
            if (!this.showMap) return;
            this.updateRouteMap();
        },

        type() {
            if (!this.showMap) return;
            this.updateRouteMap();
        },
    },


    methods: {
        showLongText() {
            this.showParagraph = !this.showParagraph;
        },

        addOfficeAddressToWaypoints() {
            if (this.waypoints.length > 4) return;

            if (this.type === 'morning') {
                this.waypoints.push(this.office);
            } else {
                this.waypoints.unshift(this.office);
            }
            this.markers.push(L.marker([this.office.lat, this.office.lng]));
        },

        async geolocateAddresses() {
            this.loader = true;

            const geolocRequests = [];
            this.routingAddresses.forEach(address => {
                if (this.cachedWaypointsByAddressName[address]) {
                    geolocRequests.push(
                        Promise.resolve(this.cachedWaypointsByAddressName[address])
                    );
                    return;
                }

                const geolocate = axios.get('https://nominatim.openstreetmap.org/search', {
                    params: {
                        q: `${address}, Одеса`,
                        country: 'Україна',
                        limit: 1,
                        format: 'json',
                        countrycodes: 'ua',
                    }
                });

                geolocRequests.push(geolocate);
            });

            try {
                return await Promise.all(geolocRequests);
            } catch (error) {
                alert('Error by locating routingAddresses');
                return Promise.resolve(false);
            } finally {
                this.loader = false;
            }
        },

        async updateRouteMap() {
            const geolocs = await this.geolocateAddresses();
            if (!geolocs) return;

            this.waypoints = [];
            this.markers = [];
            this.notFoundAddresses = [];

            geolocs.forEach((resp, index) => {
                const address = resp.data[0];
                this.cachedWaypointsByAddressName[this.routingAddresses[index]] = {data: [address]};
                if (!address) {
                    this.notFoundAddresses.push(this.routingAddresses[index]);
                    return;
                }

                this.waypoints.push(L.latLng(address.lat, address.lon));
                this.markers.push(L.marker([address.lat, address.lon]));
            });

            this.addOfficeAddressToWaypoints();

            this.loader = false;

            if (!this.showMap) {
                this.showMap = true;
            }
            this.$nextTick(() => {
                this.drawRoute();
            });
        },

        drawRoute() {
            if (!this.routeControl) {
                this.routeControl = L.Routing.control({
                    waypoints: this.waypoints,
                    routeWhileDragging: false,
                    router: L.Routing.graphHopper('41b11856-c406-4f19-865a-d71c1a95330d'),
                    show: false,
                    autoRoute: false,
                }).addTo(this.$refs.map.mapObject);

            } else {
                this.routeControl.setWaypoints(this.waypoints);
                this.$refs.map.fitBounds(new L.featureGroup(this.markers).getBounds());
            }

            this.routeControl.route();
        },
    },


    mounted() {
        this.updateRouteMap();
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

