<template>
    <card :title="`Dashboard`">

    </card>
</template>

<script>
    import Form from 'vform'
    import {mapGetters} from 'vuex'

    export default {
        data: () => ({}),

        computed: mapGetters({
            user: 'authUser'
        }),

        created () {

        },

        methods: {

        }
    }
</script>
