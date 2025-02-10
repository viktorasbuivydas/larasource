<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import VerticalListingCard from '@/Components/Cards/VerticalListingCard.vue';
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    repositories: Array
})

const items = ref(props.repositories);
const isLoading = ref(false);
const nextPageUrl = ref(props.repositories.next_page_url);

const loadMore = () => {
    if (!nextPageUrl.value) {
        return;
    }

    isLoading.value = true;

    axios.get(nextPageUrl.value)
        .then(response => {
            nextPageUrl.value = response.data.next_page_url;
            console.log
            items.value.data.push(...response.data.data);
        })
        .finally(() => {
            isLoading.value = false;
        })
}


</script>

<template>
    <DefaultLayout>
        <div class="flex flex-col gap-10 mx-auto container mb-10">
            <div class="flex flex-col items-center justify-center space-y-4">
                <h1 class="font-bold text-4xl text-center">
                    Explore the Laravel Open Source Universe
                </h1>
                <p class="text-center">
                    Browse hand-picked Laravel projects, updated daily to fuel your next development journey
                </p>
            </div>
        </div>
        <div class="flex gap-5">
            <div class="bg-base-200 max-w-[200px] p-2 w-full h-fit hidden xl:flex lg:flex-col gap-2">
                <h3>Filters</h3>
                <div>
                    Type
                    <select name="" id="">
                        <option value="">All</option>
                        <option value="">Project</option>
                        <option value="">Package</option>
                    </select>
                </div>
                <div>
                    Slider forks
                </div>
                <div>Slider stars</div>
                <div>Slider watches</div>
                <div>
                    show TagsInputInput
                </div>
            </div>
            <div>
                <template v-if="items.data.length > 0">
                    <div class="grow p-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <VerticalListingCard v-for="repository in items.data" :key="repository.id"
                            :repository="repository" />
                    </div>

                    <div class="flex justify-center grow mt-4">

                        <button class="btn btn-primary flex gap-2" @click="loadMore" v-if="nextPageUrl">
                            <div>Load more</div>
                            <span class="loading loading-spinner" v-if="isLoading"></span>
                        </button>
                    </div>

                </template>
                <template v-else>
                    <div class="flex flex-col space-y-4">
                        <h1 class="font-bold text-4xl text-center">
                            No results found
                        </h1>
                        <p class="text-center">
                            Try a different search query or filter
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </DefaultLayout>
</template>
