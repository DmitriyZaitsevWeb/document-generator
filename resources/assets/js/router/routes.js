export default ({adminGuard, authGuard, guestGuard}) => {
    return [
        {path: '/', name: 'welcome', component: require('~/pages/landing.vue')},

        /*
         |--------------------------------------------------------------------------
         | Forms.
         |--------------------------------------------------------------------------
         */
        {path: '/forms', name: 'forms', component: require('~/pages/forms/index.vue'),},
        {path: '/forms/:form/save', name: 'form.save', component: require('~/pages/forms/save.vue')},
        {path: '/forms/:form/:edit?', name: 'form', component: require('~/pages/forms/show.vue')},

        /*
         |--------------------------------------------------------------------------
         | Categories.
         |--------------------------------------------------------------------------
         */
        {path: '/categories', name: 'categories', component: require('~/pages/categories/index.vue'),},
        {path: '/categories/:category', name: 'category', component: require('~/pages/categories/show.vue'),},

        /*
         |--------------------------------------------------------------------------
         | Authenticated admin routes.
         |--------------------------------------------------------------------------
         */
        ...adminGuard([
            {
                path: '/admin-panel',
                component: require('~/pages/admin-panel/index.vue'), children: [
                {
                    path: '', redirect: {name: 'admin-panel.dashboard'}
                },
                {
                    path: 'dashboard', name: 'admin-panel.dashboard', component: require(
                    '~/pages/admin-panel/dashboard.vue')
                },

                /*
                 |--------------------------------------------------------------------------
                 | Categories.
                 |--------------------------------------------------------------------------
                 */
                {
                    path: 'categories',
                    name: 'admin-panel.categories',
                    component: require('~/pages/admin-panel/categories/index.vue')
                },
                {
                    path: 'categories/create',
                    name: 'admin-panel.categories.create',
                    component: require('~/pages/admin-panel/categories/create.vue')
                },
                {
                    path: 'categories/:category',
                    name: 'admin-panel.category.edit',
                    component: require('~/pages/admin-panel/categories/edit.vue')
                },

                /*
                 |--------------------------------------------------------------------------
                 | Forms.
                 |--------------------------------------------------------------------------
                 */
                {path: 'forms', name: 'admin-panel.forms', component: require('~/pages/admin-panel/forms/index.vue')},
                {
                    path: 'forms/create',
                    name: 'admin-panel.forms.create',
                    component: require('~/pages/admin-panel/forms/create.vue')
                },
                {
                    path: 'forms/:form',
                    name: 'admin-panel.forms.edit',
                    component: require('~/pages/admin-panel/forms/edit.vue')
                },
            ]
            },
            {
                path: '/admin-panel/forms/:form/steps',
                name: 'admin-panel.forms.edit.steps',
                component: require('~/pages/admin-panel/forms/fullwidth.vue')
            },
        ]),

        /*
         |--------------------------------------------------------------------------
         | Authenticated routes.
         |--------------------------------------------------------------------------
         */
        ...authGuard([
            {path: '/home', name: 'home', component: require('~/pages/home.vue')},
            {
                path: '/settings', component: require('~/pages/settings/index.vue'), children: [
                {path: '', redirect: {name: 'settings.profile'}},
                {path: 'profile', name: 'settings.profile', component: require('~/pages/settings/profile.vue')},
                {path: 'password', name: 'settings.password', component: require('~/pages/settings/password.vue')},
                {path: 'forms', name: 'settings.forms', component: require('~/pages/settings/forms.vue')}
            ]
            }
        ]),

        /*
         |--------------------------------------------------------------------------
         | Guest routes.
         |--------------------------------------------------------------------------
         */
        ...guestGuard([
            {path: '/login', name: 'login', component: require('~/pages/auth/login.vue')},
            {path: '/register', name: 'register', component: require('~/pages/auth/register.vue')},
            {path: '/password/reset', name: 'password.request', component: require('~/pages/auth/password/email.vue')},
            {
                path: '/password/reset/:token', name: 'password.reset', component: require(
                '~/pages/auth/password/reset.vue')
            }
        ]),

        {path: '*', component: require('~/pages/errors/404.vue')}
    ];
}
