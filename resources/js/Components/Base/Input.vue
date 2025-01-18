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
    }
});
const input = ref(null);
const emit = defineEmits(['update:modelValue']);

function handleInput(event) {
    emit('update:modelValue', event.target.value);
}
</script>
<template>
    <label class="form-control w-full">
        <div class="label">
            <span class="label-text">{{ label }}</span>
        </div>
        <input type="text" :placeholder="placeholder" class="input-bordered w-full input" ref="input"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <div class="label" v-if="subLabel">
            <span class="label-text-alt">{{ subLabel }}</span>
        </div>
    </label>
</template>