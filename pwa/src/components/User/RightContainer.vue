<script setup>

const links = [
  {
    name: "Mes achats",
    link: "mypurchases",
  },
  {
    name: "Données personnelles",
    link: "user",
  },
  {
    name: "Moyens de paiement",
    link: "mypayment",
  },
  {
    name: "Newsletter",
    link: "newsletter",
  },
  {
    name: "Politique de confidentialité",
    link: "confidentiality",
  },
];
</script>

<template>
  <div class="total-container">
    <div class="right-container">
      <RouterLink v-for="l in links" :to="l.link" style="color: black">
        <div>{{ l.name }}</div>
      </RouterLink>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div.total-container {
  display: flex;
  height: 100vh;

  div.right-container {
    background-color: rgb(245, 245, 245);
    width: 25vw;
    padding: 5rem;
    font-size: x-large;

    a {
      text-decoration: none;

      div {
        margin-bottom: 2rem;
      }
    }
  }
}
</style>
