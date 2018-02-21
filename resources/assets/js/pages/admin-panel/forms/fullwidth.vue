<template>
    <steps :data="steps" :on-update="onUpdate" :show-title="false" :full-width="true"></steps>
</template>

<script>
    import Form from 'vform'
    import Forms from '../../../models/forms'
    import Steps from './steps/index';

    export default {
        layout: 'fullwidth',
        components: {Steps},
        data: () => ({
            uploadedTemplate: {},
            steps : [],
            form: new Form({
                title: '',
                guid: '',
                description: '',
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
        }
    }
</script>
