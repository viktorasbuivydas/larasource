<script setup>
import { Github, Star, Eye, GitFork } from 'lucide-vue-next';
</script>
<template>
    <div
        class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <a href="#">
            <img class="rounded-t-lg" src="/example-card.png" alt="product image" />
        </a>
        <div class="px-5 pb-5">
            <a href="#">
                <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Laravel framework
                </h5>
            </a>
            <div class="flex items-center mt-2.5 mb-5 gap-4">
                <span class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Eye /> Watch 33k
                </span>
                <span class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <GitFork /> Star 33k
                </span>
                <span class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Star />Fork 33k
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div></div>
                <Link href="" role="button" class="btn btn-primary btn-sm">
                <Github />
                Visit github page
                </Link>
            </div>
        </div>
    </div>
</template>