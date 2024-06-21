<script setup>
const props = defineProps({
    message: String,
    id: String
})
</script>

<template>
<div :id="id" class="toast position-absolute top-0 start-0 ms-2 " role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
    <strong class="me-auto">Notification</strong>
        <small class="text-body-secondary">just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
        <div class="toast-body">
        {{ message }}
    </div>
</div>
</template>