<template>
    <div>
        <h4 class="mb-5">New Site</h4>
        <form @submit.prevent="submit">
            <div class="form-group">
                <label for="name">Site name</label>
                <input
                    v-model="site.name"
                    v-focus
                    type="text"
                    id="name"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                    maxlength="50"
                >
                <div
                    class="invalid-feedback"
                    v-if="form.errors.has('name')"
                    v-text="form.errors.get('name')"
                ></div>
            </div>

            <h5>Repository</h5>
            <div class="form-group">
                <label for="git_platform">Platform</label>
                <select v-model="site.git_platform" id="git_platform" class="form-control">
                    <option value="github">GitHub</option>
                    <option value="bitbucket">BitBucket</option>
                </select>
            </div>

            <div class="form-group">
                <label for="repository">Repository Name</label>
                <input
                    v-model="site.repository"
                    type="text"
                    id="repository"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('repository') }"
                    placeholder="vendor/repository"
                    maxlength="100"
                >
                <div
                    class="invalid-feedback"
                    v-if="form.errors.has('repository')"
                    v-text="form.errors.get('repository')"
                ></div>
            </div>

            <div class="mt-5 text-right">
                <c-button class="btn btn-primary" type="submit" :disabled="form.isPending">Add New Site</c-button>
            </div>
        </form>
    </div>
</template>

<script>
import Form from 'form-object';

export default {
    data() {
        return {
            site: {
                name: null,
                repository: null,
                git_platform: 'github',
            },
            form: new Form()
        }
    },

    methods: {
        async submit() {
            let site = await this.form.post('sites', this.site)
                .catch(error => console.warn(error.message));

            if (site) {
                this.$router.replace({name: 'sites.show', params: {id: site.id}});
            }
        }
    }
}
</script>
