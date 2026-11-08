
<script setup>
import PrivateNavbar from '../../Components/PrivateNavbar.vue';
import Footer from '../../Components/Footer.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    skater: {
        type: Object,
        required: true,
    },
    otherSkaters: {
        type: Array,
        default: () => [],
    },
    votingStatus: {
        type: String,
        required: true,
    },
    hasVoted: {
        type: Boolean,
        required: true,
    },
});

//it holds the contestant ID being submitted.
const form = useForm({
    skater_id: props.skater.id,
});

//submits the vote to Laravel.
const castVote = () => {
    form.post('/vote', {
        preserveScroll: true,
    });
};

const page = usePage();
</script>

<template>
    <div class="details-page">
        <PrivateNavbar />

        <div
            v-if="page.props.flash?.success"
            class="success-popup">
            <span class="success-icon">✓</span>
            {{ page.props.flash.success }}
        </div>

        <main class="details-content">

            <Link href="/home" class="back-link">
                ← Back to contestants
            </Link>

            <section class="skater-details">

                <div class="skater-image">
                    <img
                        :src="skater.photo
                            ? `/storage/${skater.photo}`
                            : '/images/skater-placeholder.jpg'"
                        :alt="skater.name"
                    >
                </div>

                <div class="skater-description">
                    <p class="eyebrow">{{ skater.category.name }}</p>

                    <h1>{{ skater.name }}</h1>

                    <p class="description">
                        Support your favourite skater by casting your vote.
                    </p>

                    <p v-if="votingStatus === 'not_configured'" class="vote-status">
                      Voting has not been configured yet.</p>

                    <p v-else-if="votingStatus === 'upcoming'" class="vote-status">
                      Voting has not started yet.</p>

                    <p v-else-if="votingStatus === 'closed'" class="vote-status">
                    Voting has closed.</p>

                    <p v-else-if="hasVoted" class="vote-status">
                    You have already voted in this category.</p>

                    <button
                        v-if="votingStatus === 'open' && !hasVoted"
                        @click="castVote"
                        :disabled="form.processing"
                        class="vote-button">
                        {{ form.processing ? 'Submitting...' : 'Vote for ' + skater.name }}
                    </button>

                    <p v-if="form.errors.vote" class="error-message">
                        {{ form.errors.vote }}
                    </p>

                </div>

            </section>

            <section class="other-contestants">
                <h2>Other contestants in this category</h2>

                <div v-if="otherSkaters.length" class="contestant-list">
                    <Link
                        v-for="other in otherSkaters"
                        :key="other.id"
                        :href="`/skaters/${other.id}`"
                        class="contestant-name"
                    >
                        {{ other.name }}
                    </Link>
                </div>

                <p v-else>No other contestants in this category yet.</p>
            </section>

        </main>

        <Footer />
    </div>
</template>

<style scoped>
.details-page {
    min-height: 100vh;
    font-family: cursive;
}

.details-content {
    padding: 55px 7%;
}

.back-link {
    display: inline-block;
    margin-bottom: 35px;
    color: #800049;
    text-decoration: none;
}

.skater-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 55px;
    align-items: center;
}

.skater-image {
    width: 100%;
    max-width: 500px;
    aspect-ratio: 3 / 4;
    overflow: hidden;
}

.skater-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.eyebrow {
    color: #800049;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-size: 13px;
}

.skater-description h1 {
    font-size: clamp(32px, 5vw, 50px);
    margin: 10px 0;
}

.vote-button {
    margin-top: 25px;
    padding: 14px 30px;
    background: #800049;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.vote-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.vote-status {
    margin-top: 20px;
    color: #800049;
}

.error-message {
    margin-top: 15px;
    color: #d00000;
}

.success-popup {
    position: fixed;
    top: 90px;
    right: 25px;
    z-index: 1000;
    max-width: 380px;
    padding: 18px 22px;
    background: #fff;
    border-left: 5px solid #16803c;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    color: #333;
    font-size: 14px;
}

.success-icon {
    color: #16803c;
    font-weight: bold;
    margin-right: 8px;
}

.vote-status {
    margin-top: 20px;
    color: #800049;
}

.description {
    color: #888;
    line-height: 1.7;
}

.other-contestants {
    margin-top: 65px;
}

.other-contestants h2 {
    font-size: 23px;
    margin-bottom: 20px;
}

.contestant-list {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.contestant-name {
    padding: 12px 20px;
    background: #f2eeee;
    color: #800049;
    text-decoration: none;
}

.contestant-name:hover {
    background: #e8dce2;
}

@media (max-width: 700px) {
    .details-content {
        padding: 35px 5%;
    }

    .skater-details {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .skater-image {
        max-width: 100%;
    }
}
</style>
