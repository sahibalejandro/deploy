<template>
    <div>
        <h4>Databases</h4>
        <div class="lead mb-5">Create and manage databases.</div>

        <router-link to="/databases/create" class="btn btn-light mb-5">Add database</router-link>

        <table class="table">
            <thead>
                <tr>
                    <th>Database</th>
                    <th>User</th>
                    <th>Created at</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="database in databases">
                    <td><code class="text-success">{{ database.name }}</code></td>
                    <td><code>{{ database.user }}</code></td>
                    <td>{{ database.created_at }}</td>
                    <td>
                        <delete-resource
                            :resource="database"
                            endpoint="databases"
                            class="btn-sm"
                            @delete="removeFromList(database)"
                        ></delete-resource>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import DeleteResource from './utils/delete-resource.vue';

export default {
    components: {DeleteResource},

    data() {
        return {
            databases: [],
        };
    },

    created() {
        this.loadDatabasesData();
    },

    methods: {
        async loadDatabasesData() {
            let {data} = await axios.get('databases');
            this.databases = data;
        },

        removeFromList(database) {
            this.databases = this.databases.filter(d => d.id !== database.id);
        },
    }
}
</script>
