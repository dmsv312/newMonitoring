<template>
  <router-view></router-view>
</template>

<script>
import axios from "axios";

export default {
  name: 'App',
  components: {

  },
  mounted() {
    // if (localStorage.token && localStorage.role === 'admin') {
    //   this.setAdminState();
    //   this.setToken();
    //   this.$router.push({ name: 'migrate'});
    // }
    // if (localStorage.token && localStorage.role === 'user') {
    //   this.setUserState();
    //   this.setToken();
    //   this.$router.push({ name: 'home'})
    // }
    // if (!localStorage.token) {
    //   this.$router.push({ name: 'login'})
    // }
    this.getCsrf();
  },
  methods: {
    getCsrf() {
      axios.get('/api/v1/csrf').catch(error => {
        console.log(error);
      });
      console.log('hello');
    },
    // setToken() {
    //   this.$store.commit('setToken', localStorage.token);
    //   this.$store.commit('setIsAuth', true);
    //   axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
    // },
    // setUserState() {
    //   this.$store.commit('setRole', 'user');
    // },
    // setAdminState() {
    //   this.$store.commit('setRole', 'admin');
    // },
  },
  data() {
    return {

    };
  },
}
</script>

<style>

</style>
