<script setup>
import BaseNavbar from '@/Components/Base/Navbar.vue';
import BaseFooter from '@/Components/Base/Footer.vue';
</script>

<template>
    <BaseNavbar />
    <div class="pt-10">
        <slot />
    </div>
    <BaseFooter />
</template>