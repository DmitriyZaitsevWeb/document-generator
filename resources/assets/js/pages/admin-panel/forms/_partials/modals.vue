<template>
    <div>
        <b-modal :id="`add-step`"
                 :ref="`step`"
                 title="Add step" hide-footer @hidden="onHidden">
            <form @submit.stop.prevent="handleStep">
                <vue-form-generator :schema="schema.step" :model="model.step">
                </vue-form-generator>
                <b-button variant="info" type="submit">{{ buttonText }}</b-button>
            </form>
        </b-modal>

        <b-modal :id="`step-add-question`"
                 :ref="`question`"
                 title="Add question" hide-footer @hidden="onHidden">
            <form @submit.stop.prevent="handleQuestion">
                <vue-form-generator :schema="schema.question" :model="model.question">
                </vue-form-generator>
                <b-button variant="info" type="submit">{{ buttonText }}</b-button>
            </form>
        </b-modal>

        <b-modal :id="`question-add-answer`"
                 :ref="`answer`"
                 title="Add answer" hide-footer @hidden="onHidden">
            <form @submit.stop.prevent="handleAnswer">
                <vue-form-generator :schema="schema.answer" :model="model.answer">
                </vue-form-generator>
                <b-button variant="info" type="submit">{{ buttonText }}</b-button>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios'
    import VueFormGenerator from "vue-form-generator";
    import Steps from '../../../../models/steps'
    import Questions from '../../../../models/questions'
    import Answers from '../../../../models/answers'

    export default {
        data: () => ({
            update: false,
            parent: {
                question: {},
                answer: {}
            },
            model: {
                step: {},
                question: {
                    required: 'No'
                },
                answer: {}
            },
            schema: {
                step: {
                    fields: [
                        {
                            type: "input",
                            inputType: "number",
                            label: "Order",
                            model: "order",
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Title",
                            model: "title",
                            required: true,
                        },
                        {
                            type: "textArea",
                            label: "Description",
                            model: "description",
                        },
                    ]
                },
                question: {
                    fields: [
                        {
                            type: "input",
                            inputType: "text",
                            label: "Question",
                            model: "question",
                            required: true,
                        },
                        {
                            type: "checkbox",
                            label: "Multiple",
                            model: "multiple",
                            default: false
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Name",
                            model: "name",
                            required: true,
                            visible (model){
                                return model && model.multiple;
                            },
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Help text",
                            model: "help_text",
                        },
                        {
                            type: "textArea",
                            label: "Attributes",
                            model: "attributes",
                        },
                        {
                            type: "select",
                            label: "Required",
                            model: "required",
                            required: true,
                            values(){
                                return [
                                    {id: 'No', name: 'No'},
                                    {id: 'Yes', name: 'Yes'}
                                ];
                            },
                            default: 'No',
                            validator: 'required'
                        },
                    ]
                },
                answer: {
                    fields: [
                        {
                            type: "select",
                            label: "Input*",
                            model: "schema.type",
                            required: true,
                            values: () => {
                                return [
                                    {id: "input", name: "Text"},
                                    {id: "select", name: "Select"},
                                    {id: "radios", name: "Radio"},
                                    {id: "checklist", name: "Checkboxes"},
                                    {id: "textArea", name: "Textarea"},
                                    {id: "pikaday", name: "Date Picker"},
                                ]
                            },
                            default: "input"
                        },
                        {
                            type: "select",
                            label: "Input Type",
                            model: "schema.inputType",
                            required: true,
                            values() {
                                return [
                                    {id: "text", name: "Text"},
                                    {id: "email", name: "Email"},
                                    {id: "url", name: "Url"},
                                    {id: "tel", name: "Telephone"},
                                    {id: "number", name: "Number"},
                                ]
                            },
                            default: "text",
                            visible (model){
                                return model && model.schema && model.schema.type === "input";
                            },
                            required(model) {
                                return model && model.schema && model.schema.type === "input";
                            }
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Values",
                            model: "schema.textValues",
                            hint: 'Explode values with ", "',
                            onChanged: function (model, newVal, oldVal, field) {
                                model.schema.values = model.schema.textValues.split(', ');
                            },
                            visible (model){
                                return model && model.schema && (model.schema.type === "radios" || model.schema.type === 'select' || model.schema.type === 'checklist');
                            },
                            required(model) {
                                return model && model.schema && (model.schema.type === "radios" || model.schema.type === 'select' || model.schema.type === 'checklist');
                            }
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Label",
                            model: "schema.label",
                            placeholder: "Input label",
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Name*",
                            model: "schema.model",
                            placeholder: "Name",
                            required: true,
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Placeholder",
                            model: "schema.placeholder",
                        },
                        {
                            type: "checkbox",
                            label: "Required",
                            model: "schema.required",
                            default: false
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Visible",
                            model: "schema.visibleIf",
                            placeholder: "model.a === model.b || model.c !== model.d",
                            hint: '|| - or, && - and',
                        },
                        {
                            /* https://icebob.gitbooks.io/vueformgenerator/content/validation/built-in-validators.html */
                            type: "input",
                            inputType: "text",
                            label: "Validator",
                            model: "schema.validator",
                            placeholder: "string, integer, etc.",
                            help: '<a href="https://icebob.gitbooks.io/vueformgenerator/content/validation/built-in-validators.html" title="Help" target="_blank">Help</a>',
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Hint",
                            model: "schema.hint",
                            hint: 'Show this hint below the field.',
                        },
                        {
                            type: "input",
                            inputType: "text",
                            label: "Help",
                            model: "schema.help",
                            help: 'Show this help if mouse hover the question icon before the caption of field.',
                        },
                    ]
                }
            },
        }),

        computed: {
            buttonText(){
                return this.update ? 'Update' : 'Create';
            }
        },

        methods: {
            onHidden(){
                this.parent.question = {};
                this.parent.answer = {};
                this.model.step = {};
                this.model.question = {required: 'No'};
                this.model.answer = {};
                this.update = false;
            },
            handleStep() {
                if (this.update) {
                    return this.updateStep();
                }
                return this.createStep();
            },
            handleQuestion(){
                if (this.update) {
                    return this.updateQuestion();
                }
                return this.createQuestion();
            },
            handleAnswer() {
                if (this.update) {
                    return this.updateAnswer();
                }
                return this.createAnswer();
            },

            createStep(){
                Steps.create(this.$route.params.form, this.model.step).then((data) => {
                    this.$parent.steps.push(data);
                });
                this.$refs.step.hide();
            },
            createQuestion(){
                Questions.create(this.parent.question.id, this.model.question).then((data) => {
                    this.parent.question.questions.data.push(data);
                    this.$refs.question.hide();
                });
            },
            createAnswer(){
                Answers.create(this.parent.answer.id, this.model.answer.schema).then((data) => {
                    this.parent.answer.answers.data.push(data);
                    this.$refs.answer.hide();
                });
            },

            updateStep(){
                delete this.model.step.questions;
                Steps.update(this.model.step.id, this.model.step).then((data) => {
                    this.$refs.step.hide();
                });

            },
            updateQuestion(){
                delete this.model.question.answers;
                Questions.update(this.model.question.id, this.model.question).then((data) => {
                    this.$refs.question.hide();
                });

            },
            updateAnswer(){
                Answers.update(this.model.answer.id, this.model.answer).then((data) => {
                    this.$refs.answer.hide();
                });
            }
        },

        created(){
            //console.log(this);
        }
    }
</script>
