<script setup>
import { Link } from '@inertiajs/vue3';

// Props are data passed from a parent component.
defineProps({
    id: {
        type: Number,
        required: true,
    },
    photo: {
        type: String,
        required: false,
        default: null,
    },
    name: {
        type: String,
        required: true,
    },
    category: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link :href="`/skaters/${id}`" class="skater-link">
        <article class="skater-card">
            <div class="skater-photo">
                <img
                    :src="photo ? `/storage/${photo}` : '/images/skater-placeholder.jpg'"
                    :alt="name"
                >
            </div>

            <div class="skater-info">
                <h3>{{ name }}</h3>
                <p>{{ category }}</p>
            </div>
        </article>
    </Link>
</template>

<style scoped>
.skater-link {
    display: block;
    color: inherit;
    text-decoration: none;
}

.skater-card {
    width: 100%;
    overflow: hidden;
    background: #f2eeee;
    cursor: pointer;
}

.skater-photo {
    width: 100%;
    aspect-ratio: 3 / 4;
    overflow: hidden;
}

.skater-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.skater-info {
    padding: 14px;
}

.skater-info h3 {
    font-size: 16px;
    font-weight: 600;
    color: #222;
    margin: 0 0 5px;
}

.skater-info p {
    font-size: 12px;
    color: #8b626a;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}
</style>
