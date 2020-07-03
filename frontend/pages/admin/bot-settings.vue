<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>Bot settings</h4>
                <hr>
                <h5>Work mode:</h5>
                <b-form-select v-model="selectedWorkMode" :options="options"></b-form-select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selectedWorkMode: null,
                options: [
                    { value: null, text: 'Please select an option' },
                    { value: 1, text: 'Webhook mode' },
                    { value: 2, text: 'getUpdates mode', disabled: true},
                ]
            }
        }
    }
</script>

<style scoped>

</style>