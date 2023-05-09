<script setup>
import { ref } from "vue";
import { Modal } from "bootstrap";
import { RouterView } from "vue-router";


import Footer from "./components/Default/Footer.vue";
import Menu from "./components/Default/Menu.vue";
import LoginView from "./views/LoginView.vue";
import Cookie from "./components/Home/Cookie.vue";

import { initData } from "./utils/initData";


const modalRef = ref(null);
const closeModal = () => Modal.getInstance(modalRef.value)?.hide();

initData();

const links1 = [
  {
    title: "Twitter",
    link: "https://www.twitter.com",
  },
  {
    title: "Facebook",
    link: "https://www.facebook.com",
  },
  {
    title: "Instagram",
    link: "https://www.instagram.com",
  },
];

const links2 = [
  {
    title: "Changer mes informations",
    link: "/user/personal-data",
  },
  {
    title: "Mes achats",
    link: "/user/mypurchases",
  },
  {
    title: "Notre politique de confidentialité",
    link: "/user/confidentiality",
  },
];
</script>

<template>
  <header>
    <Menu/>

    <div
      class="modal fade"
      id="ModalConnection"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="modalRef"
    >
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
          <LoginView :closeModal="closeModal"/>
        </div>
      </div>
    </div>
  </header>

  <main>
    <Suspense> <RouterView /></Suspense>
  </main>


  <Cookie />

  <Footer :links1="links1" :links2="links2" />
</template>

<style scoped>
div.nav-message-container {
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
}

div.flash-message {
  margin: 1vw 15vw;
  padding: 0.3rem 0;
  background-color: red;
  text-align: center;
  display: flex;
  color: var(--color-text-light);
  border-radius: 0.375rem;
}

div.message-flash-message {
  vertical-align: middle;
  padding: inherit;
  flex: 10;
}

span.span-flash-message {
  font-weight: bold;
}

div.close-flash-message {
  flex: 1;
}
</style>
