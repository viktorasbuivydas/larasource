<script setup>
import { Github, Star, Eye, GitFork } from 'lucide-vue-next';
</script>
<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <a href="#">
            <img class="rounded-t-lg" src="/example-card.png" alt="product image" />
        </a>
        <div class="px-5 py-5 flex flex-col gap-1">
            <a href="#">
                <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Laravel framework
                </h5>
            </a>
            <div class="flex flex-wrap sm:flex-row sm:items-center mt-2.5 mb-5 gap-2 sm:gap-4">
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Eye /> Watch 33k
                </span>
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <GitFork /> Fork 33k
                </span>
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Star />Star 33k
                </span>
            </div>
            <div class="flex items-center sm:justify-end">
                <Link href="" role="button" class="btn btn-primary btn-sm">
                <Github />
                Visit github page
                </Link>
            </div>
        </div>
    </div>
</template>