<template>
    <div>
        <div v-if="loading">Loading site information...</div>

        <div v-if="site">
            <h4>{{ site.name }}</h4>

            <!-- Site's information -->
            <code>{{ site.ssh_url }}</code>
            <hr>

            <!-- Site status -->
            <h5>Status</h5>

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

            <a href="#" @click.prevent="showingEnvFile = true" class="btn btn-light">Edit .env file</a>
            <portal to="modal-outlet" v-if="showingEnvFile">
                <modal-overlay @close="showingEnvFile = false">
                    <!-- Probably we should listen for an event when the contents change, but YAGNI -->
                    <env-file @saved="showingEnvFile = false" :site-id="site.id" :initial-contents="site.env_file_contents" />
                </modal-overlay>
            </portal>
        </div>
    </div>
</template>

<script>
import EnvFile from './env-file.vue';
import ModalOverlay from './modal-overlay.vue';

export default {

    components: {EnvFile, ModalOverlay},

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
            site: null,
            loading: true,
            showingEnvFile: false,
        };
    },

    /**
     * Run tasks when the component is created.
     *
     * @return void
     */
    created() {
        this.loadSiteData().then(() => {
            this.loading = false;
        });

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
