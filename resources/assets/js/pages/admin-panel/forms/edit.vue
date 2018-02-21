<template>
    <card :title='`Form "${form.title}"`'>
        <form @submit.prevent="update" @keydown="form.onKeydown($event)" enctype="application/x-www-form-urlencoded">
            <alert-success :form="form" :message="'Form updated'"></alert-success>

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

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Template</label>
                <div class="col-md-7">
                    <input @change="onChangeTemplate" type="file" name="template" accept=".doc,.docx"
                           class="form-control"
                           :class="{ 'is-invalid': form.errors.has('template') }">
                    <has-error :form="form" field="template"></has-error>
                </div>
            </div>

            <div class="form-group row" v-if="uploadedTemplate">
                <label class="col-md-3 col-form-label text-md-right">Uploaded template</label>
                <div class="col-md-7">
                    <ul>
                        <li><a :href="`/admin-panel/media/${uploadedTemplate.id}`" :title="uploadedTemplate.file_name">{{ uploadedTemplate.file_name }}</a> uploaded {{ uploadedTemplate.created_at }}</li>
                    </ul>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Categories</label>
                <div class="col-md-7">
                    <select v-model="form.categories" class="form-control" name="categories" multiple>
                        <option :value="category.id"  v-for="category in categories" :key="category.id">{{ category.title }}</option>
                    </select>
                    <has-error :form="form" field="categories"></has-error>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-9 ml-md-auto">
                    <v-button type="success" :loading="form.busy">Update</v-button>
                </div>
            </div>
        </form>

        <steps :data="steps" :on-update="onUpdate"></steps>
    </card>
</template>

<script>
    import Form from 'vform'
    import Forms from '../../../models/forms'
    import Steps from './steps/index';
    import Categories from '../../../models/categories'

    export default {
        components: {Steps},
        data: () => ({
            uploadedTemplate: {},
            steps : [],
            categories : [],
            form: new Form({
                title: '',
                guid: '',
                description: '',
                categories: [],
                template: '',
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
            Forms.get(this.$route.params.form, ['']).then(form => {
                this.steps = form.steps.data;
                this.uploadedTemplate = form.uploaded_template.data[0];
                this.form.keys().forEach(key => {
                    if (form[key]) {
                        this.form[key] = form[key]
                    }
                })
                this.form.busy = false
            });
        },


        methods: {
            onUpdate(value, oldValue){
                this.steps = value;
            },
            goBack(){
                return this.$router.go(-1);
            },
            onTitleChange(){
                this.form.guid = this.guid;
            },
            onChangeTemplate(e){
                this.form.template = e.target.files[0]
            },
            async update () {
                await this.form.post(`/api/forms/${this.$route.params.form}`);
            }
        },

        created(){
            Categories.all().then((data)=>{
                this.categories = data;
            })
        }
    }
</script>
