<template>
    <div class="modal-overlay">
        <div class="modal-overlay-content">
            <a class="btn btn-light modal-overlay-close" href="#" @click.prevent="$emit('close')">Close</a>
            <slot></slot>
        </div>
    </div>
</template>

<style>
    .modal-overlay {
        animation-name: modal-overlay-fade-in;
        animation-duration: 500ms;
        animation-iteration-count: 1;
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 1;
    }

    .modal-overlay-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 4px;
        position: relative;
    }

    .modal-overlay-close {
        position: absolute;
        right: 2rem;
        top: 2rem;
    }

    @keyframes modal-overlay-fade-in {
        0% {
            background-color: rgba(0, 0, 0, 0);
        }

        100% {
            background-color: rgba(0, 0, 0, 0.5);
        }
    }
</style>
