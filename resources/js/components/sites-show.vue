<template>
    <div>
        <h3>Site {{ site.name }}</h3>
        <p>
            <strong>Repository:</strong>
            {{ site.repository }}
        </p>
        <p>
            <h4 class="border-bottom">Status</h4>
            <div v-if="status.installed" class="alert alert-success">
                Site is installed correctly.
            </div>
            <div v-if="status.install_error">
                <div class="alert alert-danger">
                    <strong>An error occurred during site installation:</strong>
                    <div class="text-monospace">{{ status.install_error}}</div>
                </div>
            </div>
            <div v-if="installationIsPending()" class="text-muted">
                Obtaining site status, please wait.
            </div>

            <h4 class="border-bottom">Reinstall</h4>
            <p> In case you need to reinstall the site, you can do it by pressing this scary button.<br/> </p>
            <div class="alert alert-warning">
                <div class="text-center">
                    <p>
                        <strong>All files will be deleted, this action cannot be undone!</strong>
                    </p>
                    <button class="btn btn-danger" type="button" @click="reinstall" :disabled="installationIsPending()">
                        Delete all the files and reinstall
                    </button>
                </div>
            </div>
        </p>
    </div>
</template>

<script>
export default {

    /**
     * Identifier for the setInterval(...) for monitoring the status.
     *
     * @var Number
     */
    monitorInterval: null,

    /**
     * Returns the component's data.
     *
     * @return Object
     */
    data() {
        return {
            /**
             * Site data.
             */
            site: { name: null, repository: null },

            /**
             * Site status.
             */
            status: {installed: false, install_error: null},
        }
    },

    /**
     * Run tasks when the component is created.
     *
     * @return void
     */
    created() {
        this.loadSite();
        this.startMonitoring();
    },

    methods: {
        /**
         * Get the site information from the API.
         *
         * @return void
         */
        async loadSite() {
            try {
                const res = await axios.get(`/api/sites/${this.$route.params.id}`);
                this.site = res.data;
            } catch (err) {
                if (err.response && err.response.status === 404) {
                    this.$router.push('/');
                }
            }
        },

        /**
         * Starts the status monitoring interval.
         *
         * @return void
         */
        startMonitoring() {
            // This is to avoid start multiple setInterval(...) by mistake.
            if (this.$options.monitorInterval) {
                return;
            }

            this.$options.monitorInterval = setInterval(this.getStatus, 3000);
        },

        /**
         * Stops the status monitoring interval.
         *
         * @return void
         */
        stopMonitoring() {
            clearInterval(this.$options.monitorInterval);
            this.$options.monitorInterval = null;
        },

        /**
         * Get the status from the API and trigger the update status method.
         *
         * @return void
         */
        async getStatus() {
            try {
                const res = await axios.get(`/api/sites/${this.site.id}/status`);
                this.updateStatus(res.data);
            } catch (err) {
                console.log('Unable to get site status', err);
            }
        },

        /**
         * Update and validate the current site status.
         *
         * @return void
         */
        updateStatus(status) {
            this.status = status;

            // Stop making requests for new status if the installation failed.
            if (status.install_error !== null) {
                this.stopMonitoring();
            }
        },

        /**
         * Trigger the reinstall process.
         *
         * @return void
         */
        reinstall() {
            this.status.installed = false;
            this.status.install_error = null;
            this.startMonitoring();
            console.log('Trigger the reinstall process.');
        },

        /**
         * Return true if the installation is pending.
         *
         * @return Boolean
         */
        installationIsPending() {
            // If the site is not installed and there is no installation
            // error then we can say that the installation is running.
            return !this.status.installed && !this.status.install_error;
        }
    }
}
</script>
