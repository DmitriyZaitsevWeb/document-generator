<template>
    <card :title="`Create Category`">
        <form @submit.prevent="create" @keydown="form.onKeydown($event)">
            <alert-success :form="form" :message="'Category created'"></alert-success>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Title</label>
                <div class="col-md-7">
                    <input v-model="form.title" type="text" name="title" class="form-control"
                           :class="{ 'is-invalid': form.errors.has('title') }" required @input="onTitleChange">
                    <has-error :form="form" field="title"></has-error>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Guid</label>
                <div class="col-md-7">
                    <input v-model="form.guid" type="text" name="guid" class="form-control"
                           :class="{ 'is-invalid': form.errors.has('guid') }" required>
                    <has-error :form="form" field="guid"></has-error>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Description</label>
                <div class="col-md-7">
          <textarea v-model="form.description" name="description" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                    <has-error :form="form" field="description"></has-error>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-9 ml-md-auto">
                    <v-button type="success" :loading="form.busy">Create</v-button>
                </div>
            </div>
        </form>
    </card>
</template>

<script>
    import Form from 'vform'
    import VButton from "../../../components/Button";

    export default {
        components: {VButton},
        data: () => ({
            form: new Form({
                title: '',
                guid: '',
                description: '',
            })
        }),

        computed: {
            guid(){
                return this.form.title.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text
            }
        },

        methods: {
            onTitleChange(){
                this.form.guid = this.guid;
            },
            async create () {
                await this.form.post('/api/categories/');
                this.form.reset();
                this.$router.go(-1);
            }
        }
    }
</script>
