<script setup>
import { ref } from 'vue';
import { HairColor } from '../common';
import AppLayout from '../Layouts/App.vue'
import { Link, router, Head } from '@inertiajs/vue3'

const props = defineProps({
    hairProduct: Object,
    hairProductGroups: Object,
    hairItems: Object
});

const hairImageIndex = ref(0);
const images = [];
const details = [];

function setImageAndDetails(){
   Object.entries(props.hairProduct.images).map(([id, image])=>{
    images.push(image);
   });
   Object.entries(props.hairProduct.details).map(([id, detail])=>{
    details.push(detail);
   });
}
function uniqueHairProductGroupsByColor() {
    const uniqueHairProductByColors = new Set();
    return props.hairProductGroups.filter(hpGroup => {
        if (uniqueHairProductByColors.has(hpGroup.color)) {
            return false;
        }
        uniqueHairProductByColors.add(hpGroup.color);
        return true;
    });
}
function uniqueHairProductGroupsByLength() {
    const uniqueHairProductByLength = new Set();
    return props.hairProductGroups.filter(hpGroup => {
        if (uniqueHairProductByLength.has(hpGroup.length)) {
            return false;
        }
        uniqueHairProductByLength.add(hpGroup.length);
        return true;
    });
}
function formatAmount(amount){
  var formatter = new Intl.NumberFormat('en-US');
  return formatter.format(amount);
}
function renderImage(imagename){
    return '/uploads/'+imagename;
}
function getColor(color){
   return HairColor.get(color).toLowerCase();
}
function getHairName(length, color, type){
  return `${length} inch, ${getColor(color)}, ${type} hair`;
}
function getHairItemName(dimension, quality, type){
  return `${dimension} - ${quality} ${type}`;
}
function addHairToCart() {
  const hairFormData = new FormData();
  hairFormData.append('id',props.hairProduct.id);
  hairFormData.append('item', 'hair');
  router.post('/cart/add', hairFormData);
}
function addHairItemToCart(id) {
  const hairItemFormData = new FormData();
  hairItemFormData.append('id',id);
  hairItemFormData.append('item', 'hair-item');
  router.post('/cart/add', hairItemFormData);
}
setImageAndDetails()


</script>

