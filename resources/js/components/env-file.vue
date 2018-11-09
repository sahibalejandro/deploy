<template>
    <div>
        <div class="form-group">
            <textarea v-model="env.contents" class="form-control" rows="10" placeholder="APP_NAME=Acme"></textarea>
        </div>
        <div class="text-right">
            <button type="button" @click="save" class="btn btn-primary btn-sm">Save .env file</button>
        </div>
    </div>
</template>

<script>
import Form from 'form-object';

export default {
    props: {
        siteId: {
            required: true,
            type: Number,
        },
        initialContents: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            env: {
                contents: this.initialContents
            },
            form: new Form(),
        };
    },

    methods: {
        save() {
            this.form.post(`sites/${this.siteId}/env`, this.env)
                .catch(error => console.warn(error));
        }
    }
}
</script>
