<template>
    <div>
        <div class="messages-wrapper">
            <div v-for="message in alertMessages" class="alert" :class="`alert-${message.type}`" v-text="message.text"></div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: mapState(['alertMessages']),
    }
</script>

<style scoped>
    .messages-wrapper {
        position: fixed;
        right: 20px;
        top: 20px;
        min-width: 345px;
    }
</style>
