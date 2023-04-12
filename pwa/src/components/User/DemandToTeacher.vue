<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import RightContainer from "./RightContainer.vue";
import toastr from "toastr";
import router from "../../router";
import { store } from "../../store/store";

const former = ref({});
const validIban = ref(false);
const validOwner = ref(false);
const validBank = ref(false);

const firstIban = ref("");
const secondIban = ref("");
const lastIban = ref("");

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

onMounted(async () => {
  if (store.user.token) {
    former.value = {
      accountOwner: "",
      accountBankName: "",
      accountIban: "",
    };
  }
});

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

const verifIban = () => {
  setTimeout(() => {
    let iban = former.accountIban.toUpperCase();

    if (iban.substring(0, 2) === "FR") {
      //IBAN FR
      //Pour fr :
      //commence par 2 lettres
      //taille total 27 carac
      //pas d'espace
      if (
        parseInt(iban.substring(2, 2)) >= 2 &&
        parseInt(iban.substring(2, 2)) <= 98
      ) {
        if (iban.substring(4).length == 27) {
          console.log("IBAN valide");
        }
      }
    } else {
      //IBAN non FR
    }

    //Pour autre :
    //2 premiers: 2 lettres
    //2 suivant: clé de controle donc 2 chiffres de 02 à 98
    //suivant 30 max
  }, 2000);
};
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Demande de passage en rôle professeur</h3>
      <label>IBAN</label>
      <div class="firstline">
        <div class="input-item firstIban">
          <input class="innput" v-model="firstIban.value" placeholder="FR" />
        </div>
        <div class="input-item secondIban">
          <input class="innput" v-model="secondIban.value" placeholder="76" />
        </div>
        <div class="input-item lastIban">
          <input class="innput" v-model="lastIban.value" placeholder="" />
        </div>
      </div>
      <div class="input-item">
        <label :for="former.accountBankName">Banque</label>
        <input class="innput" v-model="former.accountBankName" />
      </div>

      <div class="input-item">
        <label :for="former.accountOwner">Nom du compte banquaire</label>
        <input class="innput" v-model="former.accountOwner" />
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
