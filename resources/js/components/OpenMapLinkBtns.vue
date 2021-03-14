<template>
    <div class="d-inline-block">
        <button
            class="btn btn-secondary btn-sm text-dark bg-light"
            style="
                border-color: lightgrey;
                background: center url('./icons/2gis.png') no-repeat;
                width: 30px;
                height: 30px;
            "
            @click="openAddressInGoogleMaps"
        ></button>
        <button
            class="btn btn-secondary btn-sm text-dark bg-light"
            style="
                border-color: lightgrey;
                background: center url('./icons/gmaps.png') no-repeat;
                width: 30px;
                height: 30px;
            "
            @click="openAddressIn2Gis"
        ></button>
    </div>
</template>
<script>
export default {
    props: {
        address: String,
    },


    methods: {
        openAddressIn2Gis(event) {
            this.blurBtn(event.currentTarget);

            window.open(
                `https://www.google.com.ua/maps/search/${this.address} Одесса Украина`,
                '_blank'
            );
        },

        openAddressInGoogleMaps(event) {
            this.blurBtn(event.currentTarget);

            window.open(
                `https://2gis.ua/odessa/search/${this.address}`,
                '_blank'
            );
        },

        blurBtn(btn) {
            setTimeout(() => {
                btn.blur();
            }, 100);
        },
    },
}
</script>
