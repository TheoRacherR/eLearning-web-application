import { listCourses, listComments, store } from "../store/store";
import axios from "axios";

const getCourses = () => {
  return new Promise((resolve, reject) => {
    const list = {};

    axios
      .get(import.meta.env.VITE_API_URL + "/courses")
      .then(({ data }) => {
        axios
          .get(import.meta.env.VITE_API_URL + "/user_courses")
          .then(({ data: { ["hydra:member"]: userCoursesRaw } }) => {
            const userCourses = userCoursesRaw.map((item) => ({
              userId:
                item.account.split("/")[item.account.split("/").length - 1],
              courseId:
                item.course.split("/")[item.course.split("/").length - 1],
            }));

            data["hydra:member"].map((item) => {
              const possessed = userCourses.some((uc) => {
                return (
                  parseInt(uc.courseId) === parseInt(item.id) &&
                  parseInt(uc.userId) === parseInt(store.user.id)
                );
              });

              list[item.id] = {
                id: item.id,
                title: item.title,
                description: item.description,
                content: item.content,
                price: item.price,
                possessed: possessed,
                valid: item.valid,
              };
            });

            resolve(list);
            
          });
      })
      .catch((err) => {
        reject(err);
      });
  });
};

const getComments = () => {
  return new Promise((resolve, reject) => {
    const list = {};

    axios
      .get(import.meta.env.VITE_API_URL + "/comments")
      .then(({ data }) => {
        axios
          .get(import.meta.env.VITE_API_URL + "/users")
          .then(({ data: { ["hydra:member"]: usersRaw } }) => {


            data["hydra:member"].map((item) => {
              let usr;
              for(let i in Object.values(usersRaw)){
                let userId = item.user_id.split("/")[item.user_id.split("/").length - 1];
                let id = Object.values(usersRaw)[i].id;
                if(id === parseInt(userId)){
                  usr = Object.values(usersRaw)[i];
                  break;
                }
              }

              list[item.id] = {
                id: item.id,
                user_id: item.user_id,
                course_id: item.course_id,
                content: item.content,
                star: item.star,
                created_at: item.created_at,
                updated_at: item.updated_at,
                valid: item.valid,
                firstname: usr.firstname,
                lastname: usr.lastname,
              };
            });

            resolve(list);
            
          });
      })
      .catch((err) => {
        reject(err);
      });
  });
};

export const initData = async () => {
  const courses = await getCourses();
  const comments = await getComments();
  store.setCart();
  listCourses.value = courses;
  listComments.value = comments;
  store.setListCoursesInCart()

};
