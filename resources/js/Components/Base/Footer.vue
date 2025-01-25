<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Send } from 'lucide-vue-next';
import LogoLink from '@/Components/LogoLink.vue';

const date = computed(() => {
    let date = 2025;
    const currentYear = new Date().getFullYear();
    if (currentYear > date) {
        return '© ' + date + '-' + currentYear
    }

    return '©' + currentYear;
});
</script>

<template>
    <div class="bg-base-200 w-full flex flex-col gap-10 py-10 px-4 border-t border-gray-700">
        <div class="flex flex-col gap-2 items-center">

        </div>
        <div class="mx-auto flex space-x-4 items-center">
            <div class="flex flex-col gap-2">
                <div>Couldn't find specific project/package?</div>
                <Link href="" class="btn btn-primary">
                <Send />
                Send here!
                </Link>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <div class="flex justify-center items-center">
                <LogoLink />
            </div>
            <div>
                <h3 class="text-lg font-bold">For developers</h3>
                <ul>
                    <li>Projects</li>
                    <li>Packages</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold">For Open Source project owners</h3>
                <ul>
                    <li>
                        Post a job
                    </li>
                </ul>
            </div>
        </div>
        <div class="mx-auto">
            <b>
                LaraSource
            </b> {{ date }}
        </div>
    </div>
</template>