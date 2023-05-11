<script setup>

const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
});

</script>

<template>
    <div class="container-comments">
    <div v-if="Object.values(items).length === 0">
        Il n'y a pas de commentaires
    </div>
    <div v-for="com in items" class="item-comment">
        <div class="top-com">
        <!--<img src="https://via.placeholder.com/40x40" alt="">-->
        <div class="text-topc">
            <div>{{ com.firstname }} {{ com.lastname }}</div>
            <div class="stars-com" style="display: flex;">
                <div style="margin: auto 0">
                {{ com.star }}
                </div>
                <va-icon name="star" />
            </div>
        </div>
        </div>

        <div class="main-com">
        {{ com.content.slice(0, 100) }}
        {{ com.content.length > 103 ? "..." : "" }}
        </div>
    </div>
    </div>
</template>

<style lang="scss" scoped>


    div.container-comments {
        display: flex;
        flex-wrap: wrap;
        /* text-align: left; */
        margin-top: 3rem;
        div.item-comment {
            margin: 0 1.6rem 1.6rem 0;
            width: calc(50% - 1.6rem);
            padding: 0 0 1.8rem 0;
            border-top: 1px solid grey;

            div.top-com {
                margin-bottom: 1rem;
                font-weight: bold;
                display: flex;
                justify-content: left;
                div.top-com > img {
                    border-radius: 50%;
                    div.text-topc {
                        margin-top: auto;
                        margin-bottom: auto;
                        /* margin-left: 0.5rem; */
                        margin-right: 0;

                        div.stars-com {
                            display: flex;
                        }
                    }
                }
            }
        }
    }

</style>
