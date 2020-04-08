<template>
    <div class="vld-parent">
        <vue-element-loading
            :active="isLoading"
            spinner="line-scale"
            color="#5c4ac7"

        />
    </div>
</template>

<script>
    import VueElementLoading from 'vue-element-loading'

    export default {
        components: {
            VueElementLoading
        },
        data() {
            return {
                fullPage: true,
                isLoading: false
            }
        },
        methods: {
            onCancel() {

            }
        },
        created() {
            Event.listen('show-simple-spinner', (status) => {
                this.isLoading = status;
            });
        }
    }
</script>

<style>
    .velmld-spinner[data-v-1a4f1fc2] {
        top: 160px !important;
    }
</style>
