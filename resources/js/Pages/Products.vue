<script setup>
import AppLayout from '../Layouts/App.vue'
import { Head } from '@inertiajs/vue3'
import { HairColor } from '../common';
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
  hairProducts: Object,
  hairTypes: Object,
  hairColors: Object
});

const hairProductRef = ref([]); 

let searchParams = {
  typesFilter: new Set(),
  lengthsFilter: new Set(),
  colorsFilter: new Set(),
};

const setUrlParamsToFilter = () => {
  const hairTypesLength = props.hairTypes.length;
  const hairColorLength = props.hairColors.length;
  const urlParams = new URLSearchParams(window.location.search);

  for(let i=0;i<hairTypesLength;i++){
    const typeParam = urlParams.get(`type${i}`);
    if(typeParam)
      searchParams.typesFilter.add(typeParam);
    else
      break;
  }

  for(let i=0;i<hairColorLength;i++){
    const colorParam = urlParams.get(`color${i}`);
    if(colorParam)
      searchParams.colorsFilter.add(colorParam);
    else
      break;
  }

  for(let i=0;i<hairLengths().length;i++){
    const lengthParam = urlParams.get(`length${i}`);
    if(lengthParam)
      searchParams.lengthsFilter.add(lengthParam);
    else
      break;
  }

  console.log("Search params: ",searchParams.typesFilter, searchParams.colorsFilter, searchParams.lengthsFilter);
}

