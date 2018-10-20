<!--
Events:
    delete: When the resource is succesfully deleted.
    error: When the request to delete the resource fails.
-->
<template>
    <button type="button" class="btn btn-danger" @click.prevent="askConfirmation" :disabled="loading">
        <slot>Delete</slot>
    </button>
</template>

<script>
export default {
    props: {
        resource: {
            type: Object,
            required: true,
        },
        endpoint: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            loading: false,
        };
    },

    methods: {
        askConfirmation() {
            if (confirm(`All data will be lost! Are you sure to proceed?`)) {
                this.deleteResource();
            }
        },

        async deleteResource() {
            this.loading = true;

            let response = await axios
                .delete(`${this.endpoint}/${this.resource.id}`)
                .catch(error => {
                    this.$emit('error', error);
                });

            this.loading = false;

            if (response.status === 200) {
                this.$emit('delete');
            }
        }
    }
}
</script>
