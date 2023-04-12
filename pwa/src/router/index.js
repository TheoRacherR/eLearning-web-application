import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    //Accueil
    {
      path: "/",
      name: "home",
      component: HomeView,
    },

    //Page de backoffice
    {
      path: "/db",
      name: "db",
      component: () => import("../components/Dashboard/Dashboard.vue"),
    },

    //Liste des utilisateurs
    {
      path: "/db/user-list",
      name: "db-user-list",
      component: () => import("../components/Dashboard/UserList.vue"),
    },

    //Liste des demandes de changements en prof
    {
      path: "/db/demand-list",
      name: "db-demand-list",
      component: () => import("../components/Dashboard/DemandList.vue"),
    },

    //Liste des cours
    {
      path: "/db/course-list",
      name: "db-course-list",
      component: () => import("../components/Dashboard/CourseList.vue"),
    },

    //Liste des cours à valider
    {
      path: "/db/to-valid-course",
      name: "db-to-valid-course",
      component: () => import("../components/Dashboard/CoursesToValidate.vue"),
    },

    //Liste des commentaires
    {
      path: "/db/comm-list",
      name: "db-comm-list",
      component: () => import("../components/Dashboard/CommList.vue"),
    },

    //Liste des commentaires à valider
    {
      path: "/db/to-valid-comm",
      name: "db-to-valid-comm",
      component: () => import("../components/Dashboard/CommToValidate.vue"),
    },

    //Page d'un utilisateur
    {
      path: "/db/user/:id",
      name: "user_page",
      component: () => import("../components/Dashboard/UserPage.vue"),
    },

    //Page d'un cours
    {
      path: "/db/course/:id",
      name: "course_page",
      component: () => import("../components/Dashboard/CoursePage.vue"),
    },

    //Page de backoffice à supprimer
    {
      path: "/dashboard",
      name: "dashboard",
      component: () => import("../components/Dashboard.vue"),
    },

    //Inscription
    {
      path: "/register",
      name: "register",
      component: () => import("../views/RegisterView.vue"),
    },

    //Présentaiton du cours
    {
      path: "/detail/:id",
      name: "detail",
      component: () => import("../components/Detail.vue"),
    },

    //Dans le cours
    {
      path: "/course/:id",
      name: "course",
      component: () => import("../components/Course.vue"),
    },

    //Liste de tous les courses
    {
      path: "/list-courses/",
      name: "list-courses",
      component: () => import("../components/ListCourses.vue"),
    },

    //Liste de ses cours achetés
    {
      path: "/list-mypurchases/",
      name: "list-mypurchases",
      component: () => import("../components/ListPurchases.vue"),
    },

    //Liste de ses cours créés
    {
      path: "/list-mycourses/",
      name: "list-mycourses",
      component: () => import("../components/ListTeacherCourses.vue"),
    },

    //?
    {
      path: "/validate/:token",
      name: "validate",
      component: () => import("../components/Validate.vue"),
    },

    //Panier
    {
      path: "/summary",
      name: "summary",
      component: () => import("../components/Summary.vue"),
    },

    //Liens les pages de son compte
    {
      path: "/user/personal-data",
      name: "user-personal-data",
      component: () => import("../components/User/MyUserPage.vue"),
    },
    {
      path: "/user/confidentiality",
      name: "user-confidentiality",
      component: () => import("../components/User/Confidentiality.vue"),
    },
    {
      path: "/user/newsletter",
      name: "user-newsletter",
      component: () => import("../components/User/Newsletter.vue"),
    },
    {
      path: "/user/mypayment",
      name: "user-mypayment",
      component: () => import("../components/User/Payment.vue"),
    },
    {
      path: "/user/mypurchases",
      name: "user-mypurchases",
      component: () => import("../components/User/Purchases.vue"),
    },
  ],
});

export default router;
