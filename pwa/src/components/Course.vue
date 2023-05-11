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
  checkConnection(false, true, false, false, "Course page");
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
  <div class="wrapper-course-content">
    <h1>{{ course?.title }}</h1>
    <h2>Chapitre n°{{ parseInt(chapIndex)+1 }}: "{{ chapter?.title }}"</h2>

    <div class="group-buttons">
    <button class="bttn bttn-wng quiz-btn" v-if="parseInt(chapIndex) > 0">
      <RouterLink :to="`/course/${courseId}/${parseInt(chapIndex) - 1}`">
        <va-icon name="chevron_left" />
        Chapitre précédent
      </RouterLink>
    </button>
    
    <button class="bttn bttn-drk quiz-btn go-quiz" v-if="chapters.length === parseInt(chapIndex) + 1">
      <RouterLink :to="`/course/${courseId}/quiz`">
        <va-icon name="quiz" />
        Faire le Quiz
        <va-icon name="chevron_right" />
      </RouterLink>
    </button>
    
    <button class="bttn bttn-wng quiz-btn next-chapter" v-else>
      <RouterLink :to="`/course/${courseId}/${parseInt(chapIndex) + 1}`">
        Chapitre suivant
        <va-icon name="chevron_right" />
      </RouterLink>
    </button>
    </div>

    <QuillEditor ref="editor" :readOnly="true" :toolbar="[]" theme="snow" />

  

  </div>
</template>

<style lang="scss" scoped>
.wrapper-course-content {
  // text-align: center;
  
  h1 {
    padding: 3vh 30vw 3vh 30vw;
    text-align:left;
    background-color: rgb(168, 156, 139);
  }
  h2 {
    padding: 1vh 30vw 3vh 30vw;
  }

  div.group-buttons {
    padding: 3vh 30vw 3vh 30vw;
    display: flex;
    // justify-content: space-between;
  
    button {
      a{
        text-decoration: none;
        color: white;
      }
    }
  
    button.next-chapter, button.go-quiz{
      margin: 0 0 0 auto;
    }
  }
}
</style>
