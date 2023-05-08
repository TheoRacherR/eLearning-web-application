<script setup>

import { store } from "../../store/store.js";
import toastr from "toastr";
import { onMounted, watchEffect, watch, computed, ref } from "vue";
import router from "../../router";
import { checkConnection } from "../../utils/checkConnection";
import axios from "axios";
import { initData } from "../../utils/initData";

const courseId = router.currentRoute.value.params.id;
const courses = ref({});
const report = ref("");
const onLoading = ref(false);

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});

watchEffect(() => {
  courses.value = store.courses.list;
});

onMounted(() => {
  checkConnection(false, false, "Detail");
  store.selectCourse(courseId);
});

const submitReport = () => {
  if (report.value.length > 0) {
    onLoading.value = true
    console.log("ok")
    axios
      .post(
        import.meta.env.VITE_API_URL + "/course_reports",
        {
          // course_id: 40,
          course_id: "courses/" + parseInt(courseId),
          user_id: "users/" + store.user.id,
          userId: "users/" + store.user.id,
          reason: report.value,
          done: false,
        },
        {
          headers: {
            Authorization  : `Bearer ${store.user.token}`,
          },
        }
    )
      .then(() => {
        toastr.success("Signalement envoyé", "", { timeOut: 3000 });
        onLoading.value = false;
        router.push(`/detail/:${courseId}`);
      })
      .catch((err) => {
        console.log(err);
      });
  }
}

</script>

<template>
  <div class="wrapper">
    <h1>
      Signaler le cours "{{ course?.title }}"
    </h1>
    <div class="textarea-report">
      <textarea name="report" id="report-textarea" placeholder="Renseigner votre signalement ici..." v-model="report"></textarea>
      <button class="bttn bttn-dng" @click="submitReport">
        <div
          class="spinner-border spinner-border-sm"
          role="status"
          v-if="onLoading"
        >
          <span class="visually-hidden">Loading...</span>
        </div>
        Ajouter le signalement
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  div.wrapper {
    padding: 6vh 6vw;
    // text-align: center;

    div.textarea-report{
      margin: 4vh 0;
      width: 700px;
      text-align: right;

      textarea {
        width: 100%;
        height: 10rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.375rem;
      }
      button {
        // width: 100%;
        margin-top: 2rem;
        
      }
    }
    
  }
</style>
