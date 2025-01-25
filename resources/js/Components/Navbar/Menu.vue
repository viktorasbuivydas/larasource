<script setup>
import { AppWindow, Hammer, Presentation, HardHat, BadgePlus } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'

const menuItems = [
    {
        name: 'Projects',
        icon: AppWindow,
        url: '/projects',
    },
    {
        name: 'Packages',
        icon: Hammer,
        url: '/packages',
    },
    {
        name: 'Announcements',
        icon: Presentation,
        url: '/announcements',
    },
    {
        name: 'Jobs',
        icon: HardHat,
        url: '/jobs',
    },
];
</script>
<template>
    <ul class="items-center gap-2 px-1 menu grow justify-start">
        <li class="w-full lg:w-fit lg:shrink-1" v-for="item in menuItems" :key="item.name">
            <Link :href="item.url" role="button" class="btn btn-ghost btn-sm">
            <component :is="item.icon" />
            {{ item.name }}
            </Link>
        </li>
        <li>
            <Link href="" role="button" class="btn btn-primary btn-sm">
            <BadgePlus />
            Submit a project
            </Link>
        </li>
    </ul>
</template>