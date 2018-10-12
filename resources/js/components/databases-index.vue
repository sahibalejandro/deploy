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
                    <td>{{ database.name }}</td>
                    <td><code>{{ database.user }}</code></td>
                    <td>{{ database.created_at }}</td>
                    <td>
                        <button class="btn btn-sm btn-danger">&times;</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
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
        }
    }
}
</script>
