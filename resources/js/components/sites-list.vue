<template>
    <div>
        <h5>My sites</h5>
        <router-link class="btn btn-light" :to="{ name: 'sites.create' }">Add new site</router-link>
        <ul>
            <li v-for="site in sites">
                <router-link :to="{ name: 'sites.show', params: { id: site.id } }">{{ site.name }}</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sites: []
        }
    },

    mounted() {
        this.loadSites();
    },

    methods: {
        async loadSites() {
            const res = await axios.get('sites');
            this.sites = res.data;
        }
    }
}
</script>
