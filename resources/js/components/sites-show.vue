<template>
    <div>
        <h1>
            Site {{ site.name }}
        </h1>
        <p>
            <strong>Repository:</strong>
            {{ site.repository }}
        </p>
        <p>
            <strong>Installed:</strong>
            {{ site.installed ? 'Yes' : 'No' }}
        </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            site: { name: null, repository: null }
        }
    },

    created() {
        this.loadSite();
    },

    methods: {
        async loadSite() {
            try {
                const res = await axios.get(`/api/sites/${this.$route.params.id}`);
                this.site = res.data;
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    this.$router.push('/');
                }
            }
        }
    }
}
</script>
