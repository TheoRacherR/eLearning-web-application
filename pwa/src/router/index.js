import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/db",
      name: "db",
      component: () => import("../components/Dashboard/Dashboard.vue"),
    },
    {
      path: "/db/user-list",
      name: "db-user-list",
      component: () => import("../components/Dashboard/UserList.vue"),
    },
    {
      path: "/db/course-list",
      name: "db-course-list",
      component: () => import("../components/Dashboard/CourseList.vue"),
    },
    {
      path: "/db/to-valid-course",
      name: "db-to-valid-course",
      component: () => import("../components/Dashboard/CoursesToValidate.vue"),
    },
    {
      path: "/db/comm-list",
      name: "db-comm-list",
      component: () => import("../components/Dashboard/CommList.vue"),
    },
    {
      path: "/db/to-valid-comm",
      name: "db-to-valid-comm",
      component: () => import("../components/Dashboard/CommToValidate.vue"),
    },
    {
      path: "/db/user/:id",
      name: "user_page",
      component: () => import("../components/Dashboard/UserPage.vue"),
    },
    {
      path: "/dashboard",
      name: "dashboard",
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import("../components/Dashboard.vue"),
    },
    {
      path: "/register",
      name: "register",
      component: () => import("../views/RegisterView.vue"),
    },
    {
      path: "/detail/:id",
      name: "detail",
      component: () => import("../components/Detail.vue"),
    },
    {
      path: "/course/:id",
      name: "course",
      component: () => import("../components/Course.vue"),
    },
    {
      path: "/list-courses/",
      name: "list-courses",
      component: () => import("../components/ListCourses.vue"),
    },
    {
      path: "/validate/:token",
      name: "validate",
      component: () => import("../components/Validate.vue"),
    },
    {
      path: "/summary",
      name: "summary",
      component: () => import('../components/Summary.vue'),
    },
  ],
});

export default router;
