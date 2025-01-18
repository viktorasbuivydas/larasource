<script setup lang="ts">
import { ref, watch } from 'vue'
import { ComboboxAnchor, ComboboxContent, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxItemIndicator, ComboboxLabel, ComboboxRoot, ComboboxTrigger, ComboboxViewport, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText, TagsInputRoot } from 'radix-vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
    placeholder: {
        type: String
    },
    label: {
        type: String
    },
    options: {
        type: Array
    },
    defaultValue: {
        type: Array
    },
    locale: {
        type: String,
        default: 'en'
    }
});

const searchTerm = ref('')
const values = ref(props.defaultValue ?? [])

watch(values, () => {
    searchTerm.value = ''
}, { deep: true })

const displayName = (tag) => {
    if (typeof tag === 'string') {
        return tag;
    }

    return tag?.name[props.locale]
}
</script>

<template>
    <ComboboxRoot v-model="values" v-model:search-term="searchTerm" multiple class="relative mx-auto my-4 w-full">
        <ComboboxAnchor
            class="inline-flex justify-between items-center gap-[5px] border-gray-700 bg-base-100 focus:shadow-[0_0_0_2px] focus:shadow-black p-2 border rounded-lg w-full text-[13px] text-grass11 data-[placeholder]:text-grass9 leading-none outline-none">
            <TagsInputRoot v-slot="{ modelValue: tags }" :model-value="values" delimiter=""
                class="flex flex-wrap items-center gap-2 rounded-lg grow">
                <TagsInputItem v-for="item in tags" :key="item" :value="item"
                    class="flex justify-center items-center gap-2 bg-primary aria-[current=true]:bg-primary px-2 py-1 rounded text-black">
                    <TagsInputItemText class="text-sm">{{ displayName(item) }}
                    </TagsInputItemText>
                    <TagsInputItemDelete>
                        <Icon icon="lucide:x" />
                    </TagsInputItemDelete>
                </TagsInputItem>

                <ComboboxInput as-child class="border-0 focus:border-none focus:ring-gray-700">
                    <TagsInputInput :placeholder="placeholder"
                        class="flex-1 !bg-transparent px-1 rounded placeholder:text-mauve10 focus:outline-none"
                        @keydown.enter.prevent />
                </ComboboxInput>
            </TagsInputRoot>

            <ComboboxTrigger>
                <Icon icon="radix-icons:chevron-down" class="w-4 h-4 text-grass11" />
            </ComboboxTrigger>
        </ComboboxAnchor>
        <ComboboxContent
            class="z-10 absolute border-gray-600 bg-base-100 mt-2 border rounded w-full will-change-[opacity,transform] data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade data-[side=right]:animate-slideLeftAndFade data-[side=top]:animate-slideDownAndFade overflow-hidden">
            <ComboboxViewport class="p-[5px] h-full max-h-[300px]">
                <ComboboxEmpty class="py-2 font-medium text-center text-gray-400 text-xs" />

                <ComboboxGroup>
                    <ComboboxLabel v-if="label" class="px-[25px] text-mauve11 text-xs leading-[25px]">
                        {{ label }}
                    </ComboboxLabel>

                    <ComboboxItem v-for="(option, index) in options" :key="index"
                        class="relative flex items-center data-[highlighted]:bg-primary pr-[35px] pl-[25px] rounded-[3px] h-[25px] text-[13px] text-primary data-[disabled]:text-black data-[highlighted]:text-black leading-none data-[disabled]:pointer-events-none select-none data-[highlighted]:outline-none"
                        :value="option">
                        <ComboboxItemIndicator class="inline-flex left-0 absolute justify-center items-center w-[25px]">
                            <Icon icon="radix-icons:check" />
                        </ComboboxItemIndicator>
                        <span>
                            {{ displayName(option) }}
                        </span>
                    </ComboboxItem>
                </ComboboxGroup>
            </ComboboxViewport>
        </ComboboxContent>
    </ComboboxRoot>
</template>