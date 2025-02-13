<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import VerticalListingCard from '@/Components/Cards/VerticalListingCard.vue';
import { router, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue';
import Badge from '@/Components/Base/Badge.vue';

const props = defineProps({
    repositories: Array,
    tags: Array
})

const items = ref(props.repositories);
const isLoading = ref(false);
const nextPageUrl = ref(props.repositories.next_page_url);
const tag = ref(null)

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

// Watch for changes in filters and trigger refresh
watch(filters, () => {
    refreshData();
}, { deep: true });

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('filter[tag][en]')) {
        tag.value = urlParams.get('filter[tag][en]');
    }

    // Set initial filter values from URL params
    if (urlParams.get('filter[type]')) {
        filters.value.type = urlParams.get('filter[type]');
    }
    if (urlParams.get('filter[stars_between]')) {
        const [min, max] = urlParams.get('filter[stars_between]').split(',');
        filters.value.stars.min = parseInt(min);
        filters.value.stars.max = parseInt(max);
    }
    if (urlParams.get('filter[watchers_between]')) {
        const [min, max] = urlParams.get('filter[watchers_between]').split(',');
        filters.value.watchers.min = parseInt(min);
        filters.value.watchers.max = parseInt(max);
    }
    if (urlParams.get('filter[forks_between]')) {
        const [min, max] = urlParams.get('filter[forks_between]').split(',');
        filters.value.forks.min = parseInt(min);
        filters.value.forks.max = parseInt(max);
    }
});

const hasActiveFilters = computed(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const defaultFilters = {
        type: '',
        stars: { min: 0, max: 100000 },
        watchers: { min: 0, max: 100000 },
        forks: { min: 0, max: 100000 }
    };

    return filters.value.type !== defaultFilters.type ||
        filters.value.stars.min !== defaultFilters.stars.min ||
        filters.value.stars.max !== defaultFilters.stars.max ||
        filters.value.watchers.min !== defaultFilters.watchers.min ||
        filters.value.watchers.max !== defaultFilters.watchers.max ||
        filters.value.forks.min !== defaultFilters.forks.min ||
        filters.value.forks.max !== defaultFilters.forks.max ||
        urlParams.toString() !== '';
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

            // Update URL params to match current filters
            const urlParams = new URLSearchParams(window.location.search);
            if (filters.value.type) {
                urlParams.set('filter[type]', filters.value.type);
            }
            urlParams.set('filter[stars_between]', `${filters.value.stars.min},${filters.value.stars.max}`);
            urlParams.set('filter[watchers_between]', `${filters.value.watchers.min},${filters.value.watchers.max}`);
            urlParams.set('filter[forks_between]', `${filters.value.forks.min},${filters.value.forks.max}`);
            window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
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
                <div class="flex justify-between items-center">
                    <h2>Filters</h2>
                    <Link v-if="hasActiveFilters" :href="route('index')" class="btn btn-xs btn-ghost">
                    Clear filters
                    </Link>
                </div>
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
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <div v-if="tags.length > 0" class="flex flex-wrap gap-2">
                        <Link class="btn btn-xs btn-primary gap-2 hover:bg-indigo-500"
                            :href="route('index', { 'filter[tag]': tag.name })" v-for="tag in tags" :key="tag.id">
                        {{ tag.name.en }}
                        <div class="badge bg-indigo-800 border-0" v-if="tag.count > 0">{{ tag.count }}</div>
                        </Link>
                    </div>
                </div>
                <div v-if="hasActiveFilters" class="text-sm flex gap-2">
                    <Badge v-if="filters.type" :label="`Type: ${filters.type === '1' ? 'Project' : 'Package'}`" @clear="() => {
                        filters.type = '';
                        refreshData();
                    }" />
                    <Badge v-if="filters.stars.min > 0 || filters.stars.max < 100000"
                        :label="`Stars: ${filters.stars.min}-${filters.stars.max}`" @clear="() => {
                            filters.stars.min = 0;
                            filters.stars.max = 100000;
                            refreshData();
                        }" />
                    <Badge v-if="filters.watchers.min > 0 || filters.watchers.max < 100000"
                        :label="`Watchers: ${filters.watchers.min}-${filters.watchers.max}`" @clear="() => {
                            filters.watchers.min = 0;
                            filters.watchers.max = 100000;
                            refreshData();
                        }" />
                    <Badge v-if="filters.forks.min > 0 || filters.forks.max < 100000"
                        :label="`Forks: ${filters.forks.min}-${filters.forks.max}`" @clear="() => {
                            filters.forks.min = 0;
                            filters.forks.max = 100000;
                            refreshData();
                        }" />
                    <Badge v-if="tag" :label="tag" @clear="() => {
                        tag = null
                        refreshData()
                    }" />
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
