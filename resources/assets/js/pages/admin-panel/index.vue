<template>
  <div class="row">
    <div class="col-md-3">
      <card class="admin-menu-card">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <icon :name="tab.icon"></icon>
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  loading: false,

  data () {
    return {
      tabs: [
        {
          icon: 'dashboard',
          name: 'Dashboard',
          route: 'admin-panel.dashboard'
        },
        {
          icon: 'wpforms',
          name: 'Forms',
          route: 'admin-panel.forms'
        },
        {
          icon: 'sitemap',
          name: 'Categories',
          route: 'admin-panel.categories'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
