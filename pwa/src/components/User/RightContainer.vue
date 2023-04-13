<script setup>
import { store } from "../../store/store";

const props = defineProps({
  page: {
    type: String,
    required: true,
  },
});

let links = [
  {
    name: "Mes achats",
    link: "mypurchases",
    icon: "computer",
  },
  {
    name: "Données personnelles",
    link: "personal-data",
    icon: "fingerprint",
  },
  // {
  //   name: "Moyens de paiement",
  //   link: "mypayment",
  //   icon: "payments",
  // },
  // {
  //   name: "Newsletter",
  //   link: "newsletter",
  //   icon: "mail",
  // },
  {
    name: "Politique de confidentialité",
    link: "confidentiality",
    icon: "attach_file",
  },
];

if (!store.user.isTeacher)
  links = [
    ...links,
    {
      name: "Demande de passange en Professeur",
      link: "demand-teacher",
      icon: "school",
    },
  ];
</script>

<template>
  <div class="total-container">
    <div class="right-container">
      <RouterLink v-for="l in links" :to="l.link" style="color: black">
        <div v-if="l.link === 'demand-teacher' && store.user.isTeacher"></div>
        <div v-else>
          <div v-if="l.link === props.page" class="text highlighted">
            {{ l.name }}
            <va-icon :name="l.icon" />
          </div>

          <div v-else class="text normal">
            {{ l.name }}
            <va-icon :name="l.icon" />
          </div>
        </div>
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

      div.text {
        margin-bottom: 2rem;
        color: rgb(175, 175, 175);
        display: flex;
        justify-content: space-between;
      }

      div.highlighted {
        // background-color: rgb(161, 161, 161);
        color: black;
      }
    }
  }
}
</style>
