<template>
    <div>
        <div
            v-if="notFoundAddresses.length"
            class="fw-bold"
        >Красные адреса не найдены
        </div>
        <div
            v-if="type === 'evening'"
            class="address-list-item"
        >Бригадная 77
        </div>
        <div
            v-for="(address, index) of routingAddresses"
            :key="index"
            class="address-list-item d-flex"
            :class="{'text-danger fw-bold': notFoundAddressesAssoc[address]}"
        >
            <OpenMapLinkBtns class="flex-shrink-0" :address="address" />
            <div>{{ address }}</div>
        </div>
        <div
            v-if="type === 'morning'"
            class="address-list-item"
        >Бригадная 77
        </div>
    </div>
</template>


<script>
import OpenMapLinkBtns from "./OpenMapLinkBtns";

export default {
    components: {OpenMapLinkBtns},
    props: {
        notFoundAddresses: Array,
        notFoundAddressesAssoc: Object,
        routingAddresses: Array,
        type: String,
    },
}
</script>


<style scoped>
.address-list-item:not(:last-of-type) {
    border-bottom: 1px solid lightgrey;
}
</style>
