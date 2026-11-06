
<script setup>
import PrivateNavbar from '../../Components/PrivateNavbar.vue';
import Footer from '../../Components/Footer.vue';
import SkaterCard from '../../Components/SkaterCard.vue'

defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="voting-page">
        <PrivateNavbar />

        <main class="voting-content">
            <section class="voting-heading">
                <p class="eyebrow">THE PEOPLE'S CHOICE</p>

                <h1>Cast Your Vote</h1>

                <p class="description">
                    Celebrate your favourite skaters.
                    Every vote makes a difference.
                </p>
            </section>

            <section class="skaters-grid">
                <template
                    v-for="category in categories"
                    :key="category.id"
                >
                    <SkaterCard
                        v-for="skater in category.skaters"
                        :key="skater.id"
                        :photo="skater.photo || '/images/skater-placeholder.jpg'"
                        :name="skater.name"
                        :category="category.name"
                    />
                </template>
            </section>

            <p v-if="categories.length === 0" class="empty-message">
                No categories are available yet.
            </p>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
.voting-page {
    width: 100%;
    min-height: 100vh;
    font-family: cursive;
}

.voting-content {
    width: 100%;
    padding: 70px 7%;
}

.voting-heading {
    text-align: center;
    margin-bottom: 55px;
}

.eyebrow {
    color: #9b6872;
    font-size: 12px;
    letter-spacing: 3px;
    margin-bottom: 12px;
}

.voting-heading h1 {
    font-size: clamp(32px, 5vw, 55px);
    margin: 0 0 15px;
}

.description {
    color: #888;
    font-size: 15px;
}

.skaters-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 22px;
}

.empty-message {
    text-align: center;
    color: #888;
    padding: 40px 0;
}

/* Smaller screens */
@media (max-width: 1000px) {
    .skaters-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

/* Mobile */
@media (max-width: 600px) {
    .voting-content {
        padding: 45px 5%;
    }

    .skaters-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
}
</style>
