<script setup>
import { Link } from '@inertiajs/vue3'
import { AppWindow, Hammer, Presentation, HardHat, BadgePlus } from 'lucide-vue-next'
</script>

<template>
    <div class="bg-base-200 navbar border-b border-gray-700">
        <div class="flex-1">
            <Link :href="route('index')" class="text-xl btn btn-ghost">
            LaraSource
            </Link>
        </div>
        <div class="flex-none">
            <ul class="items-center space-x-2 px-1 menu menu-horizontal">
                <li>
                    <a>
                        <AppWindow />
                        Projects
                    </a>
                </li>
                <li>
                    <a>
                        <Hammer />
                        Packages
                    </a>
                </li>
                <li>
                    <a>
                        <Presentation />
                        Announcements
                    </a>
                </li>
                <li>
                    <a>
                        <HardHat />
                        Jobs
                    </a>
                </li>
                <li>
                    <Link href="" role="button" class="btn btn-primary btn-sm">
                    <BadgePlus />
                    Post a job
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>