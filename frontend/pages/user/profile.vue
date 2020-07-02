<template>
  <b-form @submit.prevent="Submit">
    <b-form-group label="Fullname">
      <b-form-input v-model="form.fullname" required/>
    </b-form-group>

    <b-form-group label="Email">
      <b-form-input type="email" v-model="form.email"/>
    </b-form-group>

    <b-form-group label="Phone">
      <b-form-input type="text" v-model="form.phone"/>
    </b-form-group>

    <b-form-group label="Telegram account">
      <b-form-input type="text" v-model="form.telegram"/>
    </b-form-group>

    <b-form-group label="Password">
      <b-form-input type="password" v-model="form.password"/>
    </b-form-group>

    <b-button variant="primary" type="submit" class="mt-2" :disabled="this.loading">
      <b-spinner small v-if="loading"/>
      Update profile
    </b-button>
  </b-form>
</template>

<script>
  export default {
    data() {
      return {
        loading: false,
        form: {
          fullname: this.$auth.user.fullname,
          email: this.$auth.user.email,
          phone: this.$auth.user.phone,
          telegram: this.$auth.user.telegram,
          password: null,
        }
      }
    },
    methods: {
      async Submit() {
        this.loading = true;
        try {
          const {data: user} = await this.$axios.patch('user/profile', this.form);
          this.$auth.setUser(user.user);

          for (let i in this.form) {
            if (this.form.hasOwnProperty(i)) {
              this.form[i] = user.user[i];
            }
          }

          this.$notify.success({message: 'Profile was updated!'})
        } catch (e) {
        } finally {
          this.loading = false
        }
      },
    }
  }
</script>