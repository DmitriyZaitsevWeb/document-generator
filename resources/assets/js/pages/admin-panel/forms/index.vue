<template>
    <card :title="`Forms`">
        <p class="text-right">
            <router-link :to="{ name : 'admin-panel.forms.create' }" class="btn btn-info">Create</router-link>
        </p>
        <v-server-table :url="`/api/forms/`" :columns="columns" :options="options" ref="formsTable">
            <template slot="actions" scope="props">
                <div>
                    <b-button type="button" variant="danger" @click="remove(props.row.id)">
                        <icon name="trash"></icon>
                    </b-button>
                </div>
            </template>
        </v-server-table>
    </card>
</template>

<script>
    import axios from 'axios';
    import Form from 'vform'


    export default {
        data: () => ({
            columns: ['id', 'title', 'guid', 'description', 'actions'],

            options: {
                requestFunction: function (data) {
                    return axios.get(this.url, {
                        params: data
                    }).catch(function (e) {
                        this.dispatch('error', e);
                    }.bind(this));
                },
                requestKeys : {
                    query:'search', limit:'limit', orderBy:'orderBy', ascending:'sortedBy', page:'page', byColumn:'byColumn'
                },
                params : {
                    searchFields : 'title:like',
                },
                responseAdapter(r) {
                    const response = r.data
                    return {
                        data: response.data,
                        count: response.meta.pagination.total
                    }
                },
                templates: {
                    title: function (h, row) {
                        return h('router-link', {
                            attrs: {
                                to: {
                                    name: 'admin-panel.forms.edit',
                                    params: {
                                        form: row.id,
                                    }
                                }
                            }
                        }, row.title);
                    },
                    actions: function (h, row) {
                        return h('router-link', {
                            attrs: {
                                to: {
                                    name: 'admin-panel.forms.delete',
                                    params: {
                                        form: row.id,
                                    }
                                },
                                'class': 'btn btn-danger',
                            }
                        }, 'Delete');
                    }
                }
            }
        }),

        methods: {
            async remove(id){
                await axios.delete(`/api/forms/${id}`);
                this.$refs.formsTable.refresh();
            }
        }
    }
</script>
