<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import RightContainer from "./RightContainer.vue";
import toastr from "toastr";
import router from "../../router";
import { store } from "../../store/store";

const former = ref({});

const firstIban = ref("");
const secondIban = ref("");
const lastIban = ref("");

const firstIbanValid = ref(false);
const secondIbanValid = ref(false);
const lastIbanValid = ref(false);


if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

onMounted(() => {
  former.value = {
    accountOwner: "",
    accountBankName: "",
    accountIban: "",

  }
})


const handleSubmit = () => {
  axios
    .post(
      import.meta.env.VITE_API_URL + "/formers",
      {
        userId: "users/" + store.user.id,
        user_id: "users/" + store.user.id,
        accountOwner: former.value.accountOwner,
        accountBankName: former.value.accountBankName,
        accountIban: former.value.accountIban,
        createdAt: "NOW",
        updatedAt: "NOW",
        status: "WAITING",
      },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      toastr.success("Données mises à jour", "", { timeOut: 3000 });
      store.setProf(true, false);
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const focusOnNext = (e) => {
  switch(e) {
    case "first":
      firstIban.value = firstIban.value.toUpperCase()
      if(firstIban.value.length === 2){
        document.getElementById("secondIban").focus()
      }
      else if(firstIban.value.length >= 2){
        firstIban.value = firstIban.value.substring(0,2);
      }
      break;

    case "second":
      secondIban.value = secondIban.value.toUpperCase()
      if(secondIban.value.length === 2){
        document.getElementById("lastIban").focus()
      }
      else if(secondIban.value.length >= 2){
        secondIban.value = secondIban.value.substring(0,2);
      }
      break;

    case "last":
      lastIban.value = lastIban.value.toUpperCase()
      if(lastIban.value.length === 23){
        document.getElementById("bankName").focus()
      }
      else if(lastIban.value.length >= 2){
        lastIban.value.substring(0,2);
      }
      break;

      case "name":
      former.value.accountOwner = former.value.accountOwner.toUpperCase()
      break;
  }

  former.value.accountIban = firstIban.value + secondIban.value + lastIban.value;
}

const checkSizeIban = () => {
  switch(e) {
    case "first":
      if(firstIban.value.length < 2){
        document.getElementById("secondIban").focus()
      }
      break;

    case "second":
      if(secondIban.value.length < 2){
        document.getElementById("lastIban").focus()
      }
      break;

    case "last":
      if(lastIban.value.length < 23){
        document.getElementById("bankName").focus()
      }
      break;
  }
}


</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Demande de passage en rôle professeur</h3>
      <label>IBAN</label>
      <div class="firstline">
        <div class="input-item firstIban">
          <input class="innput" id="firstIban" @input="focusOnNext('first')" v-model="firstIban" placeholder="FR" />
        </div>
        <div class="input-item secondIban">
          <input class="innput" id="secondIban" @input="focusOnNext('second')" v-model="secondIban" placeholder="76" />
        </div>
        <div class="input-item lastIban">
          <input class="innput" id="lastIban" @input="focusOnNext('last')" v-model="lastIban" placeholder="" />
        </div>
      </div>
      <div class="input-item">
        <label :for="former.accountBankName">Banque</label>
        <input class="innput" id="bankName" v-model="former.accountBankName" />
      </div>

      <div class="input-item">
        <label :for="former.accountOwner">Nom du compte banquaire</label>
        <input class="innput" @input="focusOnNext('name')" v-model="former.accountOwner" />
      </div>

      <button class="bttn bttn-wng" @click="handleSubmit">
        Envoyer la demande
      </button>
    </div>

    <RightContainer page="demand-teacher" />
  </div>
</template>

<style lang="scss" scoped>
div.total-container {
  display: flex;
  height: 100vh;
  div.main-container {
    // background-color: chocolate;
    width: 75vw;
    padding: 5rem 2rem 2rem 2rem;

    h3 {
      font-weight: bold;
      margin-bottom: 2.5rem;
    }

    div.firstline {
      display: flex;
      justify-content: center;

      div:nth-child(1),
      div:nth-child(2) {
        margin-right: 2%;
      }

      div.lastIban {
        width: 80%;
      }
    }

    div.input-item {
      margin-bottom: 2rem;
    }

    button.bttn-wng {
      margin: 2rem 1rem 0 auto;
      width: 100%;
    }
  }
}
</style>
