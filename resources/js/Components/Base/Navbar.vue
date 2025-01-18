<script setup>
import { Link } from '@inertiajs/vue3'
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
                    <!-- <Link :href="route('jobs.index')">Jobs</Link> -->
                </li>
                <li><a>Projects</a></li>
                <li><a>Packages</a></li>
                <!-- <li><a>Talent</a></li> -->
                <li>
                    <!-- <Link :href="route('jobs.create')" role="button" class="btn btn-primary btn-sm">Post a job</Link> -->
                </li>
            </ul>
        </div>
    </div>
</template>