<script setup>

import { RouterLink } from "vue-router";

import { store } from "../../store/store";

const handleLogout = () => {
  localStorage.removeItem("TOKEN");

  store.reset();
  router.push("/");
};

</script>


<template>

  <nav class="navbar-nav">
    <div class="container-nav">

      <RouterLink to="/" class="item-nav home">E learning</RouterLink>

      <!--<button class="item-nav list" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span></span>
      </button>-->

      
      <div class="item-nav links">
        <div>
          <RouterLink to="/">Accueil</RouterLink>
        </div>
        <div class="fill"></div>
        <div>
          <RouterLink to="/list-courses">Tous les cours</RouterLink>
        </div>
        <div class="fill" v-if="store.user.isConnected"></div>
        <div v-if="store.user.isConnected">
          <RouterLink to="/dashboard">Vos cours</RouterLink>
        </div>
      </div>


      <div class="item-nav left">
        <RouterLink to="/summary" style="color: black;">
          <button class="bttn bttn-cart">
            <va-icon name="shopping_cart" style="position: absolute;"/>
            <div class="sc-count">10</div>
          </button>
        </RouterLink>

        <div class="role left-item" v-if="store.user.isAdmin && store.user.isConnected">Admin</div>
        <div class="role left-item" v-if="store.user.isTeacherValid && store.user.isConnected">Professeur</div>
        
        <button class="bttn bttn-succ bttn-login left-item" v-if="!store.user.isConnected" data-bs-toggle="modal" data-bs-target="#ModalConnection">
          Connexion
        </button>

        <button class="bttn bttn-dng bttn-logout left-item" v-if="store.user.isConnected" type="button" @click="handleLogout">
          Déconnexion
        </button>
        <button class="bttn bttn-drk-out bttn-theme left-item" type="button">
          <va-icon name="light_mode"/>
        </button>

        <!--<button class="bttn bttn-drk bttn-theme left-item" type="button">
            <va-icon name="dark_mode"/>
            <va-icon name="light_mode"/>
          </button>-->
        
      </div>

    </div>
  </nav>

</template>


<style lang="scss" scoped>



nav.navbar-nav {
  background-color: var(--background-color-light);
  padding: 0 24px;
  box-shadow: 0 2px 4px rgb(0 0 0 / 8%), 0 4px 12px rgb(0 0 0 / 8%);

  div.container-nav {
    display: flex;
    margin: 0;
    padding: 0;

    div.item-nav{
      flex: 1;



    }
//875px

    .home {
      margin: auto auto auto 0.5rem;
      text-decoration: none;
      color: var(--color-text-light);
      font-size: x-large;
      flex: 1;
    }

    .links {
      margin: auto 0;
      display: flex;
      justify-content: center;

      :nth-child(1) {
        margin-right: 0.5rem;
      }

      div.fill {
        min-height: 100%;
        width: 2px;
        background-color: var(--color-text-light);
      }

      :nth-child(3), :nth-child(5) {
        margin-left: 0.8rem;
      }

      > div > a {
        text-decoration: none;
        color: var(--color-text-light);
      }
    }

    .left {
      margin: 0.5rem 0.5rem 0.5rem 0;
      display: flex;
      justify-content: end;

      .left-item{
        margin-left: 0.5rem;
      }

      div.role {
        margin: auto 0 auto 0.5rem;
        color: var(--color-text-light);

      }
      button.bttn-cart {
      margin-right: 1rem;

        div.sc-count{
          position: relative;
          top: 1rem;
          left: 30px;
          font-weight: bold;
          margin-right: 10px;
        }
      }
    }
  }
}





</style>