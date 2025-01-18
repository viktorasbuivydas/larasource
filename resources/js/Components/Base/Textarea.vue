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
    cols: {
        type: Number,
    },
    rows: {
        type: Number,
    },
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
        <textarea :cols="cols" :rows="rows" :placeholder="placeholder" class="textarea-bordered w-full textarea"
            ref="input" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <div class="label" v-if="subLabel">
            <span class="label-text-alt">{{ subLabel }}</span>
        </div>
    </label>
</template>