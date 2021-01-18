<template>
    <div class="container position-fixed top-0"
         style="z-index: 10000; left: 50%; transform: translateX(-50%)"
    >
        <div
            class="success-toast toast d-flex align-items-center position-absolute top-0 end-50 text-white bg-success border-0"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
            style="z-index: 10000; margin-top: 5px; margin-right: 20px; transform: translateX(50%)"
        >
            <div class="toast-body">Успешно</div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'SuccessToast',

    data() {
        return {
            toast: null,
            showing: false,
            timer: null,
        }
    },


    methods: {
        showSuccessToast() {
            if (this.showing) {
                clearTimeout(this.timer);
                this.toast.hide();

                setTimeout(() => {
                    this.showToastWithHideTimeout();
                }, 300);
                return;
            }

            this.showToastWithHideTimeout();
        },

        showToastWithHideTimeout() {
            this.toast.show();
            this.showing = true;
            this.timer = setTimeout(() => {
                this.toast.hide();
                this.showing = false;
            }, 1500);
        },
    },


    mounted() {
        this.toast = new bootstrap.Toast(
            document.querySelector('.success-toast'),
            {
                autohide: false,
            }
        );
        this.$eventBus.$on('show-success-toast', this.showSuccessToast);
    }
}
</script>
