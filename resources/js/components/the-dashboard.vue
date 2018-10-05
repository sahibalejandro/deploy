<template>
    <div>
        <h4>Dashboard</h4>
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
