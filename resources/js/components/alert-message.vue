<template>
    <div>
        <div v-for="message in messages" class="alert" :class="`alert-${message.type}`" v-text="message.text"></div>
    </div>
</template>

<script>
export default {
    props: {
        messages: {
            required: true,
            type: Array,
        },
    },
}
</script>
