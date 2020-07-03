<template>
  <div>
    <b-form v-if="!$auth.loggedIn" @submit.prevent="onSubmit">
      <b-form-group id="input-username" label="Username:" label-for="input-username">
        <b-form-input id="input-username" v-model="form.username" :disabled="loading" required autofocus />
      </b-form-group>

      <b-form-group id="input-password" label="Password:" label-for="input-password">
        <b-form-input id="input-password" v-model="form.password" :disabled="loading" type="password" required />
      </b-form-group>

      <b-button type="submit" variant="primary">
        <b-spinner v-if="loading" small />
        LogIn
      </b-button>
    </b-form>
    <div v-else>
        <h2 v-if="$hasScope('users')" class="text-center text-md-left">Dashboard</h2>
        <b-row class="text-center justify-content-around justify-content-md-start">

        </b-row>

        <h2 v-if="$hasScope('users')" class="text-center text-md-left mt-4">Administration</h2>
        <b-row class="text-center justify-content-around justify-content-md-start">
          <template v-if="$hasScope('users')">
            <nuxt-link class="p-5 ml-md-2 mt-3 border" :to="{name: 'admin-users'}">
              <fa :icon="['fas', 'users']" size="2x"/>
              <div class="mt-1">Users</div>
            </nuxt-link>
            <nuxt-link class="p-5 ml-md-2 mt-3 border" :to="{name: 'admin-user-roles'}">
              <fa :icon="['fas', 'user-shield']" size="2x"/>
              <div class="mt-1">User roles</div>
            </nuxt-link>
            <nuxt-link class="p-5 ml-md-2 mt-3 border" :to="{name: 'admin-bot-settings'}">
              <fa :icon="['fas', 'cogs']" size="2x"/>
              <div class="mt-1">Bot settings</div>
            </nuxt-link>
          </template>
        </b-row>
      </div>
  </div>
</template>

<script>
  import {faCoins, faUserTie, faUsers, faUserShield, faUserSecret, faTasks, faCogs} from '@fortawesome/free-solid-svg-icons'
  export default {
    auth: false,
    data() {
      return {
        loading: false,
        form: {
          username: null,
          password: null,
        },
      }
    },
    created() {
      this.$fa.add(faCoins, faUserTie, faUsers, faUserShield, faUserSecret, faTasks, faCogs)
      this.redirect()
    },
    methods: {
      onSubmit() {
        this.$auth
                .loginWith('local', {data: this.form})
                .then(() => {
                  this.loading = false
                  this.$notify.success({message: 'Welcome!'})
                  this.redirect()
                })
                .catch((err) => {
                  this.$notify.error({message: err.response.data})
                })
                .finally(() => {
                  this.loading = false
                })
      },
      redirect() {
        if (this.$hasScope('maintenance')) {
          this.$router.replace({name: 'maintenance'})
        }
      },
    },
    head() {
      return {
        title: this.$auth.loggedIn ? 'Main / VespTelegramBot Admin' : 'VespTelegramBot',
      }
    },
    //auth: false,
  }

</script>

<style>
  a {color: #343a40}
  a:hover, a:active {color: #000000}
</style>