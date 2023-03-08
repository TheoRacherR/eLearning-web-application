<script setup>
import { ref } from "vue";
import { Modal } from "bootstrap";
import { RouterLink, RouterView } from "vue-router";

import router from "./router";
import { store } from "./store/store";

import Footer from "./components/Default/Footer.vue"
import Menu from "./components/Default/Menu.vue"
import LoginView from "./views/LoginView.vue";

import { initData } from "./utils/initData";

import toastr from 'toastr';

const modalRef = ref(null);
const closeModal = () => Modal.getInstance(modalRef.value)?.hide();

initData();

const links1 = [
  {
    title: "Google",
    link: "https://www.google.com"
  },
  {
    title: "Twitter",
    link: "https://www.twitter.com"
  },
  {
    title: "Facebook",
    link: "https://www.facebook.com"
  },
  {
    title: "Twitch",
    link: "https://www.twitch.tv"
  },
  
]

const links2 = [
  {
    title: "Yahoo",
    link: "https://www.yahoo.com"
  },
  {
    title: "Instagram",
    link: "https://www.instagram.com"
  },
  {
    title: "Spotify",
    link: "https://www.spotify.com"
  },
  
]

const links3 = [
  {
    title: "Amazon",
    link: "https://www.amazon.com"
  },
  {
    title: "Microsoft",
    link: "https://www.microsoft.com"
  },
  {
    title: "Apple",
    link: "https://www.apple.com"
  },
  
]

let showMessage = () => {
  toastr.success('Ceci est un message', '', {timeOut: 100000});

}


</script>

<template>
  <header>

    <Menu />

    <div class="modal fade" id="ModalConnection" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="modalRef">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Connexion</h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <LoginView :closeModal="closeModal" />
        </div>
      </div>
    </div>

  </header>


  <main>
    <RouterView/>
  </main>

  <button @click="showMessage">here</button>

  <Footer :links1="links1" :links2="links2" :links3="links3"/>

</template>

<style scoped>

div.nav-message-container{
  padding: 0 15vw;
}

div.nav-message {
  text-align: center;
  padding: 1rem 0;
  background-color: red;
  color: var(--color-text-light);
  display: flex;
  height: 56px;
}

div.message {
  flex: 10;
}

div.close {
  flex: 1;
  /* margin-right: 2rem; */
}

button.bttn-close {
  /* width: 2rem;
  height: 2rem; */
}



div.flash-message {
  /* width: 100%; */
  margin: 1vw 15vw;
  padding: 0.3rem 0;
  background-color: red;
  text-align: center;
  display: flex;
  /* height: 56px; */
  color: var(--color-text-light);
  /* display: table-cell; */
  border-radius: .375rem;
}


div.message-flash-message {
  vertical-align: middle;
  padding: inherit;
  /* background-color: aqua; */
  flex: 10;
}

span.span-flash-message {
  font-weight: bold;
}

div.close-flash-message {
  /* background-color: blue; */
  flex: 1;
  /* margin-right: 2rem; */
}



</style>
