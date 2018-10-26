<template>
    <div>
        <div class="form-group">
            <textarea v-model="content" id="content" class="form-control" rows="10" placeholder="APP_NAME=Acme"></textarea>
        </div>
        <div class="text-right">
            <button class="btn btn-primary btn-sm">Save .env file</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        siteId: {
            required: true,
            type: Number,
        },
        initialContent: {
            required: true,
            type: String,
        },
    },

    data() {
        console.log('initialContent=', this.initialContent);
        return {
            content: this.initialContent,
        };
    },
}
</script>
