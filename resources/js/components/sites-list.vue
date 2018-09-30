<template>
    <div>
        <h2>Sites</h2>

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
            const res = await axios.get('/api/sites');
            this.sites = res.data;
        }
    }
}
</script>
