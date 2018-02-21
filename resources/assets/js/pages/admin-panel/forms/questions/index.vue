<template>
    <b-container class="bv-example-row">
        <b-row class="step-questions">
            <b-col>
                <b-btn block href="#" v-b-toggle="`accordion-step-${step.id}`" variant="info">
                    Questions
                </b-btn>
                <b-collapse :id="`accordion-step-${step.id}`"
                            :accordion="`accordion-step-${step.id}`" role="tabpanel">
                    <b-container class="bv-example-row">
                        <b-row class="header">
                            <b-col cols="1">№</b-col>
                            <b-col cols="8">Question</b-col>
                            <b-col>Required</b-col>
                            <b-col cols="1"></b-col>
                        </b-row>
                        <draggable v-model="questions"
                                   :options="{draggable:'.question', group: 'questions'}">
                            <b-row v-for="(question, key, index) in questions" :key="question.id" class="question">
                                <b-col cols="1">{{ key }}</b-col>
                                <b-col cols="8"> {{question.question}} </b-col>
                                <b-col> {{question.required}} </b-col>
                                <b-col cols="1">
                                    <b-button type="button" variant="danger" @click="remove(question.id)" class="mb-1 mb-xl-0">
                                        <icon name="trash"></icon>
                                    </b-button>
                                    <b-button type="button" variant="primary" @click="edit(question)">
                                        <icon name="pencil"></icon>
                                    </b-button>
                                </b-col>
                                <answers :question="question" :data="question.answers.data"
                                         :on-update="onUpdateQuestion" :on-change="onChange"></answers>
                            </b-row>
                            <b-btn @click="addQuestion">Add question</b-btn>
                        </draggable>
                    </b-container>
                </b-collapse>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import _ from 'lodash'
    import draggable from 'vuedraggable'
    import Form from 'vform'
    import Answers from './../answers/index'
    import Questions from './../../../../models/questions';

    export default {
        props: {
            data: {
                type: Array,
                default: () => [],
                required: true
            },
            step: {
                type: Object,
                required: true
            },
            onUpdate: {
                required: true,
                type: Function
            },
            onChange: {
                required: true,
                type: Function
            }
        },

        components: {
            draggable, Answers
        },

        data: () => ({}),

        computed: {
            questions: {
                get() {
                    return this.data
                },
                set(newValue) {
                    return this.onUpdate(this.step, newValue, this.questions)
                },
            }
        },

        methods: {
            addQuestion(){
                this.onChange('question', this.step, {}, false);
            },
            onUpdateQuestion(question, value, oldValue){
                const index = _.findKey(this.questions, _.matchesProperty('id', question.id));
                this.questions[index].answers.data = value;
            },
            edit(question){
                this.onChange('question', this.step, question, true);
            },
            remove(id){
                const index = _.findKey(this.questions, _.matchesProperty('id', id));
                Questions.destroy(id).then((data) => {
                    this.questions.splice(index, 1)
                });
            }
        },
        watch : {
            questions(value, oldValue){
                if(oldValue.length && !_.isEqual(value, oldValue)){
                    _.forEach(value, (item, key) => {
                        let question = _.omit(item, 'answers');
                        question.order = key;
                        question.form_step_id = this.step.id;
                        Questions.update(question.id, question)
                    });
                }
            }
        }
    }
</script>
