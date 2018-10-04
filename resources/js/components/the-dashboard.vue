<template>
    <div>
        <h2>Dashboard</h2>
        <div class="lead">
            This is the Dashboard, here you will find useful information.
        </div>
        <div class="mt-5">
            <sites-list />
        </div>
    </div>
</template>

<script>
import SitesList from './sites-list.vue';

export default {
    components: {SitesList},
}
</script>
