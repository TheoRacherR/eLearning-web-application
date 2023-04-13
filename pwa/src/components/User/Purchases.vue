<script setup>
import RightContainer from "./RightContainer.vue";
import toastr from "toastr";
import { store } from "../../store/store";
import router from "../../router";
import { ref, watchEffect } from "vue";

import ItemCourse from "../Courses/ItemCourse.vue";

if (!store.user.isConnected) {
  router.push("/");
  toastr.error("Vous n'êtes pas connecté ", "", { timeOut: 3000 });
}

const items = ref({});
const validItems = ref({});

watchEffect(() => {
  items.value = store.courses.list;

  for (const item in items.value) {
    if (items.value[item].possessed) {
      validItems.value = {
        ...validItems.value,
        [item]: { ...items.value[item] },
      };
    }
  }
});
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Mes derniers achats</h3>
      <div class="container-purchases">
        <div v-if="validItems.length > 0">
          <div class="purchase-item" v-for="item in validItems">
            <div class="left">
              <img v-if="item.image !== null" src="../../../public/not-found.png" alt="not found image">
              <img v-else :src="item.image" alt="image of the product" />
              <div class="item">
                <h4>{{ item.title }}</h4>
                <p>{{ item.description }}</p>
              </div>

            </div>
            <div class="right">
              <RouterLink :to="`/detail/${item.id}`">Détails</RouterLink>
              <div>{{ item.price }}€</div>
            </div>
          </div>
        </div>
        <div v-else>Vous n'avez pas de cours acheté</div>
      </div>
    </div>

    <RightContainer page="mypurchases" />
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

          .item {
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