<template>
  <AppLayout>
    <Head title="Hair Products" />

    <div class="container border-bottom pb-5">
    <br><br>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container">
                <img :src=renderImage(images[hairImageIndex]) width="100%" class="img-fluid" /> 
            </div>
            <div class="container text-center mt-2">
                <i v-for="(i, index) in images" :key="index" 
                :class=" i === images[hairImageIndex] ? 'bi bi-circle-fill me-1' : 'bi bi-circle me-1' " 
                @click="hairImageIndex = index">
            </i>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container px-1 px-md-5">
              <div>
                <Link :href="route('products')" class="no-text-decoration"> 
                <span class="fs-8 text-dark">Home</span>
                </Link>
                <span class="fs-8 text-dark"> > </span>
                <Link :href="route('products')" class="no-text-decoration"> 
                <span class="fs-8 text-dark">Products</span>
                </Link>
              </div>
              <div class="container-fluid py-3 px-0 border-bottom">
                <h4 class="m-0 mt-2 fw-light text-body-black fs-4"> 
                    {{ getHairName(hairProduct.length, hairProduct.color, hairProduct.type) }}
                </h4>
                <h6 class="fw-light mt-1 ms-1">
                   N {{ formatAmount(hairProduct.originalPrice) }}
                   <span class="text-secondary ms-1 fs-9 text-decoration-line-through">
                    {{ formatAmount(hairProduct.price) }}
                  </span>
                  {{ hairProduct.discount }}% off
               </h6>
           </div>

              <br>
            
            <div class="fw-light mt-2 d-inline-block" 
               v-for="hpGroup in uniqueHairProductGroupsByColor()">
              <Link 
               :href="route('product',{id: hpGroup.id, type: hpGroup.type})"
               class="btn p-2 rounded-0 me-2" 
               :style="{
               'background-color': hpGroup.color, 
               'height': '25px',
               'width': '25px',
               'border': hpGroup.color === hairProduct.color ? '1px solid white' : '0px solid transparent',
               'outline': hpGroup.color === hairProduct.color ? '1px solid black' : '0px solid transparent' }">
               </Link>
               
            </div>
            <h6 class="fw-light mt-2 i fs-8 text-secondary">{{ getColor(hairProduct.color) }}</h6>

            <h6 class="fw-light m-0 mt-4 text-body-black i"> 
                Sizes
            </h6>
            <div class="fw-light mt-3 d-inline-block" 
               v-for="hpGroup in uniqueHairProductGroupsByLength()">
              <Link 
               :href="route('product',{id: hpGroup.id, type: hpGroup.type})"
               class="btn p-2 rounded-0 me-3 fw-light" 
               :style="{
               'background-color': hpGroup.length === hairProduct.length ? 'black' : 'white', 
               'color': hpGroup.length === hairProduct.length ? 'white' : 'black', 
               'height': '40px',
               'width': '50px',
               'outline': '1px solid black' }">
               {{ hpGroup.length }}
              </Link>
            </div>

            <br>

            <div class="d-grid gap-2 mt-5">
                <form @submit.prevent="addHairToCart" class="d-grid gap-2">
                    <button class="btn btn-dark rounded-0 py-2" type="submit">
                        <span class="fw-lighter fs-9">ADD TO CART <i class="bi bi-cart ms-2"></i></span>
                    </button>
                </form>
            </div>

            <br><br>
            <div class="accordion accordion-flush" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button px-1" type="button"
                     data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" 
                     aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        <span class="fw-light fs-8">DESCRIPTION</span>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                    <div class="accordion-body fw-light fs-8 px-2">
                        {{ hairProduct.description }}
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed px-1" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        <span class="fw-light fs-8">PRODUCT DETAILS</span>
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <ul class="py-0 px-2">
                          <li v-for="detail in details" class="fs-9 fw-light">{{ detail }}</li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed px-1" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" 
                    aria-controls="panelsStayOpen-collapseThree">
                        <span class="fw-light fs-8">DELIVERY AND RETURNS</span>
                    </button>
                    
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                    <div class="accordion-body px-2">
                       <p class="fw-light fs-9">
                       <strong> General Delivery Info</strong><br>
                        All international orders are fulfilled by DHL. 
                        For more information on delivery click here.
                        Please allow 2 - 3 business days for delivery.
                        </p>
                        <p class="fw-light fs-9">
                            <strong>International Shipping</strong><br>
                            Our international website ships to over 100 countries and offers 
                            checkout in multiple currencies, with local payment types.
                             Please allow 3 - 5 business days for delivery. 
                             For any questions, please contact Client Services by email at
                            clientservices@poshthra.com, or by phone at  +000 0000 0000.
                        </p>
                        <p class="fw-light fs-9">
                            <strong>Online Returns</strong><br>
                            Returns are not accepted for now
                      </p>
                        </div>
                    </div>
                </div>
                </div>

                <br>

            <div class="container px-2">
                <i class="fs-9 fw-light">Need help?</i>
                <br>
                <p class="mt-2">
                    <ul class="none-ul">
                    <li class="text-decoration-underline mb-2">
                       <i class="bi bi-envelope fw-light fs-6 me-2"></i> 
                       <a href="poshthra@gmail.com" class="text-dark fw-light fs-8">Email us</a>
                    </li>
                    <li class="">
                        <i class="bi bi-telephone me-2 mb-2"></i>
                       <span class="fs-8 fw-light">Call us: </span> 
                       <a href="#" class="text-dark fw-light fs-7">+234 000 0000 0000 </a>
                    </li>
                </ul>
                </p>
            </div>

            </div>

        </div>
    </div>
  
    </div>


    <div class="container-fluid mb-3" v-if="hairItems.length !== 0">
    <br>
    <h4 class="text-center my-4 it fw-bold">Get the perfect Look !</h4>
    <br>
    <div class="container p-0 px-md-5">
        <div class="container px-2 px-md-5">
        <div class="row px-2 px-md-5">
            <div class="col-12 col-md-6 mb-4" v-for="hairItem in hairItems">
                <div class="container">
                    <img :src=renderImage(hairItem.images[0]) width="90%" class="img-fluid" /> 
                    <h5 class="m-0 mt-3 fw-light text-body-black fs-6"> 
                      {{ getHairItemName(hairItem.dimensions, hairItem.quality, hairItem.type) }}
                    </h5>
                    <h6 class="fw-light mt-1 fs-9">
                        N {{ formatAmount(hairItem.originalPrice) }}
                        <span class="text-secondary ms-1 fs-8 text-decoration-line-through">
                            {{ formatAmount(hairItem.price) }}
                        </span>
                        {{ hairItem.discount }}% off
               </h6>
               <div class="row px-0" style="width: 95%;">
                <div class="col-12">
                    <div class="d-grid gap-2 mt-2">
                        <form @submit.prevent="addHairItemToCart(hairItem.id)" class="d-grid gap-2">
                            <button class="btn btn-outline-dark rounded-0 py-2" type="submit">
                               <span class="fw-lighter fs-7">ADD TO CART</span>
                                <i class="bi bi-cart ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
               </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>


  </AppLayout>
</template>