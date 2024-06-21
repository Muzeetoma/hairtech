<script setup>
import AppLayout from '../Layouts/App.vue'
import { Head } from '@inertiajs/vue3'
import { HairColor } from '../common';
import { Link } from '@inertiajs/vue3'
import { reactive, ref } from 'vue';

const props = defineProps({
  hairProducts: Object
})

const hairProductRef = ref([]); 

function parseData(){
  props.hairProducts.forEach(product => {
  product.colors = JSON.parse(product.colors),
  product.lengths = JSON.parse(product.lengths),
  product.discounts = JSON.parse(product.discounts),
  product.prices = JSON.parse(product.prices);
  product.images.sort((a, b) => a.id - b.id);
});
hairProductRef.value = props.hairProducts;
}
function renderImage(imagename){
  return '/uploads/'+imagename;
}
function formatAmount(amount){
  var formatter = new Intl.NumberFormat('en-US');
  return formatter.format(amount);
}
function getColor(color){
  const upperCaseColor = HairColor.get(color);
  return upperCaseColor ? upperCaseColor.toLowerCase() : ' ';
}
function getOriginalPrice(price, discount){
    const discountPrice = (discount / 100) * price;
    const newPrice = price - discountPrice;
    return formatAmount(Math.round(newPrice));
}
parseData();

</script>

<template>
  <AppLayout>
    <Head title="Home" />

    <div class="container">
    <br><br>
    <h1 class="fw-bold text-center">New Hair Arrivals</h1>

    <div class="container my-4">
      <div class="row">
        <div class="col-6 col-md-3 mb-4" v-for="hairProduct in hairProductRef">
          <div class="container">
            <img :src=renderImage(hairProduct.images[0].images[0]) width="100%" class="img-fluid" /> 
            <br>
            <div class="container-fluid px-0 mt-3">
            <h6 class="m-0 mt-2 fw-light fs-6">
              {{ hairProduct.lengths[0].length }} inch, 
              {{ getColor(hairProduct.colors[0].color) }},
              {{ hairProduct.type }} hair 
              
            </h6>
            <h6 class="fw-light fs-8 t mt-1">
               N {{ getOriginalPrice(hairProduct.prices[0].price,hairProduct.discounts[0].discount) }}
                   <span class="text-secondary ms-1 fs-8 text-decoration-line-through">
                    {{ formatAmount(hairProduct.prices[0].price) }}
                  </span>
                  {{ hairProduct.discounts[0].discount }}% off
            </h6>
            <div class="fw-light mt-2 d-inline-block" 
            v-for="(colorObj, index) in hairProduct.colors" :key="colorObj.id">
              <Link :href="route('product', {id: colorObj.id, type: hairProduct.type})" 
              class="btn p-2 rounded-0 me-2" 
              :style="{'background-color': colorObj.color, 
               'height': '25px',
               'width': '25px',
               'border': index === 0 ? '1px solid white' : '0px solid transparent',
               'outline': index === 0 ? '1px solid black' : '0px solid transparent' 
               }">
               </Link>
            </div>
            </div>
          </div>
        </div>

      </div>

    </div>
 
    </div>
  </AppLayout>
</template>