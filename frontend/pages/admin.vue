<template>
  <div>
    <form-auth v-if="!$auth.loggedIn"/>
    <div v-else>
      <ul class="nav mt-3 mb-5 nav-tabs justify-content-center justify-content-md-start">
        <b-nav-item :to="{name: item.to}" v-for="item in items" :key="item.to">
          {{item.title}}
        </b-nav-item>
      </ul>
      <div class="mt-3">
        <p class="text-center" v-if="$route.name === 'admin'">
          This page for admin staff only.
        </p>
        <nuxt-child/>
      </div>
    </div>
  </div>
</template>

<script>
  import FormAuth from '../components/form-auth';

  export default {
    auth: false,
    validate(app) {
      return !app.$auth.loggedIn || app.$auth.hasScope('users') || app.$auth.hasScope('users/get')
    },
    components: {FormAuth},
    data() {
      let items = [];

      if (this.$auth.hasScope('users') || this.$auth.hasScope('users/get')) {
        items.push({to: 'admin-users', title: 'Users'});
        items.push({to: 'admin-user-roles', title: 'User Roles'});
        items.push({to: 'admin-bot-settings', title: 'Bot settings'});
      }

      return {
        loading: false,
        form: {
          username: null,
          password: null,
        },
        items
      }
    },
    created() {
      if (this.$route.name === 'admin' && this.items.length) {
        this.$router.replace({name: this.items[0].to})
      }
    }
  }
</script>
