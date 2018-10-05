<template>
    <div>
        <h4>Databases</h4>
        <div class="lead">Create and manage databases.</div>

        <router-link to="/databases/create" class="btn btn-light">Add database</router-link>

        <table class="table">
            <thead>
                <tr>
                    <th>Database</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="database in databases">
                    <td>{{ database.name }}</td>
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
            let {data} = await axios.get('/api/databases');
            this.databases = data;
        }
    }
}
</script>
