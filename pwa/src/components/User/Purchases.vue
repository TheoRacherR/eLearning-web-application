<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import router from "../../router";
import { store } from "../../store/store";
import RightContainer from "./RightContainer.vue";

const user = ref({});
const userId = store.user.id;

onMounted(async () => {
  if (store.user.token) {
    const { data: userRaw } = await axios
      .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .catch((err) => {
        console.log("debug", err);
      });

    user.value = {
      firstname: userRaw.firstname,
      lastname: userRaw.lastname,
      mail: userRaw.mail,
    };
  }
});

watch(
  () => store.user.token,
  async () => {
    if (store.user.token) {
      const { data: userRaw } = await axios
        .get(import.meta.env.VITE_API_URL + "/users/" + userId, {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .catch((err) => {
          console.log("debug", err);
        });

      user.value = {
        firstname: userRaw.firstname,
        lastname: userRaw.lastname,
        mail: userRaw.mail,
      };
    }
  }
);

const handleSubmit = async () => {
  axios
    .patch(
      import.meta.env.VITE_API_URL + "/users/" + userId,
      { ...user.value },
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      }
    )
    .then(() => {
      router.push("/db/user-list");
    })
    .catch((err) => {
      console.log("debug", err);
    });
};
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Mes derniers achats</h3>
      <div class="container-purchases">
        <div class="purchase-item">
          <div class="left">
            <img src="https://via.placeholder.com/250x250" alt="" />
            <div class="item">Title</div>
          </div>
          <div class="right">
            <RouterLink to="/summary">Détails</RouterLink>
            <div>15.98€</div>
          </div>
        </div>

        <div class="purchase-item">
          <div class="left">
            <img src="https://via.placeholder.com/250x250" alt="" />
            <div class="item">Title</div>
          </div>
          <div class="right">
            <RouterLink to="/summary">Détails</RouterLink>
            <div>15.98€</div>
          </div>
        </div>

        <div class="purchase-item">
          <div class="left">
            <img src="https://via.placeholder.com/250x250" alt="" />
            <div class="item">Title</div>
          </div>
          <div class="right">
            <RouterLink to="/summary">Détails</RouterLink>
            <div>15.98€</div>
          </div>
        </div>
      </div>
    </div>

    <RightContainer />
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

    div.container-purchases {
      div.purchase-item:last-child {
        border-bottom: 1px solid rgb(185, 185, 185);
      }
      div.purchase-item {
        // background-color: aquamarine;
        // margin-bottom: 1.5rem;
        padding: 1rem 1rem;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid rgb(185, 185, 185);

        div.left {
          display: flex;

          img {
            height: 8rem;
          }

          div.item {
            margin-left: 1rem;
          }
        }

        div.right {
          a {
            color: var(--color-text-light);
          }
          div {
            font-weight: bold;
          }
        }
      }
    }
  }
}
</style>
