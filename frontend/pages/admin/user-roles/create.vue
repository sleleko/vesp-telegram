<template>
  <modal-create v-model="record" :url="url" title="New user role">
    <template slot="fields">
      <b-form-group label="Title of user role">
        <b-form-input v-model.trim="record.title" autofocus required/>
      </b-form-group>

      <b-form-group label="Access rights scopes" description="Comma-separated list of permissions for this group">
        <b-form-input v-model.trim="record.scope"/>
      </b-form-group>
    </template>
  </modal-create>
</template>

<script>
  export default {
    data() {
      return {
        url: 'admin/user-roles',
        roles: [],
        record: {
          title: null,
          scope: null,
        },
      }
    },
  }
</script>
