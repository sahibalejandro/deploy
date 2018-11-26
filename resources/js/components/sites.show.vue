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

            <!-- Buttons to show/hide different sections of the form -->
            <a href="#" @click.prevent="display('deploymentScript')" class="btn btn-light">Deployment script</a>
            <a href="#" @click.prevent="display('envFileContents')" class="btn btn-light">Env file</a>

            <!-- Modal to edit the env file -->
            <portal to="modal-outlet" v-if="isVisible('envFileContents')">
                <modal-overlay @close="hide('envFileContents')">
                    <h4 class="mb-4">Env File</h4>
                    <div class="form-group">
                        <textarea v-model="envFileContentsDraft" class="form-control text-monospace" cols="80" rows="10"></textarea>
                    </div>
                    <div class="text-right">
                        <c-button @click="updateEnvFileContents" class="btn btn-primary" :disabled="form.isPending">
                            Update
                        </c-button>
                    </div>
                </modal-overlay>
            </portal>

            <!-- Modal to edit the deployment script -->
            <portal to="modal-outlet" v-if="isVisible('deploymentScript')">
                <modal-overlay @close="hide('deploymentScript')">
                    <h4 class="mb-4">Deployment Script</h4>
                    <div class="form-group">
                        <textarea v-model="deploymentScriptDraft" class="form-control text-monospace" cols="80" rows="10"></textarea>
                    </div>
                    <div class="text-right">
                        <c-button @click="updateDeploymentScript" class="btn btn-primary" type="button" :disabled="form.isPending">
                            Update
                        </c-button>
                    </div>
                </modal-overlay>
            </portal>

        </div><!-- if (site) -->
    </div><!-- component -->
</template>

<script>
import ModalOverlay from './modal-overlay.vue';
import Form from 'form-object';

export default {

    components: {ModalOverlay},

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
            form: new Form(),
            showingEnvFile: false,

            visibleFormSections: {
                envFileContents: false,
                deploymentScript: false,
            },

            envFileContentsDraft: '',
            deploymentScriptDraft: '',
        };
    },

    /**
     * Run tasks when the component is created.
     *
     * @return void
     */
    created() {
        this.loadSiteData().then(data => {
            this.site = data;
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
                return data;
            } catch (err) {
                this.alert('That site does not exists.', 'danger');
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
        async refreshData() {
            let site = await this.loadSiteData();

            // Update only the properties that represents the site's status.
            this.site.installed = site.installed;
            this.site.install_error = site.install_error;

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
        },

        /**
         * Send the request to update the site's information.
         */
        async update() {
            await this.form.patch(`/sites/${this.site.id}`, this.site);
        },

        /**
         * Handles the logic to update the env file contents.
         */
        async updateEnvFileContents() {
            try {
                await this.form.post(`sites/${this.site.id}/env`, {contents: this.envFileContentsDraft});
                this.site.env_file_contents = this.envFileContentsDraft;
                this.alert('Env file contents updated.');
                this.hide('envFileContents')
            } catch (error) {
                this.alert(error.toString(), 'danger');
            }
        },

        /**
         * Handles the logic to update the deployment script.
         */
        async updateDeploymentScript() {
            let unmodifiedDeploymentScript = this.site.deployment_script;

            // Mutate the state so the update() method sends the updated values.
            // If the request fails then we will have to revert this change.
            this.site.deployment_script = this.deploymentScriptDraft;

            try {
                await this.update();
                this.alert('Deployment script updated.');
                this.hide('deploymentScript');
            } catch (error) {
                this.alert(error.toString(), 'danger');

                // When the error contains a response object it means the request
                // failed and we need to revert the changes made to the state.
                if (error.response) {
                    this.site.deployment_script = unmodifiedDeploymentScript;
                }
            }
        },

        /**
         * Displays the form section with the given name.
         *
         * @param  {String} formSection
         */
        display(formSection) {
            this.visibleFormSections[formSection] = true;
            switch (formSection) {
                case 'deploymentScript':
                    this.deploymentScriptDraft = this.site.deployment_script;
                    break;
                case 'envFileContents':
                    this.envFileContentsDraft = this.site.env_file_contents;
                    break;
            }
        },

        /**
         * Hides the form section with the given name.
         *
         * @param  {String} formSection
         */
        hide(formSection) {
            this.visibleFormSections[formSection] = false;
        },

        /**
         * Checks if the form section with the given name is visible.
         *
         * @param  {String} formSection
         * @return {Boolean}
         */
        isVisible(formSection) {
            return this.visibleFormSections[formSection];
        }
    }
}
</script>
