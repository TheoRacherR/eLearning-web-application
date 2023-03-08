<script setup>

const content = [
  {
    title: "Cours de JS",
    description: "Descripion",
    price: 25.98
  },
  {
    title: "PHP de A à Z",
    description: "Descripion",
    price: 16.05
  },
  {
    title: "Avoir plus de 4 en PWA",
    description: "Descripion",
    price: 11.91
  },
  
]

const sums = Object.values(content).reduce((acc, {price}) => acc + price , 0);


</script>

<template>

  <div class="container-summary">
    <h1 class="title-summary">Panier</h1>
    <div class="main-summary">

      <div class="list-items">

        <div v-for="c in content" class="item-sum">
          <div class="container-item">
            <img src="https://via.placeholder.com/250x250" alt="image of the course">
            <div class="right-box">
              <div class="title-item">{{ c.title }}</div>
              <div class="description-item">{{ c.description }}</div>
            </div>
          </div>
        </div>

      </div>

      <div class="straps">

        <div class="list-purchase">
          <div v-for="c in content" class="item-purchase">
            <div class="left-name">{{ c.title }}</div>
            <div class="right-price">{{ c.price }} €</div>
          </div>

          <div class="fill-purchase"></div>

          <div class="total-price">
            <div class="left-name">Total</div>
            <div class="right-price">{{ sums }} €</div>
            
          </div>

        </div>

        <button class="bttn bttn-succ">Terminer la commande</button>

      </div>

    </div>
  </div>

</template>

<style lang="scss" scoped>

div.container-summary {
  text-align: center;
  padding-top: 4rem;
  width: 100%;

  div.title-summary {
  }
  
  div.main-summary {
    margin-top: 3rem;
    display: flex;

    div.list-items {
      // background-color: red;
      width: 60%;
      margin: 0 2rem 0 0;

      div.item-sum{ 
        // background-color: green;
        margin: 0 0 2rem 0;
        // padding: 1rem 0;
        div.container-item{
        background-color: rgb(233, 233, 233);
        display: flex;
        // margin-top: 3vh;


        >img {
            width: 20%;
            height: auto;
            margin: 0;
            display: block;
            padding: 0;
        }

        div.right-box {
            padding: 1rem 1.5rem;
            color: #000;
            width: 100%;
            text-align: left;

            .title-item {
                font-size: 1rem;
                letter-spacing: 0px;
                line-height: 24px;
                font-weight: 700;
            }
            .description-item {
                font-size: 1rem;
                letter-spacing: 0px;
                line-height: 26px;
                font-weight: 400;
                display: block;
            }
            .check {
                display: flex;
                justify-content: end;
                color: #fff;
                
                > div {
                    padding: .2rem 1rem;
                    // background-color: #49961d;

                }
            }
        }
    }
      }
    }

    div.straps {
      // background-color: aqua;
      text-align: left;
      width: 40%;

      div.list-purchase {
        padding: 2rem;
        div.item-purchase {
          display: flex;
          justify-content: space-between;
        }

        div.fill-purchase {
          margin: 1rem 0;
          height: 1px;
          background-color: #000;
          width: 100%;
        }

        div.total-price {
          // margin-top: 2rem;
          font-weight: bold;
          display: flex;
          justify-content: space-between;
        }

      }
      > button {
        width: 100%;
      }
    }
  }
}

@media (min-width: 768px){
  div.container-summary{
    max-width: 768px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
@media (min-width: 992px){
  div.container-summary{
    max-width: 992px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}
@media (min-width: 1280px) {
  div.container-summary{
    max-width: 1280px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
  }
}


</style>
