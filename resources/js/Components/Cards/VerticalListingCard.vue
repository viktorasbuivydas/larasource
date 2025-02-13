<script setup>
import { Github, Star, Eye, GitFork, Archive } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

defineProps({
    repository: Object,
});
</script>
<template>
    <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <a :href="repository.html_url" target="_blank" nofollow>
            <img class="rounded-t-lg object-cover max-h-[175px] h-full w-full" :src="repository?.thumbnail"
                :alt="repository.name" />
        </a>
        <div class="px-5 py-5 flex flex-col gap-1">
            <div class="flex space-x-2 items-center">
                <div class="shrink-0">
                    <img :src="repository?.owners[0].avatar_url ?? 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' + repository.name"
                        alt="" width="30" height="30" class="rounded-full" />
                </div>
                <div class="break-words overflow-hidden">
                    <a :href="repository.html_url" nofollow target="_blank" class="hover:underline">
                        <h5 class="line-clamp-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ repository.name }}
                        </h5>
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap sm:flex-row sm:items-center mt-2.5 mb-5 gap-2 sm:gap-4">
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Eye /> Watch {{ repository.watchers_count }}
                </span>
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <GitFork /> Fork {{ repository.forks_count }}
                </span>
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1">
                    <Star />Star {{ repository.stargazers_count }}
                </span>
                <span class="w-fit text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1"
                    v-if="repository.archived">
                    <Archive />Archived
                </span>
            </div>
            <div class="flex items-center sm:justify-end">
                <a :href="repository.html_url" target="_blank" class="btn btn-primary btn-sm">
                    <Github />
                    Visit github page
                </a>
            </div>
        </div>
    </div>
</template>
