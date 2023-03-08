import { reactive, ref, toRaw } from "vue";

export const listCourses = ref({});

const bool = false;

const initStore = {
  user: {
    isValid: false,
    isConnected: false,
    id: 0,
    mail: "",
    firstname: "",
    lastname: "",
    isAdmin: false,
    isTeacher: false,
    isTeacherValid: bool,
    token: "",
  },
  courses: { list: {}, selected: null },
};

export const store = reactive({
  ...initStore,
  courses: {
    ...initStore.courses,
    list: listCourses,
  },
  setConnected(isConnected) {
    this.user.isConnected = isConnected;
  },
  setValid(isValid) {
    this.user.isValid = isValid;
  },
  setUser(user) {
    this.user.id = user.user_id;
    this.user.mail = user.mail;
    this.user.firstname = user.firstname;
    this.user.lastname = user.lastname;
    this.user.isAdmin = user.isAdmin;
  },
  setToken(token) {
    this.user.token = token;
  },
  setProf(isTeacher, isValid) {
    this.user.isTeacher = isTeacher;
    this.user.isTeacherValid = isValid;
  },
  selectCourse(id) {
    this.courses.selected = id;
  },

  getCourses() {
    return Object.values(this.courses.list);
  },

  reset() {
    this.user.isValid = initStore.isValid;
    this.user.isConnected = initStore.isConnected;
    this.user.id = initStore.user.id;
    this.user.mail = initStore.mail;
    this.user.firstname = initStore.firstname;
    this.user.lastname = initStore.lastname;
    this.user.isAdmin = initStore.user.isAdmin;
    this.user.isTeacher = initStore.user.isTeacher;
    this.user.isTeacherValid = initStore.user.isTeacherValid;
    this.user.token = initStore.user.token;
  },
});

export const setBuyCourse = (id) => {
  const courses = toRaw(store.courses.list);

  const arrayCourses = Object.keys(courses).map((cle) => {
    return [Number(cle), courses[cle]];
  });
  const arrayFiltered = arrayCourses.filter((course) => {
    return course[1].id === parseInt(id);
  });

  store.courses.list[arrayFiltered[0][0]].possessed = true;

  // store.courses.list.splice(
  //   arrayFiltered[0][0],
  //   1,
  //   courses[arrayFiltered[0][0]]
  // );
};
