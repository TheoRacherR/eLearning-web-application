<script setup>
import {
  onMounted,
  watchEffect,
  ref,
  shallowReactive,
  computed,
  markRaw,
} from "vue";
import { checkConnection } from "../utils/checkConnection";
import { store } from "../store/store";

import Cours from "./Dashboard/Cours.vue";
import Gestion from "./Dashboard/Gestion.vue";
import Profil from "./Dashboard/Profil.vue";
import Avancement from "./Dashboard/Avancement.vue";

const minimized = ref(true);

const config = shallowReactive({
  tabs: [],
  selected: [],
  setSelected(name) {
    this.selected = name;
  },
});

watchEffect(() => {
  const tabsAdmin = store.user.isAdmin
    ? [
        {
          icon: "settings_accessibility",
          name: "Gestion User",
          component: Gestion,
        },
        // { icon: "donut_small", name: "Avancement", component: Avancement },
      ]
    : [];
  const tabsTeacher = store.user.isTeacher
    ? [{ icon: "menu_book", name: "Vos cours", component: Cours }]
    : [];

  const userTabs = [{ icon: "face", name: "Profil", component: Profil }];

  config.tabs = [...tabsAdmin, ...tabsTeacher, ...userTabs];
  config.selected = [...tabsAdmin, ...tabsTeacher, ...userTabs][0].name;
});

const currentPage = computed(() => {
  const tabs = markRaw(config.tabs);
  const index = tabs.findIndex((tab) => tab.name === config.selected);

  return config.tabs[index].component;
});

const toggleMenu = () => {
  minimized.value = !minimized.value;
};

onMounted(() => {
  checkConnection(false, true, "dashboard");
});
</script>

<template>
  <div style="height: 90vh" class="wrapper">
    <va-sidebar :minimized="minimized" minimizedWidth="64px">
      <va-icon class="m-3" name="arrow_forward" @click="toggleMenu" :rotation="!minimized ? 180 : 0"/>
      <va-sidebar-item v-for="tab in config.tabs" :active="tab.name === config.selected" @click="() => {config.setSelected(tab.name);}">
        <va-sidebar-item-content>
          <va-icon :name="tab.icon" />
          <va-sidebar-item-title>{{ tab.name }}</va-sidebar-item-title>
        </va-sidebar-item-content>
      </va-sidebar-item>
    </va-sidebar>

    <Suspense>
      <component v-bind:is="currentPage"></component>
    </Suspense>
  </div>
</template>

<style scoped>
.wrapper {
  display: flex;

  max-width: 100vw;
}
</style>
