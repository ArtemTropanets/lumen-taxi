<template>
    <button
        class="btn btn-secondary btn-sm text-dark bg-light"
        style="border-color: lightgrey"
        :disabled="!phone"
        @click="copyToClipboard"
        v-tooltip.left="`Скопировать <div>номер</div> <div style='max-width: 90px'>${phone}</div>`"
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
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
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
