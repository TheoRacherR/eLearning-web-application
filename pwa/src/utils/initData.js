import { listCourses, store } from "../store/store";
import axios from "axios";

const getItems = () => {
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

export const initData = async () => {
  const courses = await getItems();

  listCourses.value = courses;
};
