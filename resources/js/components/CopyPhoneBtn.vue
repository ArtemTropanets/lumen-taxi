<template>
    <button
        class="btn btn-secondary btn-sm text-dark bg-light"
        style="border-color: lightgrey"
        :disabled="!phone"
        @click="copyToClipboard"
        v-tooltip.left="`Скопировать <div>номер</div> <div style='max-width: 95px'>${phone}</div>`"
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
            v-else-if="phone"
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
        </svg>
        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
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
