<script setup>
import RightContainer from "./RightContainer.vue";
</script>

<template>
  <div class="total-container">
    <div class="main-container">
      <h3>Mes moyens de paiements</h3>
      Vous n'avez pas de moyen de paiement d'enregistré
    </div>

    <RightContainer page="mypayment" />
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
  }
}
</style>
