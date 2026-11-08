<script setup>
defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Search...', //lets each page customize the search hint
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="search-input">
        <span class="search-icon">⌕</span>

        <input
            type="text"
            :value="modelValue"
            :placeholder="placeholder"
            @input="emit('update:modelValue', $event.target.value)"
        />

        <button
            v-if="modelValue"
            type="button"
            class="clear-search"
            @click="emit('update:modelValue', '')"
        >
            ×
        </button>
    </div>
</template>

<style scoped>
.search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    /* max-width: 420px; */
    padding: 0 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
}

.search-input:focus-within {
    border-color: #888;
}

.search-input input {
    flex: 1;
    min-width: 0;
    padding: 12px 0;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
}

.search-icon {
    font-size: 22px;
    color: #777;
}

.clear-search {
    border: none;
    background: transparent;
    font-size: 21px;
    cursor: pointer;
    color: #777;
}
</style>