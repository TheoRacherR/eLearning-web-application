<script setup>

import LeftDashboard from './LeftDashboard/LeftDashboard.vue';

import { RouterLink } from 'vue-router';


const content = [
    {
        id: 1,
        firstname: "Michele",
        lastname: "Obama",
        mail: "michmichobama@gmail.com",
        role: "",
        valid: true,
    },
    {
        id: 2,
        firstname: "Silvin",
        lastname: "Dumont",
        mail: "silv.dumont@yahoo.fr",
        role: "Admin",
        valid: true,
    },
    {
        id: 3,
        firstname: "Franck",
        lastname: "Oam",
        mail: "",
        role: "User",
        valid: false,
    },
]


</script>



<template>
    <div class="container-dashboard">

        <LeftDashboard/>
        <div class="container-commlist">      

            <h2>Liste des utilisateurs: </h2>
            <div class="container-grid">

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Mail</th>
                            <th>Role</th>
                            <th>Compte Validé ?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in content">
                            <td>{{ c.id }}</td>

                            <td v-if="c.firstname.length > 0">{{ c.firstname }}</td>
                            <td v-else>-</td>

                            <td v-if="c.lastname.length > 0">{{ c.lastname }}</td>
                            <td v-else>-</td>

                            <td v-if="c.mail.length > 0">{{ c.mail }}</td>
                            <td v-else>-</td>

                            <td v-if="c.role.length > 0">{{ c.role }}</td>
                            <td v-else>-</td>

                            <td class="verifed" v-if="c.valid === true"><va-icon name="verified"/></td>
                            <td class="waiting" v-else><va-icon name="hourglass_empty"/></td>

                            <td>
                                <button class="bttn bttn-wng"><RouterLink to="/db/user/1"><va-icon name="last_page"/></RouterLink></button> <!--Aller sur la page-->
                                <button class="bttn bttn-dng-out"><va-icon name="block"/></button> <!--Bannir-->
                                <button class="bttn bttn-dng"><va-icon name="delete"/></button> <!--Supprimer-->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    
    </div>
</template>



<style lang="scss" scoped>

div.container-dashboard {
    width: 100%;
    height: 100%;
    display: flex;
    /* background-color: aqua; */

    div.container-commlist {
        padding: 2rem 5rem;
        width: 90%;

        div.container-grid {
            display: grid;
            grid-auto-columns: auto;
            grid-auto-rows: auto;

            thead {
                background-color: rgb(133, 85, 63);
                color: #fff;
            }
            th, td {
                padding: 1rem;
            }

            td.verifed{
                color: #52b425;
            }

            td.waiting {
                color: rgb(168, 43, 43);
            }

            td button a{
                text-decoration: none;
                color: #fff;
            }
            tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }



        }
    
    }
}




</style>