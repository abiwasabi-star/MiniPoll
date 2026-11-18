<script setup>
import { Link } from '@inertiajs/vue3';

//props r data passed from a parent component
defineProps({
    id: {
        type: Number,
        required: true,
    },
    photo: {
        type: String,
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

            <!-- CONTESTANT IMAGE -->
            <div class="skater-photo">

                <img
                    v-if="photo"
                    :src="`/storage/${photo}`"
                    :alt="name"
                    loading="lazy"
                />

                <div v-else class="contestant-photo-placeholder">
                    <i class="fa-solid fa-user"></i>
                </div>

                <!-- Image overlay -->
                <div class="photo-overlay"></div>

                <!-- Contestant number -->
                <span class="contestant-number">
                    NO. {{ String(id).padStart(3, '0') }}
                </span>

                <!-- Bottom image information -->
                <div class="photo-caption">
                    <span class="featured-label">THE NOMINEE</span>
                    <h3>{{ name }}</h3>
                </div>

                <!-- Arrow -->
                <span class="card-arrow">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </span>
            </div>

            <!-- CARD FOOTER -->
            <div class="skater-info">
                <span class="category-label">{{ category }}</span>
                <span class="view-label">VIEW PROFILE</span>
            </div>

        </article>
    </Link>
</template>

<style scoped>
.skater-link {
    display: block;
    width: 100%;
    color: inherit;
    text-decoration: none;
}

.skater-card {
    width: 100%;
    background: #fffaf4;
    border: 1px solid rgba(128, 0, 73, 0.10);
    overflow: hidden;
    transition: transform .35s ease, box-shadow .35s ease;
}

.skater-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 16px 35px rgba(45, 20, 32, 0.13);
}

/* IMAGE */

.skater-photo {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 5;
    overflow: hidden;
    background: #e9dfe2;
}

.skater-photo img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform .6s ease;
}

.skater-card:hover .skater-photo img {
    transform: scale(1.06);
}

/* IMAGE PLACEHOLDER */

.contestant-photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #800049;
    font-size: 55px;
    background: linear-gradient(145deg, #f4e9ed, #dfcbd4);
}

/* DARK GRADIENT */

.photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(20, 10, 16, .25) 0%,
        transparent 35%,
        rgba(20, 10, 16, .15) 55%,
        rgba(20, 10, 16, .88) 100%
    );
}

/* CONTESTANT NUMBER */

.contestant-number {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 7px 10px;

    color: white;
    background: rgba(20, 10, 16, .55);
    border: 1px solid rgba(255, 255, 255, .35);
    backdrop-filter: blur(8px);

    font-size: 9px;
    font-weight: 600;
    letter-spacing: 1.5px;
}

/* NAME ON IMAGE */

.photo-caption {
    position: absolute;
    bottom: 20px;
    left: 17px;
    right: 45px;
    color: white;
}

.featured-label {
    display: block;
    color: #e9a9ca;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 7px;
}

.photo-caption h3 {
    font-size: clamp(16px, 1.4vw, 21px);
    line-height: 1.15;
    font-weight: 700;
    overflow-wrap: anywhere;
}

/* ARROW */

.card-arrow {
    position: absolute;
    right: 15px;
    bottom: 20px;

    width: 30px;
    height: 30px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: white;
    border: 1px solid rgba(255, 255, 255, .65);
    font-size: 11px;

    transition: background .3s ease, transform .3s ease;
}

.skater-card:hover .card-arrow {
    background: #800049;
    border-color: #800049;
    transform: rotate(45deg);
}

/* CARD FOOTER */

.skater-info {
    min-height: 48px;
    padding: 12px 14px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.category-label {
    color: #800049;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.view-label {
    color: #9c8b91;
    font-size: 8px;
    letter-spacing: 1px;
    white-space: nowrap;
}

/* ACCESSIBILITY */

.skater-link:focus-visible {
    outline: 3px solid #800049;
    outline-offset: 4px;
}

@media (max-width: 600px) {
    .skater-info {
        padding: 10px;
    }

    .category-label {
        font-size: 8px;
    }

    .view-label {
        font-size: 7px;
    }
}
</style>
