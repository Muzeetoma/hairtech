<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { HairColor } from '../common'
import { usePage, router } from '@inertiajs/vue3'
import axios, { Axios } from 'axios'
import Toast from './Toast.vue'
import ToastManager from '../toastManager.js'


const page = usePage()

const authUser = computed(() => page.props.auth.user);
const cartCount = computed(() => page.props.cart.count);
const cart = computed(() => page.props.cart.items);
const cartProducts = ref([]);
const maxItemsAllowed = 10;


function formatAmount(amount){
  var formatter = new Intl.NumberFormat('en-US');
  return formatter.format(amount);
}
function renderImage(imagename){
    return '/uploads/'+imagename;
}
function getOriginalPrice(price, discount){
    const discountPrice = (discount / 100) * price;
    const newPrice = price - discountPrice;
    return formatAmount(Math.round(newPrice));
}
function getCartItemCount(id, type) {
  const cartItem = Array.from(cart.value).find(cartItem => {
    const item = JSON.parse(cartItem);
    return item.itemId === id && item.cartItem === type;
  });
  return cartItem ? JSON.parse(cartItem).count : 0;
}
function getColor(color){
  const upperCaseColor = HairColor.get(color);
   return upperCaseColor ? upperCaseColor.toLowerCase() : ' ';
}
function getCartData(){
  const formData = new FormData()
  formData.append('cartItems', JSON.stringify(cart.value));

  axios.post('/cart/products/in',formData)
  .then(res => {
    if(res.data){
      cartProducts.value = res.data;
    }
  });
}
function getHairName(length, color, type){
  return `${length} inch, ${getColor(color)}, ${type} hair`;
}
function getHairItemName(dimension, quality, type){
  return `${dimension} - ${quality} ${type}`;
}
function addToCart(cartItem, id){
  if(getCartItemCount(id,cartItem) === maxItemsAllowed){
    const toastManager = new ToastManager('#cartLimitAlert');
    toastManager.show();
    return;
  }
  const formData = new FormData()
  formData.append('item', cartItem);
  formData.append('id', id);
  router.post('/cart/add', formData);
}
function removeFromCart(cartItem, id){
  const formData = new FormData()
  formData.append('item', cartItem);
  formData.append('id', id);
  router.post('/cart/remove', formData);
}
function deleteFromCart(cartItem, id){
  const formData = new FormData()
  formData.append('item', cartItem);
  formData.append('id', id);
  router.post('/cart/delete', formData);
}
</script>

<template>
  <main>
    <header>

      <div class="container-fluid mt-3 position-relative">

        <Toast id="cartLimitAlert" :message="`For items above ${maxItemsAllowed} please contact sales!`">
        </Toast>
     
        <h4 class="text-center fw-light ls-2">POSHTHRA</h4>
        <div class="position-absolute top-0 end-0 me-4">
          <button class="btn " style="background-color: transparent;" type="button"
          @click="getCartData"
           data-bs-toggle="offcanvas" 
           data-bs-target="#cartCanvas" aria-controls="offcanvasScrolling">
            <i class="bi bi-bag me-2"></i> 
            <span class="fs-8">{{ cartCount }}</span>
          </button>
        </div>
      </div>
      <nav class="navbar navbar-expand-lg m-auto border-bottom border-black">
        <div class="container-fluid">
          <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <Link :href="route('products')" class="nav-link fw-light me-3 fs-9">Home</Link>
              </li>
              <li class="nav-item">
                <Link :href="route('products')" class="nav-link fw-light me-3 fs-9">BIG SALES</Link>
              </li>
              <li class="nav-item">
                <Link :href="route('products')" class="nav-link fw-light me-3 fs-9">ABOUT</Link>
              </li>
              <li class="nav-item">
                <Link :href="route('products')" class="nav-link fw-light me-3 fs-9">CONTACT</Link>
              </li>
            </ul>
            <div class="d-flex" role="search">
                <Link v-if="!authUser" :href="route('admin.show.hair')" class="nav-link fw-bold me-3 fs-9">Admin</Link>
                <!-- <Link v-if="!authUser" :href="route('signup.view')" class="nav-link me-3">Register</Link> -->
                <!-- <span v-if="authUser" class="fw-bold me-3"><i class="bi bi-person-fill fw-bold"></i> {{ getFirstWord(authUser.name)}}</span> -->
                <!-- <Link v-if="authUser" href="/logout" method="post" class="nav-link me-4 text-danger">Logout</Link> -->
            </div>
          </div>
        </div>
      </nav>

      <!--Cart-->
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" 
      id="cartCanvas" aria-labelledby="cartCanvasLabel">
      <div class="offcanvas-header">
        <h4 class="offcanvas-title border-bottom border-black" id="cartCanvasLabel">Your bag</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <h6 v-if="cartCount < 1" class="fw-light mb-3 text-decoration-underline">Your bag is empty</h6>

        <div class="row">
          <div class="col-12 mb-4" v-for="item in cartProducts">
            <div class="row">
              <div class="col-4">
                <img :src="renderImage(item.images[0])" height="100px" width="100%" />
              </div>
              <div class="col-8">

                <div class="container-fluid">
                  <h6 class="fs-8 fw-light m-0 p-0">
                    {{ item.cartItemType === 'hair' 
                    ? getHairName(item.length, item.color, item.type) 
                    : getHairItemName(item.dimensions, item.quality, item.type) }}
                </h6>
                 <h6 class="fs-8 fw-light mt-1">
                   N {{ getOriginalPrice(item.price, item.discount) }}
               </h6>
               
               <div class="row mt-4">
                <div class="col-9">
                
                  <div class="row">
                      <div class="col-3 border d-flex align-items-center justify-content-center">
                          <form @submit.prevent="removeFromCart(item.cartItemType, item.id)">
                              <button type="submit" class="transparent text-secondary text-center fs-5 p-0">
                                  <i class="bi bi-dash"></i>
                              </button>
                          </form>
                      </div>
                      <div class="col-3 border d-flex align-items-center justify-content-center">
                          <button class="transparent text-secondary text-center fs-6 p-0">
                              <span class="text-center">{{ getCartItemCount(item.id, item.cartItemType) }}</span>
                          </button>
                      </div>
                      <div class="col-3 border d-flex align-items-center justify-content-center">
                          <form @submit.prevent="addToCart(item.cartItemType, item.id)">
                              <button type="submit" class="transparent text-secondary text-center fs-5 p-0">
                                  <i class="bi bi-plus"></i>
                              </button>
                          </form>
                      </div>
                  </div>


                </div>
                <div class="col-3">
                  <form @submit.prevent="deleteFromCart(item.cartItemType, item.id)">
                    <button class="btn transparent" >
                    <i class="bi bi-trash"></i>
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
    </header>
    <article>

      <slot />
    </article>
  </main>
</template>