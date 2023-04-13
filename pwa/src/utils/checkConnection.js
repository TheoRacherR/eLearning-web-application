import axios from "axios";
import router from "../router";
import { store, listUsers } from "../store/store";
import toastr from "toastr";

export const checkConnection = (
  withRedirectOnConnect,
  withRedirectOnCatch,
  redirectIfNotTeacher,
  from
) => {
  if (!store.user.isConnected) {
    const tokenRaw = localStorage.getItem("TOKEN");

    if (tokenRaw) {
      const [id, token] = tokenRaw.split(" ");

      axios
        .get(import.meta.env.VITE_API_URL + "/users/" + id, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then(({ data }) => {
          axios
            .get(import.meta.env.VITE_API_URL + "/formers")
            .then(({ data: { ["hydra:member"]: formerRaw } }) => {
              const formers = formerRaw.map((item) => ({
                userId: parseInt(
                  item.userId.split("/")[item.userId.split("/").length - 1]
                ),
                isValid: item.valid,
                status: item.status,
              }));

              if (formers.some((item) => item.userId === parseInt(data.id))) {
                const former = formers.filter(
                  (item) => item.userId === parseInt(data.id)
                );

                store.setProf(true, former[0].status);
              } else if (redirectIfNotTeacher) router.push("/");
            });

          store.setToken(token);

          store.setConnected(true);
          store.setValid(data.valid);

          store.setCart();

          store.setUser({
            ...data,
            user_id: data.id,
            isAdmin: data.roles.includes("ROLE_ADMIN"),
          });

          if (withRedirectOnConnect) router.push("/");
        })
        .catch(() => {
          if (withRedirectOnCatch) router.push("/");
        });
    } else {
      if (withRedirectOnCatch) router.push("/");
    }
  }
};
