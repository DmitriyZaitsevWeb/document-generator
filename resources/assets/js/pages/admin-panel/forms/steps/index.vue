<template>
    <div class="row steps" :class="{'steps-full-width' : fullWidth}">
        <div class="col-md-12">
            <h4 v-if="showTitle">Steps <a :href="fullWidthUrl" target="_blank"><icon name="expand"></icon></a></h4>
            <b-container class="bv-example-row steps-container">
                <b-row class="header">
                    <b-col>Title</b-col>
                    <b-col>Description</b-col>
                    <b-col cols="1"></b-col>
                </b-row>
                <draggable v-model="steps" :options="{draggable:'.step'}">
                    <b-row v-for="step in steps" :key="step.id" class="step">
                        <b-col> {{step.title}} </b-col>
                        <b-col> {{step.description}} </b-col>
                        <b-col cols="1">
                            <b-button type="button" variant="danger" @click="remove(step.id)" class="mb-1 mb-xl-0">
                                <icon name="trash"></icon>
                            </b-button>
                            <b-button type="button" variant="primary" @click="edit(step)">
                                <icon name="pencil"></icon>
                            </b-button>
                        </b-col>
                        <questions :step="step" :data="step.questions ? step.questions.data : []"
                                   :on-update="onUpdateQuestions" :on-change="onChange"></questions>
                    </b-row>
                    <b-btn v-b-modal="`add-step`">Add step</b-btn>
                </draggable>

                <modal-forms ref="modals"></modal-forms>
            </b-container>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import draggable from 'vuedraggable'
    import Form from 'vform'
    import ModalForms from './../_partials/modals'
    import Questions from './../questions/index'
    import Steps from './../../../../models/steps';
    import Icon from "../../../../../../../node_modules/vue-awesome/components/Icon";

    export default {
        components: {
            Icon,
            draggable, Questions, ModalForms
        },

        props: {
            data: {
                required: true,
                type: Array,
                default: () => [],
            },
            onUpdate: {
                required: true,
                type: Function
            },
            showTitle: {
                required: false,
                default: true
            },
            fullWidth: {
                required: false,
                default: false
            }
        },

        data: () => ({}),

        computed: {
            fullWidthUrl(){
                return `${this.$route.fullPath}/steps`;
            },
            steps: {
                get() {
                    return this.data
                },
                set(newValue) {
                    return this.onUpdate(newValue, this.steps)
                },
            }
        },

        created(){
        },

        methods: {
            onUpdateQuestions(step, value, oldValue){
                const index = _.findKey(this.steps, _.matchesProperty('id', step.id));
                this.steps[index].questions.data = value;
            },
            onChange(type, parent, data, update){
                if (!_.isEmpty(parent)) {
                    this.$refs.modals.parent[type] = parent;
                }
                this.$refs.modals.update = update;
                this.$refs.modals.model[type] = data;
                this.$refs.modals.$refs[type].show();
            },
            edit(step){
                this.onChange('step', {}, step, true);
            },
            remove(id){
                const index = _.findKey(this.steps, _.matchesProperty('id', id));
                Steps.destroy(id).then((data) => {
                    this.steps.splice(index, 1)
                });
            }
        },
        watch : {
            steps(value, oldValue){
                if(oldValue.length && !_.isEqual(value, oldValue)){
                    _.forEach(value, (item, key) => {
                        let step = _.omit(item, 'questions');
                        step.order = key;
                        Steps.update(step.id, step)
                    });
                }
            }
        }
    }
</script>
