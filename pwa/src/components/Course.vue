<script setup>
import { onMounted, ref, computed, watchEffect, watch } from "vue";
import { store, setBuyCourse } from "../store/store";
import router from "../router";
import { checkConnection } from "../utils/checkConnection";

const { id: courseId } = router.currentRoute.value.params;
const chapIndexRaw = router.currentRoute.value.params.chapIndex;

const courses = ref({});
const chapter = ref({});
const chapIndex = ref({});
const chapters = ref({});

const course = computed(() => {
  return store.courses.selected ? courses.value[store.courses.selected] : {};
});
const editor = ref({});

watch(
  () => router.currentRoute.value.params.chapIndex,
  () => {
    location.reload();
  }
);

watchEffect(() => {
  courses.value = store.courses.list;
});

watchEffect(() => {
  if (
    editor.value &&
    typeof editor.value.setHTML === "function" &&
    course.value
  ) {
    chapIndex.value = chapIndexRaw || 0;
    chapters.value =
      Object.values(JSON.parse(course.value.sequence).chapters) || [];
    chapter.value = chapters.value[chapIndex.value];

    editor.value.setHTML(chapter.value?.content);
  }
});

onMounted(() => {
  checkConnection(false, true, false, "Course page");
  store.selectCourse(courseId);

  if (
    typeof course.value?.possessed === "boolean" &&
    !course.value?.possessed
  ) {
    router.push(`/detail/${courseId}`);
  }
});
</script>

<template>
  <div class="wrapper">
    <h1>{{ course?.title }}</h1>
    <h2>{{ chapter?.title }}</h2>
    <QuillEditor ref="editor" :readOnly="true" :toolbar="[]" theme="snow" />
  </div>

  <button class="bttn bttn-wng quiz-btn" v-if="parseInt(chapIndex) > 0">
    <RouterLink :to="`/course/${courseId}/${parseInt(chapIndex) - 1}`">
      Chapitre précédent
    </RouterLink>
  </button>
  <button
    class="bttn bttn-wng quiz-btn"
    v-if="chapters.length === parseInt(chapIndex) + 1"
  >
    <RouterLink :to="`/course/${courseId}/quiz`">
      <va-icon name="last_page" />
      Faire le Quiz
    </RouterLink>
  </button>
  <button class="bttn bttn-wng quiz-btn" v-else>
    <RouterLink :to="`/course/${courseId}/${parseInt(chapIndex) + 1}`">
      Chapitre suivant
    </RouterLink>
  </button>
</template>

<style scoped>
.wrapper {
  padding: 3vh 3vw;
  text-align: center;
}
</style>
