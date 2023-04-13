<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import RightContainer from "./RightContainer.vue";
import toastr from "toastr";
import router from "../../router";
import { listUsers, store } from "../../store/store";

const former = ref({});

const number = ref("0123456789");

const firstIban = ref("");
const secondIban = ref("");
const lastIban = ref("");

const firstIbanValid = ref("");
const secondIbanValid = ref("");
const lastIbanValid = ref("");
const bankNameValid = ref("");
const ownerValid = ref("");

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

onMounted(() => {
  former.value = {
    accountOwner: "",
    accountBankName: "",
    accountIban: "",
  };
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
      listUsers.value[store.user.id].teacherStatus = "WAITING";

      router.push("/user/personal-data");
    })
    .catch((err) => {
      console.log("debug", err);
    });
};

const focusOnNext = (e) => {
  switch (e) {
    case "first":
      if (
        !number.value.includes(
          firstIban.value.substring(
            firstIban.value.length - 1,
            firstIban.value.length
          )
        )
      ) {
        firstIban.value = firstIban.value.toUpperCase();
        if (firstIban.value.length === 2) {
          document.getElementById("secondIban").focus();
        } else if (firstIban.value.length >= 2) {
          firstIban.value = firstIban.value.substring(0, 2);
        }
      } else {
        firstIban.value = firstIban.value.substring(
          firstIban.value.length - 1,
          0
        );
      }
      break;

    case "second":
      if (
        number.value.includes(
          secondIban.value.substring(
            secondIban.value.length - 1,
            secondIban.value.length
          )
        )
      ) {
        secondIban.value = secondIban.value.toUpperCase();
        if (secondIban.value.length === 2) {
          document.getElementById("lastIban").focus();
        } else if (secondIban.value.length >= 2) {
          secondIban.value = secondIban.value.substring(0, 2);
        }
      } else {
        secondIban.value = secondIban.value.substring(
          secondIban.value.length - 1,
          0
        );
      }

      break;

    case "last":
      lastIban.value = lastIban.value.toUpperCase();
      if (lastIban.value.length === 23) {
        document.getElementById("bankName").focus();
      } else if (lastIban.value.length >= 23) {
        lastIban.value.substring(0, 2);
      }
      break;

    case "name":
      former.value.accountOwner = former.value.accountOwner.toUpperCase();
      break;

    case "bank":
      former.value.accountBankName = former.value.accountBankName.toUpperCase();
      break;
  }

  former.value.accountIban =
    firstIban.value + secondIban.value + lastIban.value;
};

const checkSize = (e) => {
  switch (e) {
    case "first":
      if (firstIban.value.length === 2) {
        firstIbanValid.value = "true";
      } else {
        firstIbanValid.value = "false";
      }
      break;

    case "second":
      if (secondIban.value.length === 2) {
        secondIbanValid.value = "true";
      } else {
        secondIbanValid.value = "false";
      }
      break;

    case "last":
      if (lastIban.value.length === 23) {
        lastIbanValid.value = "true";
      } else {
        lastIbanValid.value = "false";
      }
      break;

    case "name":
      if (former.value.accountOwner.length > 0) {
        ownerValid.value = "true";
      } else {
        ownerValid.value = "false";
      }
      break;

    case "bank":
      if (former.value.accountBankName.length > 0) {
        bankNameValid.value = "true";
      } else {
        bankNameValid.value = "false";
      }
      break;
  }
};
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Demande de passage en rôle professeur</h3>
      <label>IBAN</label>
      <div class="firstline">
        <div class="input-item firstIban">
          <input
            v-if="firstIbanValid !== 'false'"
            class="innput valid"
            id="firstIban"
            @change="checkSize('first')"
            @input="focusOnNext('first')"
            v-model="firstIban"
            placeholder="Exemple: FR"
          />
          <input
            v-else
            class="innput invalid"
            id="firstIban"
            @change="checkSize('first')"
            @input="focusOnNext('first')"
            v-model="firstIban"
            placeholder="Exemple: FR"
          />
        </div>
        <div class="input-item secondIban">
          <input
            v-if="secondIbanValid !== 'false'"
            class="innput valid"
            id="secondIban"
            @change="checkSize('second')"
            @input="focusOnNext('second')"
            v-model="secondIban"
            placeholder="Exemple: 76"
          />
          <input
            v-else
            class="innput invalid"
            id="secondIban"
            @change="checkSize('second')"
            @input="focusOnNext('second')"
            v-model="secondIban"
            placeholder="Exemple: 76"
          />
        </div>
        <div class="input-item lastIban">
          <input
            v-if="lastIbanValid !== 'false'"
            class="innput valid"
            id="lastIban"
            @change="checkSize('last')"
            @input="focusOnNext('last')"
            v-model="lastIban"
            placeholder="Exemple: XXXXXXXXXXXXX"
          />
          <input
            v-else
            class="innput invalid"
            id="lastIban"
            @change="checkSize('last')"
            @input="focusOnNext('last')"
            v-model="lastIban"
            placeholder="Exemple: XXXXXXXXXXXXX"
          />
        </div>
      </div>
      <div class="input-item">
        <label :for="former.accountBankName">Banque</label>
        <input
          v-if="bankNameValid !== 'false'"
          class="innput valid"
          @change="checkSize('bank')"
          @input="focusOnNext('bank')"
          id="bankName"
          v-model="former.accountBankName"
          placeholder="Exemple: BNP Paribas..."
        />
        <input
          v-else
          class="innput invalid"
          @change="checkSize('bank')"
          @input="focusOnNext('bank')"
          id="bankName"
          v-model="former.accountBankName"
          placeholder="Exemple: BNP Paribas..."
        />
      </div>

      <div class="input-item">
        <label :for="former.accountOwner">Nom du compte banquaire</label>
        <input
          v-if="ownerValid !== 'false'"
          class="innput valid"
          @change="checkSize('name')"
          @input="focusOnNext('name')"
          v-model="former.accountOwner"
          placeholder="Exemple: M.DUPOND Jean"
        />
        <input
          v-else
          class="innput invalid"
          @change="checkSize('name')"
          @input="focusOnNext('name')"
          v-model="former.accountOwner"
          placeholder="Exemple: M.DUPOND Jean"
        />
      </div>

      <button
        v-if="
          firstIbanValid === 'true' &&
          secondIbanValid === 'true' &&
          lastIbanValid === 'true' &&
          ownerValid === 'true' &&
          bankNameValid === 'true'
        "
        class="bttn bttn-wng"
        type="button"
        @click="handleSubmit"
      >
        Envoyer la demande
      </button>
      <button v-else class="bttn bttn-wng disabled" disabled>
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

    input.invalid {
      border-color: red;
    }
  }
}
</style>
