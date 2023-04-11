<script setup>
</script>


<template>
    <div class="block">
        <h4>Go</h4>
        <div class="box">
            <div class="line-graph"></div>
            <div class="line-graph"></div>
            <div class="line-graph"></div>
            <div class="line-graph"></div>
            <div class="line-graph last-line"></div>

            <div class="container-tower">  
                <div class="tower"></div>
                <div class="tower"></div>
                <div class="tower"></div>
            </div>

        </div>
    </div>
</template>


<style lang="scss" scoped>

div.block {
    width: 100vw;
    // height: 25vh;
    background-color: rgb(230, 230, 230);
    padding: 1rem 1rem 0 1rem;

    h4 {
        text-align: center;
    }
    
    div.box {
        width: 100%;
        height: 75%;
        padding: 1rem 1rem 2rem 1rem;

        div.line-graph {
            border-top: 2px solid rgb(223, 190, 163);
            height: 5vh;
        }
        
        div.last-line {
            border-bottom: 2px solid rgb(223, 190, 163);

        }

        div.container-tower{
            display: flex;
            position: absolute;
            justify-content: space-around;
            width: auto;

            div.tower {
                background-color: blueviolet;
                // position: absolute;
                // top: 0px;
                width: 1rem;
                height: 5rem;
            }
        }

    }
}

</style>