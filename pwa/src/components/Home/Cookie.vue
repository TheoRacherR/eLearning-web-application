<script setup>

if (document.getElementById("wrapperCookie") && window.localStorage.getItem("validToken")){
  console.log("ok");
  document.getElementById("wrapperCookie").style.display = "none";
}

const setLocalStorage = (valid) => {
  window.localStorage.setItem("validToken", valid);
  document.getElementById("wrapperCookie").style.display = "none";
}

</script>


<template> 

    <div id="wrapperCookie">

        <div class="container">
            <div class="wrapper-left">
                En cliquant sur « OK », vous acceptez de stocker des cookies sur votre appareil afin d'améliorer votre navigation sur le site, d'analyser votre utilisation du site et d'améliorer nos actions marketing.Politique de confidentialité
            </div>
            <div class="wrapper-right">
                <button class="bttn bttn-yt-out">Paramètres</button>
                <button class="bttn bttn-yt" @click="setLocalStorage(false)">Tout refuser</button>
                <button class="bttn bttn-yt" @click="setLocalStorage(true)">OK</button>
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
        justify-content: end;

        * {
          margin-left: 1rem;
        }
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