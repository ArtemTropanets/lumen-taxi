<template>
    <div style="height: 500px;">
        <l-map
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
            <l-marker :lat-lng="office"></l-marker>
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
            showMap: true
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
    }
};
</script>

<style>
.leaflet-default-shadow-path {
    display: none;
}
</style>
