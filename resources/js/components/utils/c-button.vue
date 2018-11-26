<template>
    <button :type="type" :disabled="disabled" @click="$emit('click')">
        <slot></slot>
        <div class="spinner"></div>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: "button"
            },
            disabled: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="scss" scoped>
    button {
        position: relative;
    }

    button:disabled {
        color: transparent;

        .spinner {
            display: block;
        }
    }

    .spinner {
        animation: c-button-spinner 750ms infinite linear;
        border: 2px solid white;
        border-radius: 50%;
        border-top-color: transparent;
        display: none;
        margin-top: -10px;
        margin-left: -10px;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
    }

    @keyframes c-button-spinner {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
