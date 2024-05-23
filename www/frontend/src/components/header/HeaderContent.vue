<template>
  <header>
    <div class="content">
      <router-link to="/" class="logo-link">
        <img src="../../assets/images/logo-white.svg" class="logo" alt="NSUNet">
      </router-link>
      <router-link to="/" class="notify-link">
        <img src="../../assets/images/notifications.svg" alt="notification">
      </router-link>
      <div class="language-choice">
        <span class="language" ref="languages" @click="isShowLanguages = !isShowLanguages">En</span>
        <div class="languages" v-if="isShowLanguages">
          <router-link to="/">Ру</router-link>
          <router-link to="/">En</router-link>
        </div>
      </div>
      <router-link to="/" class="user-link">
        <span>{{ firstname }} {{ lastname }}</span>
      </router-link>
      <div>
        <el-button type="primary" @click="logout">Выйти</el-button>
      </div>
    </div>
  </header>
</template>

<script>
import axios from "axios";

export default {
  name: "header-content",
  methods: {
    logout() {
      axios.post('/api/v1/logout').then(response => {
        console.log(response);
        this.removeToken();
        this.$router.push({ name: 'login' });
      }).catch(error => {
        console.log(error)
      });
    },
    removeToken() {
      localStorage.removeItem('token');
      localStorage.removeItem('role');
      this.$store.commit('setToken', '');
      this.$store.commit('setIsAuth', false);
      this.$store.commit('setRole', '');
    },
  },
  data() {
    return {
      isShowLanguages: false,
    };
  },
  props: {
    firstname: String,
    lastname: String,
  },
}
</script>

<style scoped lang="scss">
  header {
    height: 105px;
    background: #337AB7;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
    color: #ffffff;
    .content {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      flex-wrap: nowrap;
    }
    a {
      color: #ffffff;
    }
  }
  .logo-link {
    margin-right: 25px;
  }
  .logo {
    width: 150px;
  }
  .notify-link {
    margin-left: auto;
    height: 20px;
  }
  .language-choice {
    margin-left: 33px;
    width: 40px;
    .language {
      font-weight: 700;
      display: flex;
      flex-wrap: nowrap;
      align-items: center;
      justify-content: space-between;
      &:after {
        display: block;
        content: '';
        width: 10px;
        height: 10px;
        background: url("../../assets/images/arrow-white.svg") no-repeat center center;
        cursor: pointer;
        transform: rotate(90deg);
      }
    }
    .languages {
      position: absolute;
      border: 1px solid #fff;
      width: 40px;
      background: #FFFFFF;
      box-shadow: 0 6px 58px rgba(49, 49, 49, 0.4);
      border-radius: 17px;
      margin-left: -12px;
      padding: 5px 0;
      a {
        display: block;
        color: #337AB7;
        text-align: center;
        font-weight: 700;
      }
    }
  }
  .user-link {
    margin-left: 24px;
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: space-between;
    font-weight: 700;
    text-decoration: none;
    &:after {
      display: block;
      content: '';
      width: 10px;
      height: 10px;
      background: url("../../assets/images/arrow-white.svg") no-repeat center center;
      cursor: pointer;
      margin-left: 10px;
    }
    &:focus, &:hover {
      text-decoration: underline;
    }
  }
</style>