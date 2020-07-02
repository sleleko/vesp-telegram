<template>
  <footer class="container mt-auto text-center text-md-right">
    <small>&copy; {{AppName}} version {{AppVersion}}. {{year}} year. All rights reserved.</small>
  </footer>
</template>

<script>
  export default {
    name: 'app-footer',
    data() {
      let year = new Date().getFullYear();
      if (year > 2020) {
        year = '2020 - ' + String(year);
      }
      return {
        AppName: process.env.APP_NAME,
        AppVersion: process.env.APP_VER,
        year: year
      }
    }
  }
</script>

<style lang="scss">
  footer {
    padding: 15px 0;
  }
</style>
