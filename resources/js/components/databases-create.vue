<template>
    <div>
        <h4 class="mb-5">New Database</h4>
        <form @submit.prevent="submit">
            <div class="form-group">
                <label for="name">Database name</label>
                <input v-model="database.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" maxlength="30">
                <div class="invalid-feedback" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
            </div>

            <div class="form-group">
                <label for="user">User name</label>
                <input v-model="database.user" type="text" id="user" class="form-control" :class="{ 'is-invalid': form.errors.has('user') }" maxlength="16">
                <div class="invalid-feedback" v-if="form.errors.has('user')" v-text="form.errors.get('user')"></div>
            </div>

            <div class="form-group">
                <label for="password">User password</label>
                <input v-model="database.password" type="password" id="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                <div class="invalid-feedback" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></div>
            </div>

            <div class="mt-5 text-right">
                <button type="submit" class="btn btn-primary" :disabled="form.isPending">Add New Database</button>
            </div>
        </form>
    </div>
</template>

<script>
import Form from 'form-object';

export default {
    data() {
        return {
            form: new Form(),
            database: {
                name: null,
                user: null,
                password: null,
            },
        };
    },

    methods: {
        async submit() {
            let response = await this.form.post('databases', this.database)
                .catch(e => console.warn(e.message));

            if (response) {
                this.$router.push({name: 'databases.index'});
            }
        }
    }
}
</script>
