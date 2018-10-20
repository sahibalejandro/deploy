<template>
    <div>
        <h4>Site {{ site.name }}</h4>

        <!-- Site's information -->
        <strong>Repository:</strong>
        {{ site.repository }}

        <!-- Site status -->
        <h5 class="border-bottom">Status</h5>

        <!-- Status: Installed -->
        <div v-if="site.installed" class="alert alert-success">
            Site is installed correctly.
        </div>

        <!-- Status: Installation failed -->
        <div v-if="site.install_error">
            <div class="alert alert-danger">
                An error occurred during site installation!
            </div>
            <div class="mb-2"><strong>Output:</strong></div>
            <pre class="pre-scrollable border rounded text-secondary bg-light p-3">{{ site.install_error }}</pre>
        </div>

        <!-- Status: Installation running -->
        <div v-if="installationIsPending()" class="text-muted">
            Installing the site, this can take a while.
        </div>
    </div>
</template>

<script>
export default {

    /**
     * Identifier for the setInterval(...) for monitoring the status.
     *
     * @var Number
     */
    refreshInterval: null,

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
            site: {
                name: null,
                repository: null,
                installed: null,
                install_error: null,
            },
        };
    },

    /**
     * Run tasks when the component is created.
     *
     * @return void
     */
    created() {
        this.loadSiteData();
        this.startRefreshing();
    },

    beforeDestroy() {
        this.stopRefreshing();
    },

    methods: {
        /**
         * Get the site information from the API.
         *
         * @return void
         */
        async loadSiteData() {
            try {
                let {data} = await axios.get(`sites/${this.$route.params.id}`);
                this.site = data;
            } catch (err) {
                // TODO: Display an alert to notify that the site doesn't exists.
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
        startRefreshing() {
            // This is to avoid start multiple setInterval(...) by mistake.
            if (this.$options.refreshInterval) {
                return;
            }

            this.$options.refreshInterval = setInterval(this.refreshData, 3000);
        },

        /**
         * Stops the status monitoring interval.
         *
         * @return void
         */
        stopRefreshing() {
            clearInterval(this.$options.refreshInterval);
            this.$options.refreshInterval = null;
        },

        /**
         * Get the site information from the API and trigger the update status
         * method.
         *
         * @return void
         */
        refreshData() {
            this.loadSiteData();

            // Stop making requests for new status if the installation failed.
            if (this.site.install_error !== null) {
                this.stopRefreshing();
            }
        },

        /**
         * Return true if the installation is pending.
         *
         * @return Boolean
         */
        installationIsPending() {
            // If the site is not installed and there is no installation
            // error then we can say that the installation is running.
            return !this.site.installed && !this.site.install_error;
        }
    }
}
</script>
