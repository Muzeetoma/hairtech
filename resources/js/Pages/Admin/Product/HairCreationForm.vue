<script setup>
import AppLayout from '../../../Layouts/App.vue'
import { reactive, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

defineProps({
   errors: Object,
   hairTypes: Object,
   hairColors: Object,
 })

const form = reactive({
  images: [],
  details: [],
  detail: null,
  type: null,
  color: null,
  price: null,
  discount: null,
  description: null,
  length: null,
});
function handleFiles(event) {
  form.images = Array.from(event.target.files);
}
function addDetail() {
  form.details.push({ detail: ''});
}
function removeDetail(index) {
  form.details.splice(index, 1);
}
function submit() {
  const formData = new FormData()
  form.images.forEach(image => formData.append('images[]', image))
  formData.append('detail', form.detail);
  formData.append('type', form.type);
  formData.append('color', form.color);
  formData.append('price', form.price);
  formData.append('discount', form.discount);
  formData.append('description', form.description);
  formData.append('length', form.length);
  form.details.forEach((detail, index) => {
    formData.append(`details[${index}][detail]`, detail.detail);
  });
  router.post('/admin/hair', formData);
}
</script>

<template>
  <AppLayout>
    <br><br>
    <div class="conatiner px-1 px-md-5">
      <ul class="nav nav-tabs">
      <li class="nav-item">
        <Link class="nav-link active text-dark" :href="route('admin.show.hair')">Hair</Link>
      </li>
      <li class="nav-item">
        <Link :href="route('admin.show.hair.item')" class="nav-link text-dark">Hair Item</Link>
      </li>
    </ul>
    </div>
    <br><br>

    <div class="row">
    <div class="col-12 col-md-4 "></div>  
      <div class="col-12 col-md-4">
        <div class="container">
            <h2 class="fw-bold mt-2">Create Hair</h2>
          <form @submit.prevent="submit" class="border mt-4 p-2 p-md-4">

            <div class="row">
              <div class="col-12 mb-4">
                <label for="images" class="form-label">Images</label>
                <input type="file" class="form-control rounded-0" id="images" multiple @change="handleFiles" placeholder="Images">
                <small class="text-danger ms-1" v-if="errors.images">{{ errors.images }}</small>
              </div>           
            </div>

            <div class="mb-4">
              <label for="type" class="form-label">Hair Type</label>
              <select class="form-select" v-model="form.type">
              <option v-for="type in hairTypes" id="type" :value=type >{{ type }}</option>
              </select>
               <small class="text-danger ms-1" v-if="errors.type">{{ errors.type }}</small>
            </div>

           <div class="mb-4">
              <label for="color" class="form-label">Hair Color</label>
              <input type="color" list="colors" id="color" v-model="form.color">
              <datalist id="colors">
              <option v-for="color in hairColors" :key="color" :value=color>{{ color }}</option>
              </datalist>
              <small class="text-danger ms-1" v-if="errors.color">{{ errors.color }}</small>
            </div>

            <div class="mb-4">
              <label for="length" class="form-label">Hair Length</label>
              <input type="number" class="form-control rounded-0"
              id="length" v-model="form.length" placeholder="Enter hair length"/>
              <small class="text-danger ms-1" v-if="errors.length">{{ errors.length }}</small>
            </div>

            <div class="row mb-4">
              <div class="col-6">
                <div class="mb-3">
              <label for="price" class="form-label">Hair Price</label>
              <input type="number" class="form-control rounded-0"
              id="price" v-model="form.price" placeholder="Enter hair price"/>
              <small class="text-danger ms-1" v-if="errors.price">{{ errors.price }}</small>
            </div>
              </div>
             
              <div class="col-6">
                <div class="mb-3">
              <label for="discount" class="form-label">Hair Discount</label>
              <input type="number" class="form-control rounded-0"
              id="discount" v-model="form.discount" placeholder="Enter hair discount"/>
              <small class="text-danger ms-1" v-if="errors.discount">{{ errors.discount }}</small>
            </div>
              </div>

            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Hair Description</label>
              <textarea type="text" class="form-control rounded-0" rows="5"
              id="description" v-model="form.description" placeholder="Enter hair description">
                </textarea>
              <small class="text-danger ms-1" v-if="errors.description">{{ errors.description }}</small>
            </div>

            <div class="mb-2">
              <label for="price" class="form-label">Hair Details</label><br>
            <div class="mb-4 border p-2">
                <button type="button" class="btn btn-light border py-0 px-2 mb-3" @click="addDetail">
                  <small>Add Detail</small><span class="bi bi-plus fs-5 ms-1"></span>
                </button>
                <div v-for="(detail, index) in form.details" :key="index" class="row mb-2">
                  <div class="col-10">
                    <input type="text" class="form-control mb-1" v-model="detail.detail" placeholder="Detail">
                  </div>
                  <div class="col-2">
                    <button type="button" class="btn btn-white" @click="removeDetail(index)">
                    <span class="bi bi-trash" style="color: red;"></span>
                  </button>
                  </div>
                </div>
                <small class="text-danger ms-1" v-if="errors.details">{{ errors.details }}</small>
              </div>
            </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark rounded-0">Submit</button>
              </div>
          </form>
              
         <center> 
            <div class="container mt-3 p-2 text-center border rounded-3" v-if="$page.props.flash.error">
                      <i class="bi bi-exclamation-circle text-danger me-2"></i>
                      <small class="text-danger"> {{ $page.props.flash.error }} </small>
          </div>
        </center>
        </div>
      </div>
      <div class="col-12 col-md-4 "></div>
    </div>

</AppLayout>
</template>