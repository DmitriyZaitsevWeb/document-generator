<template>
    <card :title='`Category "${form.title}"`'>
        <form @submit.prevent="update" @keydown="form.onKeydown($event)" enctype="application/x-www-form-urlencoded">
            <alert-success :form="form" :message="'Category updated'"></alert-success>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Title</label>
                <div class="col-md-7">
                    <input v-model="form.title" type="text" name="title" class="form-control"
                           :class="{ 'is-invalid': form.errors.has('title') }" required @keyup="onTitleChange">
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
                    <v-button type="success" :loading="form.busy">Update</v-button>
                </div>
            </div>
        </form>
    </card>
</template>

<script>
    import Form from 'vform'
    import Categories from '../../../models/categories'

    export default {
        data: () => ({
            uploadedTemplate: {},
            steps : [],
            form: new Form({
                title: '',
                guid: '',
                description: '',
                _method: 'PUT'
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


        mounted() {
            this.form.busy = true
            Categories.get(this.$route.params.category, ['']).then(category => {
                this.form.keys().forEach(key => {
                    if (category[key]) {
                        this.form[key] = category[key]
                    }
                })
                this.form.busy = false
            });
        },


        methods: {
            onTitleChange(){
                this.form.guid = this.guid;
            },
            async update () {
                await this.form.post(`/api/categories/${this.$route.params.category}`);
            }
        }
    }
</script>
