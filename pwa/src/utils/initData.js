import {
  listCourses,
  listUsers,
  listComments,
  store,
  listReports,
} from "../store/store";
import axios from "axios";
import toastr from "toastr";

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
              const possessed = userCourses.some((courseUser) => {
                return (
                  parseInt(courseUser.courseId) === parseInt(item.id) &&
                  parseInt(courseUser.userId) === parseInt(store.user.id)
                );
              });

              list[item.id] = {
                id: item.id,
                userId: item.userId,
                title: item.title,
                description: item.description,
                content: item.content,
                price: item.price,
                image: item.image,
                possessed: possessed,
                valid: item.valid,
                stripeProductId: item.stripeProductId,
                stripePriceId: item.stripePriceId,
                sequence: item.sequence,
                created_at: new Date(item.createdAt),
                updated_at: new Date(item.updated_at),
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

const getUsers = () => {
  return new Promise((resolve, reject) => {
    const list = {};

    axios
      .get(import.meta.env.VITE_API_URL + "/users")
      .then(({ data }) => {
        axios
          .get(import.meta.env.VITE_API_URL + "/formers")
          .then(({ data: { ["hydra:member"]: formerRaw } }) => {
            const formers = formerRaw.map((item) => ({
              id: item.id,
              userId: parseInt(
                item.userId.split("/")[item.userId.split("/").length - 1]
              ),
              status: item.status,
            }));

            data["hydra:member"].map((item) => {
              list[item.id] = {
                id: item.id,
                roles: item.roles,
                mail: item.mail,
                firstname: item.firstname,
                lastname: item.lastname,
                created_at: new Date(item.createdAt),
                updated_at: new Date(item.updatedAt),
                last_activity: item.last_activity,
                valid: item.valid,
                ban: item.ban,
              };

              formers.map((former) => {
                if (former.userId === item.id) {
                  list[item.id] = {
                    ...list[item.id],
                    teacherId: former.id,
                    isTeacher: true,
                    teacherStatus: former.status,
                  };
                }
              });
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
              for (let i in Object.values(usersRaw)) {
                let userId =
                  item.user_id.split("/")[item.user_id.split("/").length - 1];
                let id = Object.values(usersRaw)[i].id;
                if (id === parseInt(userId)) {
                  usr = Object.values(usersRaw)[i];
                  break;
                }
              }

              list[item.id] = {
                id: item.id,
                user_id: item.user_id.split("/")[2],
                course_id: item.course.split("/")[2],
                content: item.content,
                star: item.star,
                created_at: new Date(item.createdAt),
                updated_at: new Date(item.updatedAt),
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

const getReports = () => {
  return new Promise((resolve, reject) => {
    const list = {};
    if (store.user.isConnected && store.user.isAdmin) {
      axios
        .get(import.meta.env.VITE_API_URL + "/course_reports", {
          headers: {
            Authorization: `Bearer ${store.user.token}`,
          },
        })
        .then(({ data }) => {
          axios
            .get(import.meta.env.VITE_API_URL + "/users")
            .then(({ data: { ["hydra:member"]: usersRaw } }) => {
              data["hydra:member"].map((item) => {
                let usr;
                for (let i in Object.values(usersRaw)) {
                  let userId =
                    item.user_id.split("/")[item.user_id.split("/").length - 1];
                  let id = Object.values(usersRaw)[i].id;
                  if (id === parseInt(userId)) {
                    usr = Object.values(usersRaw)[i];
                    break;
                  }
                }

                list[item.id] = {
                  id: item.id,
                  user_id: item.user_id.split("/")[2],
                  firstname: usr.firstname,
                  lastname: usr.lastname,
                  course_id: item.course.split("/")[2],
                  reason: item.reason,
                  done: item.done,
                };
              });

              resolve(list);
            });
        })
        .catch((err) => {
          reject(err);
        });
    } else {
      resolve(list);
    }
  });
};

export const initData = async () => {
  const courses = await getCourses();
  const users = await getUsers();
  const comments = await getComments();
  const reports = await getReports();
  store.setCart();
  listCourses.value = courses;
  listUsers.value = users;
  listComments.value = comments;
  listReports.value = reports;
  store.setListCoursesInCart();

  let arr = [];
  if (localStorage.getItem("CART") === null) {
    localStorage.setItem("CART", JSON.stringify(arr));
  }
};
