<script setup>
import RightContainer from "./RightContainer.vue";
import router from "../../router";
import { onMounted } from "vue";
import { checkConnection } from "../../utils/checkConnection";

onMounted(() => {
  checkConnection(false, true, false, false, "Confidentiality");
});
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Les politiques de confidentialité du site</h3>

      Lorem ipsum dolor sit amet consectetur, adipisicing elit. Soluta quaerat
      quas laborum illum officia, possimus ratione obcaecati voluptate libero,
      tempora quibusdam ipsa nostrum. Consectetur tenetur eveniet corrupti
      reiciendis ab mollitia! Lorem ipsum dolor sit amet consectetur adipisicing
      elit. Optio, nostrum ipsam. Qui eligendi, quidem iure nulla tempora
      exercitationem atque iusto molestiae alias itaque ullam voluptatem
      sapiente, ipsum recusandae aut voluptatum?
    </div>

    <RightContainer page="confidentiality" />
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

      div {
        width: 50%;
      }
    }

    div.input-item {
      margin-bottom: 2rem;
    }

    button {
      margin: 2rem 1rem 0 auto;
      width: 100%;
    }
  }
}
</style>