const hairLengths = () => {
  const start = 8;
  const end = 40;
  return Array.from({ length: end - start + 1 }, (v, k) => k + start);
}
const isFilterItemChecked = (filter, item) => 
filter.has(typeof item === 'string' ? item.replace(/#/g, '') : item.toString());

const addToFilter = (filter, item) => {
  filter.add(typeof item === 'string' ? item.replace(/#/g, '') : item);
  };

const deleteFromFilter = (filter, item) => {
  filter.delete(typeof item === 'string' ? item.replace(/#/g, '') : item.toString());
};
const clearFilter = () => {
  searchParams.colorsFilter.clear();
  searchParams.lengthsFilter.clear();
  searchParams.typesFilter.clear();

  search();
}
const toggleFilter = (filter, item, event) => {
  const isChecked = event.target.checked;
  if (isChecked) {
    addToFilter(filter, item);
  } else {
    deleteFromFilter(filter, item);
  }
  search();
};

const search = () =>{
 
  const constructFilterUrl = (filterType, filterSet) => {
    let isFirst = true;
    let i = 0;
    filterSet.forEach(param => {
      if (isFirst) {
        url += `${filterType}${i}=${param}`;
        isFirst = false;
      } else {
        url += `&${filterType}${i}=${param}`;
      }
      i++;
    });
  };

  let url = '/';

  if(searchParams.typesFilter || searchParams.colorsFilter || searchParams.lengthsFilter){
    url += '?';
  }
  
  if (searchParams.typesFilter && searchParams.typesFilter.size !== 0) {
    if (url === '/') {
      url += '?';
    }
    constructFilterUrl('type', searchParams.typesFilter);
  }

  if (searchParams.colorsFilter && searchParams.colorsFilter.size !== 0) {
    if (url === '/') {
      url += '?';
    } else {
      url += '&';
    }
    constructFilterUrl('color', searchParams.colorsFilter);
  }

  if (searchParams.lengthsFilter && searchParams.lengthsFilter.size !== 0) {
    if (url === '/') {
      url += '?';
    } else {
      url += '&';
    }
    constructFilterUrl('length', searchParams.lengthsFilter);
  }

  console.log("URL: ",url);

  router.visit(url,{ method: 'get'});
}
const parseData = () => {
  props.hairProducts.forEach(product => {
    product.colors = JSON.parse(product.colors);
    product.lengths = JSON.parse(product.lengths);
    product.discounts = JSON.parse(product.discounts);
    product.prices = JSON.parse(product.prices);
    product.images.sort((a, b) => a.id - b.id);
  });
  hairProductRef.value = props.hairProducts;
};
const renderImage = (imageName) => '/uploads/' + imageName;
const formatAmount = (amount) => {
  const formatter = new Intl.NumberFormat('en-US');
  return formatter.format(amount);
};
const getColor = (color) => {
  const upperCaseColor = HairColor.get(color);
  return upperCaseColor ? upperCaseColor.toLowerCase() : ' ';
};
const getOriginalPrice = (price, discount) => {
  const discountPrice = (discount / 100) * price;
  const newPrice = price - discountPrice;
  return formatAmount(Math.round(newPrice));
};
parseData();
setUrlParamsToFilter();

</script>

<template>
  <AppLayout>
    <Head title="Home" />

    <div class="container">
    <br/><br/>
    <h1 class="fw-light text-center">New Arrivals</h1>
    <p class="fs-9 text-center">Start shopping to get the top 1% quality hair and hair products</p>


  <div class="container my-4">
  <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 border-top border-bottom border-secondary position-relative">
    <div class="container-fluid">
      <a class="navbar-brand fw-light fw-7 fs-6" href="#"><i>Filter by:</i></a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-filter-right"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item dropdown ms-2">
            <button class="btn dropdown-toggle" type="button" id="typeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="fw-light fs-8">TYPE</span>
            </button>
            <ul class="dropdown-menu position-absolute top-25 start-0 px-2 rounded-0 border border-black" 
            style="width: 80px;" aria-labelledby="typeDropdown">
              <li v-for="hairType in hairTypes" :key="hairType">
                <div class="form-check">
                  <input class="form-check-input border border-black rounded-0" type="checkbox" 
                  :id="hairType" :value="hairType" :checked="isFilterItemChecked(searchParams.typesFilter, hairType)"
                  @change="toggleFilter(searchParams.typesFilter, hairType, $event)">
                  <label class="form-check-label ms-2 fs-8" :for="hairType">{{ hairType }}</label>
                </div>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown ms-2">
            <button class="btn dropdown-toggle" type="button" id="colorDropdown" 
            data-bs-toggle="dropdown" aria-expanded="false">
              <span class="fw-light fs-8">COLOR</span>
            </button>
            <ul class="dropdown-menu position-absolute top-25 start-0 px-2 rounded-0 border border-black"
             style="width: 150px;height: 200px;overflow-y: auto;" aria-labelledby="colorDropdown">
              <li v-for="hairColor in hairColors" :key="hairColor">
                <div class="form-check" style="display: inline-block;">
                  <input class="form-check-input border border-black rounded-0" type="checkbox"
                   :id="hairColor" :value="hairColor" 
                   :checked="isFilterItemChecked(searchParams.colorsFilter, hairColor)"
                  @change="toggleFilter(searchParams.colorsFilter, hairColor, $event)">
                  <label class="form-check-label ms-2 fs-8" :for="hairColor">{{ getColor(hairColor) }}</label>
                </div>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown ms-2">
          <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="lengthDropdown" 
                    data-bs-toggle="dropdown" aria-expanded="false">
              <span class="fw-light fs-8">LENGTH</span>
            </button>
            <div class="dropdown-menu position-absolute top-25 start-0 px-2 rounded-0 border border-black" 
                style="width: 250px; height: 200px; overflow-y: auto;" 
                aria-labelledby="lengthDropdown">
              <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px;">
                <li v-for="len in hairLengths()" :key="len" style="list-style-type: none;">
                  <div class="form-check" style="display: flex; align-items: center;">
                    <input class="form-check-input border border-black rounded-0" type="checkbox" 
                          style="height: 15px; width: 15px;"
                          :id="len" :value="len" :checked="isFilterItemChecked(searchParams.lengthsFilter, len)"
                          @change="toggleFilter(searchParams.lengthsFilter, len, $event)">
                    <label class="form-check-label ms-2 fs-8" :for="len">{{ len }}</label>
                  </div>
                </li>
              </div>
            </div>
          </div>
        </li>

          <li class="nav-item">
            <button class="btn ms-3" style="background-color: transparent;border-color: transparent;"
            @click="clearFilter">
              <span class="fs-8 fw-light">CLEAR ALL</span> <span class="bi bi-x fw-light"></span>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

      <div class="row">
        <div class="col-6 col-md-3 mb-5" v-for="hairProduct in hairProductRef">
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