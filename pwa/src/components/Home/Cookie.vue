<script setup>

</script>


<template> 

    <div style="display: none">

        <div class="container">
            <div class="wrapper-left">
                En cliquant sur « OK », vous acceptez de stocker des cookies sur votre appareil afin d'améliorer votre navigation sur le site, d'analyser votre utilisation du site et d'améliorer nos actions marketing.Politique de confidentialité
            </div>
            <div class="wrapper-right">
                <button class="bttn bttn-yt-out">Paramètres</button>
                <button class="bttn bttn-yt">Tout refuser</button>
                <button class="bttn bttn-yt">OK</button>
            </div>
        </div>

    </div>


</template>


<style lang="scss" scoped>

div.container {
    background-color: #000;
    border: 1px solid #fff;
    color: var(--color-text-dark);
    padding: 1rem;
    display: flex;
    position: fixed;
    bottom: 1rem;
    left: auto;


    div.wrapper-left {
        flex: 5;
    }

    div.wrapper-right {
        flex: 4;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }
  }
  
  @media (min-width: 800px){
    div.container{
      max-width: 800px;
      margin: auto;
      width: 100%;
    }
  }

  @media (min-width: 1100px){
    div.container{
      max-width: 1100px;
      margin: auto;
      width: 100%;
    }
  }
  
  @media (min-width: 1500px) {
  div.container{
    max-width: 1500px;
    margin: auto;
    width: 100%;
  }
}

</style>