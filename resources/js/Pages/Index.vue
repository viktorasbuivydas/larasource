<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import VerticalListingCard from '@/Components/Cards/VerticalListingCard.vue';
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({
    repositories: Array,
    tags: Array
})

const items = ref(props.repositories);
const isLoading = ref(false);
const nextPageUrl = ref(props.repositories.next_page_url);

const filters = ref({
    type: '',
    stars: {
        min: 0,
        max: 100000
    },
    watchers: {
        min: 0,
        max: 100000
    },
    forks: {
        min: 0,
        max: 100000
    }
});

const refreshData = () => {
    isLoading.value = true;

    router.get(route('index'), {
        filter: {
            type: filters.value.type,
            stars_between: `${filters.value.stars.min},${filters.value.stars.max}`,
            watchers_between: `${filters.value.watchers.min},${filters.value.watchers.max}`,
            forks_between: `${filters.value.forks.min},${filters.value.forks.max}`
        }
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            items.value = response.props.repositories;
            nextPageUrl.value = response.props.repositories.next_page_url;
            isLoading.value = false;
        }
    });
};

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
            <div class="bg-base-200 max-w-[300px] p-4 w-full h-fit hidden xl:flex lg:flex-col gap-4">
                <h2>Filters</h2>
                <div class="flex flex-col gap-2">
                    <h3>Type</h3>
                    <select v-model="filters.type" class="bg-gray-800 border-0">
                        <option value="">All</option>
                        <option value="1">Project</option>
                        <option value="2">Package</option>
                    </select>
                </div>
                <div>
                    <h3>Stars</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" v-model="filters.stars.min" min="0">
                        <input type="number" v-model="filters.stars.max" min="0" max="100000">
                    </div>
                </div>
                <div>
                    <h3>Watchers</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" v-model="filters.watchers.min" min="0">
                        <input type="number" v-model="filters.watchers.max" min="0" max="100000">
                    </div>
                </div>
                <div>
                    <h3>Forks</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" v-model="filters.forks.min" min="0">
                        <input type="number" v-model="filters.forks.max" min="0" max="100000">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="btn btn-primary grow" @click="refreshData">Filter</button>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <div v-if="tags.length > 0" class="flex flex-wrap gap-2">
                        <div class="btn btn-xs btn-primary gap-2 hover:bg-indigo-500" v-for="tag in tags" :key="tag.id">
                            {{ tag.name.en }}
                            <div class="badge bg-indigo-800 border-0" v-if="tag.count > 0">{{ tag.count }}</div>
                        </div>
                    </div>
                </div>
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
