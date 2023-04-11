<script setup>
import { ref, onMounted } from "vue";

const cookie = ref(false);

onMounted(() => {
  if (window.localStorage.getItem("TOKEN_COOKIE")) {
    cookie.value = true;
  }
});

const setLocalStorage = (valid) => {
  window.localStorage.setItem("TOKEN_COOKIE", valid);
  document.getElementById("wrapperCookie").style.display = "none";
};
</script>

<template>
  <div id="wrapperCookie" v-if="!cookie">
    <div class="containers">
      <div class="wrapper-left">
        En cliquant sur « OK », vous acceptez de stocker des cookies sur votre
        appareil afin d'améliorer votre navigation sur le site, d'analyser votre
        utilisation du site et d'améliorer nos actions marketing.Politique de
        confidentialité
      </div>
      <div class="wrapper-right">
        <button class="bttn bttn-yt-out">Paramètres</button>
        <button class="bttn bttn-yt" @click="setLocalStorage(false)">
          Tout refuser
        </button>
        <button class="bttn bttn-yt" @click="setLocalStorage(true)">OK</button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.containers {
  background-color: #000;
  border: 1px solid #fff;
  color: var(--color-text-dark);
  padding: 3.5rem 2rem;
  display: flex;
  position: fixed;
  bottom: 1rem;
  width: 100%;
  // left: auto;
  // margin: auto;

  div.wrapper-left {
    flex: 5;
  }

  div.wrapper-right {
    flex: 4;
    display: flex;
    flex-wrap: wrap;
    justify-content: end;

    * {
      margin-left: 1rem;
    }
  }
}
</style>
