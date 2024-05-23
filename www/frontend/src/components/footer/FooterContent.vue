<template>
  <footer>
    <div class="content">
      <div class="branding">
        <div class="logos">
          <a href="https://nsu.ru/n">
            <img src="../../assets/images/logo-nsu-white.svg" class="logo-nsu" alt="NSU">
          </a>
          <router-link to="/">
            <img src="../../assets/images/logo-white.svg" class="logo" alt="NSUNet">
          </router-link>
        </div>
        <p class="copy">
          © 2021 Новосибирский государственный университет
        </p>
      </div>
      <div class="contacts">
        <h4>Контакты</h4>
        <p>630090, г. Новосибирск, ул. Пирогова,1, каб 4222</p>
        <p>+7 (383) 363-41-93</p>
        <p><a href="mailto:support@nsu.ru">support@nsu.ru</a></p>
      </div>
      <div class="socials">
        <h4>Мы в соцсетях</h4>
        <div class="icons">
          <a href="https://vk.com" target="_blank">
            <img src="../../assets/images/soc-vk.svg" alt="VK">
          </a>
        </div>
        <div class="service-rules">
          <router-link to="/">Правила оказания услуг связи</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterContent"
}
</script>

<style scoped lang="scss">
footer {
  background: #444444;
  color: #ffffff;
  padding: 32px 0;
  margin-top: auto;
  & > .content {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    flex-direction: row;
    flex-wrap: nowrap;
  }
}
a {
  color: #ffffff;
}
p {
  font-weight: 300;
  margin: 0.7em 0;
}
.branding {
  flex: 1 1 617px;
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  justify-content: space-between;
  .logos {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-start;
    align-items: flex-start;
    a {
      margin-right: 80px;
      display: block;
    }
    .logo-nsu {
      width: 161px;
    }
    .logo {
      width: 150px;
    }
  }
  .copy {

  }
}
.contacts {
  flex: 1 1 460px;
  h4 {
    font-style: normal;
    font-weight: 700;
    font-size: 14px;
    line-height: 17px;
    margin: 0 0 0.7em 0;
  }
}
.socials {
  text-align: right;
  h4 {
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 19px;
    margin: 0 0 0.7em 0;
  }
  .icons {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-end;
    a {
      display: block;
      width: 36px;
      height: 36px;
    }
  }
  .service-rules {
    margin-top: 36px;
  }
}

@media (max-width: 960px) {
  footer {
    & > .content {
      flex-direction: column;
    }
  }
  .branding {
    flex: auto;
    flex-direction: column;
  }
  .contacts {
    flex: auto;
    h4 {
      font-size: 16px;
      line-height: 19px;
      margin: 1.5em 0 0.7em 0;
    }
  }
  .socials {
    text-align: left;
    h4 {
      font-size: 16px;
      line-height: 19px;
      margin: 1.5em 0 0.7em 0;
    }
    .icons {
      justify-content: flex-start;
    }
  }
}
</style>