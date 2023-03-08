<script setup>

import LeftDashboard from './LeftDashboard/LeftDashboard.vue';

import { RouterLink } from 'vue-router'

const content = [
    {
        id: 1,
        user_id: {
            id: 2,
            firstname: "Michele",
            lastname: "Obama",
        },
        course: {
            id: 1,
            title: "Comment devenir très riche, très rapidement"
        },
        content: "Ce cours n'est pas vraiment approprié, je souhaite qu'il soit supprimé de la liste",
        star: 0.5,
        valid: false,
        created_at: "2022-01-25 00:01:00",
    },    
]

</script>



<template>
    <div class="container-dashboard">

        <LeftDashboard/>
        <div class="container-commlist">      

            <h2>Liste des commentaires: </h2>
            <div class="container-grid">

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cours</th> <!-- Titre du cours-->
                            <th>Auteur</th> <!--Prénom + Nom-->
                            <th>Commentaire</th> <!-- Content du commentaire slice à un nombre N-->
                            <th>Note</th> <!--Note du commentaire: Etoiles visuels si possible sinon Int-->
                            <th>Date</th> <!-- Created at en DD/MM/AAAA-->
                            <th>Validé ?</th> <!-- Commentaire validé ?-->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in content">
                            <td v-if="c.id > 0">{{ c.id }}</td>
                            <td v-else>-</td>

                            <td v-if="c.content.length > 0 ">{{ c.content.slice(0, 75) }}...</td>
                            <td v-else>-</td>

                            <td v-if="c.user_id.firstname.length > 0 || c.user_id.lastname.length > 0">{{ c.user_id.firstname + ' ' + c.user_id.lastname }}</td>
                            <td v-else-if="c.user_id.firstname.length === 0 && c.user_id.lastname.length === 0">-</td>

                            <RouterLink to="/detail/1">
                                <td v-if="c.course.title.length > 0 ">{{ c.course.title.slice(0,20) }}...</td>
                                <td v-else>-</td>
                            </RouterLink>

                            <td v-if="c.star > 0 ">{{ c.star }}</td>
                            <td v-else>-</td>

                            <td>{{ c.created_at }}</td>

                            <td class="verifed" v-if="c.valid === true"><va-icon name="verified"/></td>
                            <td class="waiting" v-else><va-icon name="hourglass_empty"/></td>

                            <td>
                                <button class="bttn bttn-succ"><va-icon name="done"/></button> <!--Autoriser le commentaire -->
                                <button class="bttn bttn-dng"><va-icon name="close"/></button> <!--Bannir-->
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
                background-color: rgb(63, 133, 118);
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
            
            tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }



        }
    
    }
}




</style>
