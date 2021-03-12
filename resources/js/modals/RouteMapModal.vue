<template>
    <div class="modal fade" id="showRouteMapModal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    На карте отображаются максимум 5 адресов
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <Map
                        v-if="drawMap"
                        :routing-addresses="routingAddresses"
                        :type="type"
                    />
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import Map from "../components/Map";

export default {
    components: {
        Map
    },


    data() {
        return {
            modal: null,
            drawMap: false,
            routingAddresses: [],
            type: '',
        }
    },


    methods: {
        showModal(addressesArr, type) {
            if (!_.isEqual(this.routingAddresses, addressesArr)) {
                this.routingAddresses = addressesArr;
            }
            if (this.type !== type) {
                this.type = type;
            }

            this.modal.show();
            if (!this.drawMap) {
                this.drawMap = true;
            }
        },
    },


    created() {
        this.$eventBus.$on('show-route-map-modal', this.showModal);
    },

    mounted() {
        this.modal = new bootstrap.Modal(document.getElementById('showRouteMapModal'));
    },

    destroyed() {
        this.modal = null;
    }
}
</script>
