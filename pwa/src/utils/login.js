import axios from "axios";
import router from "./../router";
import { store } from "../store/store";

export const login = (mail, password) => {
  return new Promise((resolve, reject) => {
    axios
      .post(import.meta.env.VITE_API_URL + "/auth", {
        mail: mail,
        password: password,
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
            }));

            const { token, ...user } = data;

            if (
              formers.some((item) => item.userId === parseInt(user.user_id))
            ) {
              const former = formers.filter(
                (item) => item.userId === parseInt(user.user_id)
              );

              store.setProf(true, former[0].isValid);
            }

            store.setConnected(true);
            store.setUser({
              ...user,
              isAdmin: user.roles.includes("ROLE_ADMIN"),
            });
            store.setToken(token);

            // Enregistrement du jeton dans le stockage local
            localStorage.setItem("TOKEN", `${user.user_id} ${token}`);
            // redirection vers la page d'accueil

            resolve();
          });
      })
      .catch((error) => {
        // Gestion des erreurs
        reject(error);
      });
  });
};
