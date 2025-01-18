<script setup>
import { ref } from 'vue'

defineProps({
    label: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Type here',
    },
    modelValue: {
        type: String,
        default: '',
    },
    subLabel: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    }
});
const input = ref(null);
const emit = defineEmits(['update:modelValue']);

function handleInput(event) {
    emit('update:modelValue', event.target.value);
}
</script>
<template>
    <label class="flex flex-col gap-2 focus:outline-none">
        {{ label }}
        <div class="flex flex-row items-center gap-2 input input-bordered grow">
            <slot name="icon"></slot>
            <input :type="type" :placeholder="placeholder" class="grow input focus:border-0" ref="input"
                :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        </div>
    </label>
</template>