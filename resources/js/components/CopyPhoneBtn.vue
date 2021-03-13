<template>
    <button
        type="button"
        class="btn btn-secondary btn-sm text-dark bg-light"
        style="border-color: lightgrey"
        :disabled="!phone"
        @click="copyToClipboard"
        v-tooltip.left="phone ? `Скопировать <div style='max-width: 90px'>${phone}</div>` : ''"
    >
        <svg
            v-if="copiedIcon"
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check2-circle"
            viewBox="0 0 16 16">
            <path
                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
            <path
                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
        </svg>
        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone"
            viewBox="0 0 16 16">
            <path
                d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
            <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
        </svg>
    </button>
</template>
<script>
export default {
    props: {
        phone: String,
    },


    data() {
        return {
            copiedIcon: false,
        };
    },


    methods: {
        copyToClipboard(event) {
            const btn = event.currentTarget;
            setTimeout(() => {
                btn.blur();
            }, 100);

            if (!navigator.clipboard) {
                alert('Браузер не поддерживает');
                return;
            }

            navigator.clipboard.writeText(this.phone)
                .then(() => {
                    this.copiedIcon = true;

                    setTimeout(() => {
                        this.copiedIcon = false;
                    }, 1000);
                });
        },
    },
}
</script>
