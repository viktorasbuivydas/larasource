<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

defineProps({

});
</script>
<template>
    <DefaultLayout>
        <div class="flex flex-col gap-10 mx-auto container">
            <div class="flex flex-col items-center justify-center space-y-4">
                <h1 class="font-bold text-4xl text-center">
                    Find all Laravel Open Source projects
                </h1>
                <p class="text-center">
                    Looking for a specific project? We update all the packages/projects on a daily basis.
                </p>
            </div>
        </div>
    </DefaultLayout>
</template>