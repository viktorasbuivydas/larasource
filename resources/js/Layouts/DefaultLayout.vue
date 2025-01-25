<script setup>
import BaseNavbar from '@/Components/Base/Navbar.vue';
import BaseFooter from '@/Components/Base/Footer.vue';
</script>

<template>
    <BaseNavbar />
    <div class="py-10 flex flex-col gap-5 container">
        <slot />
    </div>
    <BaseFooter />
</template>