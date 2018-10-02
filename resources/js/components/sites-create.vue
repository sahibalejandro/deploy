<template>
    <div>
        <h1>Create new site</h1>
        <form @submit.prevent="submit">
            <div>
                <label for="name">Site name:</label>
                <input type="text" id="name" v-model="site.name">
            </div>

            <div>
                <label for="repository">Repository:</label>
                <input type="text" id="repository" v-model="site.repository">
            </div>

            <button type="submit" :disabled="form.isPending">Save site</button>
        </form>
    </div>
</template>

<script>
import Form from 'form-object';

export default {
    data() {
        return {
            site: {name: null, repository: null},
            form: new Form()
        }
    },

    methods: {
        async submit() {
            const site = await this.form.post('/api/sites', this.site);
            this.$router.replace({name: 'sites.show', params: {id: site.id}});
        }
    }
}
</script>
