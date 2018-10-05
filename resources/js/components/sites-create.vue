<template>
    <div>
        <h4>Add new site</h4>
        <form @submit.prevent="submit">
            <div class="form-group">
                <label for="name">Site name:</label>
                <input class="form-control" type="text" id="name" v-model="site.name">
            </div>

            <div class="form-group">
                <label for="repository">Repository:</label>
                <input class="form-control" type="text" id="repository" v-model="site.repository">
                <small class="form-text text-muted">
                    The url to clone over SSH, for example:
                    <span class="text-monospace">git@github.com:user/repository.git</span>
                </small>
            </div>

            <div class="mt-3 text-center">
                <button class="btn btn-primary" type="submit" :disabled="form.isPending">Save site</button>
            </div>
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
