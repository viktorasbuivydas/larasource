<script setup>
import { Link } from '@inertiajs/vue3'
import { Menu } from 'lucide-vue-next'
import LogoLink from '@/Components/LogoLink.vue';
import { ref } from 'vue';
import NavbarMenu from '@/Components/Navbar/Menu.vue';
const opened = ref(false);

const toggleMenu = () => {
    opened.value = !opened.value;
}
</script>

<template>
    <div class="bg-base-200 navbar border-b border-gray-700">
        <div class="flex flex-row items-center justify-between grow">
            <div class="flex-1">
                <LogoLink />
            </div>
            <div class="flex-none lg:hidden">
                <button class="btn btn-ghost btn-sm" @click="toggleMenu">
                    <Menu />
                </button>
            </div>
        </div>
        <div class="flex-none hidden lg:flex">
            <NavbarMenu class="menu-horizontal" />
        </div>
    </div>
    <!-- mobile menu -->
    <div class="bg-base-200 navbar border-b border-gray-700 flex lg:hidden" v-if="opened">
        <NavbarMenu class="menu-vertical" />
    </div>

</template>