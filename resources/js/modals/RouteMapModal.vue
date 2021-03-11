<template>
    <div class="modal fade" id="showRouteMapModal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <Map
                        v-if="drawMap"
                        :routing-addresses="routingAddresses"
                        :type="type"
                    />
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >Закрыть</button>
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
            if (
                _.isEqual(this.routingAddresses, addressesArr)
                && (this.type === type)
            ) {
                this.modal.show();
                return;
            }

            this.routingAddresses = addressesArr;
            this.type = type;

            this.drawMap = false;

            this.$nextTick(() => {
                this.modal.show();
                this.drawMap = true;
            });
        },
    },


    created() {
        this.$eventBus.$on('show-route-map-modal', this.showModal);
    },

    mounted() {
        this.modal = new bootstrap.Modal(document.getElementById('showRouteMapModal'), {
            backdrop: 'static',
        });
    },

    destroyed() {
        this.modal = null;
    }
}
</script>
