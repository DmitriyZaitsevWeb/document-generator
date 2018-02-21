<template>
    <b-container class="bv-example-row">
        <b-row class="question-answers">
            <b-col>
                <b-btn block href="#" v-b-toggle="`accordion-question-${question.id}`" variant="info">
                    Answers
                </b-btn>
                <b-collapse :id="`accordion-question-${question.id}`"
                            :accordion="`accordion-question-${question.id}`" role="tabpanel">
                    <b-container class="bv-example-row">
                        <b-row class="header">
                            <b-col>Name</b-col>
                            <b-col>Label</b-col>
                            <b-col>Type</b-col>
                            <b-col>Input type/Values</b-col>
                            <b-col>Validation</b-col>
                            <b-col cols="1"></b-col>
                        </b-row>
                        <draggable v-model="answers"
                                   :options="{draggable:'.answers', group: 'answers'}">
                            <b-row v-for="answer in answers" :key="answer.id" class="answers">
                                <b-col> {{answer.schema.model}} </b-col>
                                <b-col> {{answer.schema.label}} </b-col>
                                <b-col> {{answer.schema.type}} </b-col>
                                <b-col>
                                    {{ answer.schema.type === 'input' ? answer.schema.inputType : answer.schema.textValues}}
                                </b-col>
                                <b-col> {{answer.schema.validator}} </b-col>
                                <b-col cols="1">
                                    <b-button type="button" variant="danger" @click="remove(answer.id)" class="mb-1 mb-xl-0">
                                        <icon name="trash"></icon>
                                    </b-button>
                                    <b-button type="button" variant="primary" @click="edit(answer)">
                                        <icon name="pencil"></icon>
                                    </b-button>
                                </b-col>
                            </b-row>
                            <b-btn @click="addAnswer">Add answer</b-btn>
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
    import Answers from './../../../../models/answers';


    export default {
        props: {
            data: {
                type: Array,
                required: true,
                default: () => [],
            },
            question: {
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
            draggable
        },

        data: () => ({}),

        computed: {
            answers: {
                get() {
                    return this.data
                },
                set(newValue) {
                    return this.onUpdate(this.question, newValue, this.answers)
                },
            }
        },

        methods: {
            addAnswer(){
                this.onChange('answer', this.question, {}, false);
            },
            edit(answer){
                this.onChange('answer', this.question, answer, true);
            },
            remove(id){
                const index = _.findKey(this.answers, _.matchesProperty('id', id));
                Answers.destroy(id).then((data) => {
                    this.answers.splice(index, 1)
                });
            }
        },
        watch : {
            answers(value, oldValue){
                if(oldValue.length && !_.isEqual(value, oldValue)){
                    _.forEach(value, (item, key) => {
                        let answer = item;
                        answer.order = key;
                        answer.form_question_id = this.question.id;
                        Answers.update(answer.id, answer)
                    });
                }
            }
        }
    }
</script>
