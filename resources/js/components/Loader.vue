<template>
    <div
        class="loader overlay"
        :class="{active: loading}"
    >
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
        };
    },

    methods: {
        showLoader(show = true) {
            this.loading = show;
        }
    },

    created() {
        this.$eventBus.$on('show-loader', this.showLoader);
    }
}
</script>
<style>
    .overlay {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        width: 100vw;
        height: 100vh;
        background-color: rgba(128, 128, 128, 0.5);
        opacity: 0;
        z-index: 10000;
        top: 0;
        left: 0;
        transition: .3s cubic-bezier(.25,.8,.5,1),z-index 1ms;
        pointer-events: none;
    }

    .overlay.active {
        opacity: 0.6;
        pointer-events: auto;
        transition: .3s cubic-bezier(.25,.8,.5,1),z-index 1ms;
    }

    .loader .spinner-border {
        width: 6rem;
        height: 6rem;
    }
</style>
