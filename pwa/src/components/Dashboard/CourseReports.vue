<script setup>
import LeftDashboard from "./LeftDashboard/LeftDashboard.vue";
import { ref, watchEffect } from "vue";
import router from "../../router";
import { store, listCourses, listReports } from "../../store/store";
import toastr from "toastr";
import axios from "axios";


const courseId = router.currentRoute.value.params.id;
const items = ref({});
const validItems = ref({});
const course = ref({})
const coursesList = ref({})

const onLoadingBan = ref(false);
const onLoadingRep = ref(false);


watchEffect(() => {
  items.value = store.reports.list;
  for (const item in items.value) {
    if (items.value[item].course_id == courseId) {
      validItems.value = {
        ...validItems.value,
        [Object.values(items.value).length - item]: { ...items.value[item] },
      };
    }
  }
  coursesList.value = store.courses.list;
  for (const item in coursesList.value) {
    if (coursesList.value[item].id == courseId) {
      course.value = coursesList.value[item];
    }
  }
});


const handleBanCourse = () => {

  onLoadingBan.value = true
  for (const item in validItems.value) {

    axios
      .delete(import.meta.env.VITE_API_URL + "/course_reports/" + validItems.value[item].id, {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(() => {
        delete listReports.value[validItems.value[item].id];
        toastr.success("Les signalements ont été supprimés", "", { timeOut: 3000 });
      })
      .catch((err) => {
        console.log("debug", err);
      });
    
  }
  axios
    .delete(import.meta.env.VITE_API_URL + "/courses/" + courseId,
      {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(() => {
        delete listCourses.value[courseId];
        toastr.success("Le cours est supprimé", "", { timeOut: 3000 });
        onLoadingBan.value = false
        router.push("/db/reports-list");
      })

};

const handleCloseReport = () => {
  onLoadingRep.value = true
  for (const item in validItems.value) {
          
    axios
      .delete(import.meta.env.VITE_API_URL + "/course_reports/" + parseInt(validItems.value[item].id), {
        headers: {
          Authorization: `Bearer ${store.user.token}`,
        },
      })
      .then(() => {
        delete listReports.value[validItems.value[item].id];
        toastr.success("Les signalements ont été supprimés", "", { timeOut: 3000 });
        router.push("/db/reports-list")
      })
      .catch((err) => {
        console.log("debug", err);
      });
      
  }
  onLoadingRep.value = false

};
</script>

<template>
  <div class="container-dashboard">
    <LeftDashboard />


      
    <div class="container-commlist">
      <h2>Liste des signalements pour le cours n°{{ courseId }}: {{ course.title }}</h2>
      
      <div class="container-buttons">
        <button class="bttn bttn-dng" @click="handleBanCourse">
          <div class="spinner-border spinner-border-sm" role="status" v-if="onLoadingBan">
            <span class="visually-hidden">Loading...</span>
          </div>
          Bannir le cours
        </button>
        <button class="bttn bttn-wng" @click="handleCloseReport">
          <div class="spinner-border spinner-border-sm" role="status" v-if="onLoadingRep">
            <span class="visually-hidden">Loading...</span>
          </div>
          Fermer les signalements
        </button>
      </div>


      <div class="container-reports">

        <div v-for="(item, index) in validItems">
          {{ parseInt(index)+1 }}: "{{ item.reason }}"
        </div>
      </div>

    </div>

  </div>
</template>

<style lang="scss" scoped>
div.container-dashboard {
  width: 100%;
  height: 100%;
  display: flex;
  
  div.container-commlist {
    padding: 2rem 5rem;
    width: 90%;

    div.container-buttons {
      margin: 2rem 0;
      text-align: right;

      button:nth-child(1) {
        margin-right: 1rem
      }
    }

    div.container-reports {
      font-size: large;
    }
  }

}
</style>
