<template>
  <b-modal :title="title" :size="size" @hidden="onHidden" hide-footer visible ref="modal">
    <slot name="form">
      <b-form>
        <slot name="fields"/>
        <slot name="buttons">
          <b-row no-gutters class="mt-2 justify-content-between">
            <b-button variant="secondary" @click="$refs.modal.hide()" :disabled="this.loading">
              Back
            </b-button>
          </b-row>
        </slot>
      </b-form>
    </slot>
  </b-modal>
</template>

<script>
  export default {
    name: 'modal-view',
    data() {
      return {
        loading: false,
      }
    },
    props: {
      url: {
        type: String,
        required: false,
      },
      value: {
        type: Object,
        required: false,
        default() {
          return {}
        }
      },
      name: {
        type: String,
        required: false,
        default() {
          return this.url.replace('/', '-')
        },
      },
      title: {
        type: String,
        required: false,
        default: 'View',
      },
      size: {
        type: String,
        required: false,
        default: 'md',
      },
      beforeSubmit: {
        type: Function,
        required: false,
        default(record) {
          return record;
        }
      },
      submitVariant: {
        type: String,
        required: false,
        default: 'primary',
      }
    },
    computed: {
      record: {
        get() {
          return this.value
        },
      }
    },
    methods: {
      onHidden() {
        if (this.$listeners.onHidden) {
          this.$listeners.onHidden(this.name)
        } else {
          this.$router.go(-1);
        }
      },
    },
  }
</script>
