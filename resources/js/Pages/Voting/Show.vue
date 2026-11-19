<script setup>
import { ref } from 'vue';
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

const page = usePage();

const form = useForm({
    skater_id: props.skater.id,
});

// Tracks photos that fail to load, including photos with invalid paths.
const failedPhotos = ref(new Set());

const markPhotoFailed = (id) => {
    failedPhotos.value = new Set([
        ...failedPhotos.value,
        id,
    ]);
};

//Decorative social media icons (not clickable for now)
const socialIcons = [
    {
        name: 'Instagram',
        icon: 'fa-brands fa-instagram',
    },
    {
        name: 'Facebook',
        icon: 'fa-brands fa-facebook-f',
    },
    {
        name: 'X',
        icon: 'fa-brands fa-x-twitter',
    },
    {
        name: 'TikTok',
        icon: 'fa-brands fa-tiktok',
    },
];


const getSocialLinks = (person) => {
    return socialPlatforms.filter(platform => {
        const url = person?.[platform.key];

        return typeof url === 'string' &&
            /^https?:\/\//i.test(url);
    });
};

//submits the vote to Laravel.
const castVote = () => {
    form.post('/vote', {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="details-page">

        <PrivateNavbar />

        <!-- SUCCESS MESSAGE -->
        <div
            v-if="page.props.flash?.success"
            class="success-popup"
            role="status"
        >
            <span class="success-icon">✓</span>
            {{ page.props.flash.success }}
        </div>


        <main class="details-content">

            <!-- BACK NAVIGATION -->
            <Link href="/home" class="back-link">
                <i class="fa-solid fa-arrow-left"></i>
                BACK TO CONTESTANTS
            </Link>


            <!-- FEATURED CONTESTANT -->
            <section class="skater-details">

                <!-- PORTRAIT -->
                <div class="skater-image">

                    <img
                        v-if="skater.photo && !failedPhotos.has(skater.id)"
                        :src="`/storage/${skater.photo}`"
                        :alt="skater.name"
                        @error="markPhotoFailed(skater.id)"
                    />

                    <div v-else class="photo-placeholder">
                        <i class="fa-solid fa-user"></i>
                        <span>PHOTO UNAVAILABLE</span>
                    </div>

                    <div class="image-overlay"></div>

                    <span class="image-label">
                        THE NOMINEE
                    </span>

                </div>


                <!-- DETAILS -->
                <div class="skater-description">

                    <p class="eyebrow">
                        <span></span>
                        {{ skater.category.name }}
                    </p>

                    <p class="nominee-label">
                        MEET YOUR NOMINEE
                    </p>

                    <h1>{{ skater.name }}</h1>

                    <p class="description">
                        Every skater has a story. Every talent deserves
                        recognition. Show your support by voting for
                        your favourite in this category.
                    </p>


                    <!-- SOCIAL LINKS FOR FEATURED CONTESTANT -->
                     <div class="featured-socials">
                       <span>FOLLOW THE SKATER</span>

                     <div class="social-icons">
                       <span
                         v-for="social in socialIcons"
                         :key="social.name"
                         class="social-icon"
                         :title="social.name"
                         :aria-label="social.name"
                        >
                        <i :class="social.icon"></i>
                       </span>
                     </div>
                     </div>

                    <!-- VOTING STATUS -->
                    <div class="voting-status">
                        <p
                            v-if="votingStatus === 'not_configured'"
                            class="status-message"
                        >
                            <i class="fa-solid fa-circle-info"></i>
                            Voting has not been configured yet.
                        </p>
                        <p
                            v-else-if="votingStatus === 'upcoming'"
                            class="status-message">
                            <i class="fa-solid fa-clock"></i>
                            Voting has not started yet.
                        </p>

                        <p
                            v-else-if="votingStatus === 'closed'"
                            class="status-message"
                        >
                            <i class="fa-solid fa-lock"></i>
                            Voting has closed.
                        </p>

                        <p
                            v-else-if="hasVoted"
                            class="status-message voted"
                        >
                            <i class="fa-solid fa-circle-check"></i>
                            You have already voted in this category.
                        </p>

                    </div>


                    <!-- VOTE BUTTON -->
                    <button
                        v-if="votingStatus === 'open' && !hasVoted"
                        @click="castVote"
                        :disabled="form.processing"
                        class="vote-button"
                    >
                        <span>
                            {{ form.processing
                                ? 'SUBMITTING YOUR VOTE...'
                                : 'VOTE FOR ' + skater.name.toUpperCase()
                            }}
                        </span>

                        <i class="fa-solid fa-arrow-right"></i>
                    </button>


                    <!-- SERVER VALIDATION ERROR -->
                    <p v-if="form.errors.vote" class="error-message">
                        {{ form.errors.vote }}
                    </p>

                </div>

            </section>


            <!-- OTHER CONTESTANTS -->
            <section class="other-contestants">

                <div class="section-heading">

                    <div>
                        <p class="section-eyebrow">
                            THE COMPETITION
                        </p>

                        <h2>
                            OTHER CONTESTANTS
                            <span>IN THIS RACE.</span>
                        </h2>
                    </div>

                    <p class="contestant-count">
                        {{ String(otherSkaters.length).padStart(2, '0') }}
                        <span>
                            {{ otherSkaters.length === 1
                                ? 'OTHER NOMINEE'
                                : 'OTHER NOMINEES'
                            }}
                        </span>
                    </p>

                </div>


                <!-- VERTICAL CONTESTANT LIST -->
                <div
                    v-if="otherSkaters.length"
                    class="contestant-list"
                >

                    <div
                        v-for="(other, index) in otherSkaters"
                        :key="other.id"
                        class="contestant-row"
                    >

                        <!-- NUMBER -->
                        <span class="row-number">
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>

                        <Link
                            :href="`/skaters/${other.id}`"
                            class="contestant-profile"
                        >

                            <div class="contestant-thumbnail">

                                <img
                                    v-if="other.photo && !failedPhotos.has(other.id)"
                                    :src="`/storage/${other.photo}`"
                                    :alt="other.name"
                                    loading="lazy"
                                    @error="markPhotoFailed(other.id)"
                                />

                                <div v-else class="thumbnail-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                            </div>

                            <div class="contestant-info">
                                <h3>{{ other.name }}</h3>
                                <span>{{ skater.category.name }}</span>
                            </div>

                        </Link>


                        <!-- SOCIAL ICONS -->
                        <div class="contestant-socials">
                            <span
                            v-for="social in socialIcons"
                            :key="social.name"
                            class="social-icon"
                            :title="social.name"
                            :aria-label="social.name">
                            <i :class="social.icon"></i>
                           </span>
                        </div>

                        <!-- VIEW PROFILE -->
                        <Link
                            :href="`/skaters/${other.id}`"
                            class="view-profile"
                        >
                            VIEW
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </Link>

                    </div>

                </div>


                <p v-else class="no-contestants">
                    There are no other contestants in this category yet.
                </p>

            </section>

        </main>

        <Footer />

    </div>
</template>

<style scoped>
/* page */
.details-page {
    min-height: 100vh;
    width: 100%;
    overflow: hidden;

    color: #292329;
    font-family: 'DM Sans', Arial, sans-serif;

    background: linear-gradient(
        115deg,
        #fff5e7 0%,
        #fff5e7 38%,
        #fff0e4 100%
    );
}

.details-content {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 55px 7% 110px;
}


/* BACK LINK */

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 12px;

    margin-bottom: 40px;

    color: #800049;
    text-decoration: none;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;

    transition: gap .3s ease;
}

.back-link:hover {
    gap: 17px;
}


/* FEATURED CONTESTANT */

.skater-details {
    display: grid;
    grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
    align-items: center;
    gap: clamp(35px, 7vw, 95px);
}


/* MAIN PHOTO */

.skater-image {
    position: relative;
    width: 100%;
    max-width: 510px;
    aspect-ratio: 4 / 5;
    overflow: hidden;
    background: #e9dfe2;
}

.skater-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

.photo-placeholder {
    width: 100%;
    height: 100%;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 15px;

    color: #800049;
    background: linear-gradient(145deg, #f4e9ed, #dfcbd4);
}

.photo-placeholder i {
    font-size: 85px;
}

.photo-placeholder span {
    font-size: 9px;
    letter-spacing: 2px;
    font-weight: 700;
}

.image-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;

    background: linear-gradient(
        to bottom,
        transparent 60%,
        rgba(20, 10, 16, .35)
    );
}

.image-label {
    position: absolute;
    bottom: 20px;
    left: 20px;

    color: white;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 3px;
}


/* CONTESTANT INFORMATION */

.skater-description {
    max-width: 570px;
}

.eyebrow {
    display: flex;
    align-items: center;
    gap: 12px;

    color: #800049;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.eyebrow span {
    width: 27px;
    height: 1px;
    background: #800049;
}

.nominee-label {
    margin-top: 35px;

    color: #a18c96;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 3px;
}

.skater-description h1 {
    margin: 12px 0 20px;

    color: #292329;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: clamp(42px, 6vw, 75px);
    font-weight: 700;
    line-height: 1.05;
    text-transform: uppercase;
    overflow-wrap: anywhere;
}

.description {
    max-width: 450px;
    color: #796e73;
    font-size: 14px;
    line-height: 1.9;
}


/* FEATURED SOCIAL LINKS */
.featured-socials {
    display: flex;
    flex-direction: column;
    gap: 13px;
    margin-top: 25px;
}
.featured-socials > span {
    color: #a18c96;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
}
.social-icons {
    display: flex;
    align-items: center;
    gap: 13px;
    flex-wrap: wrap;}


/* SOCIAL ICONS */

.social-icon {
    width: 34px;
    height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    color: #800049;
    border: 1px solid rgba(128, 0, 73, .2);
    border-radius: 50%;

    font-size: 13px;
    text-decoration: none;

    transition: .3s ease;
}

.social-icon:hover {
    color: white;
    background: #800049;
    border-color: #800049;
    transform: translateY(-3px);
}


/* VOTING STATUS */

.voting-status {
    margin-top: 30px;
}

.status-message {
    display: flex;
    align-items: center;
    gap: 10px;

    color: #800049;
    font-size: 12px;
    line-height: 1.6;
}

.status-message i {
    font-size: 15px;
}

.status-message.voted {
    color: #16803c;
}


/* VOTE BUTTON */

.vote-button {
    width: fit-content;
    max-width: 100%;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;

    margin-top: 25px;
    padding: 17px 22px;

    border: 1px solid #800049;
    background: #800049;
    color: white;

    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;

    cursor: pointer;
    transition: .3s ease;
}

.vote-button:hover:not(:disabled) {
    background: #a0005d;
    border-color: #a0005d;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(128, 0, 73, .16);
}

.vote-button:disabled {
    opacity: .6;
    cursor: not-allowed;
}


/* ERROR */

.error-message {
    margin-top: 15px;
    color: #c62828;
    font-size: 12px;
}


/* SUCCESS POPUP */

.success-popup {
    position: fixed;
    top: 100px;
    right: 25px;
    z-index: 1100;

    display: flex;
    align-items: center;
    gap: 10px;

    max-width: min(380px, calc(100% - 30px));
    padding: 17px 22px;

    background: #fffaf4;
    border-left: 4px solid #16803c;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .12);

    color: #333;
    font-size: 13px;
}

.success-icon {
    color: #16803c;
    font-weight: bold;
    font-size: 18px;
}


/* OTHER CONTESTANTS */

.other-contestants {
    margin-top: 110px;
}

.section-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;

    padding-bottom: 22px;
    margin-bottom: 10px;

    border-bottom: 1px solid rgba(128, 0, 73, .2);
}

.section-eyebrow {
    color: #800049;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 10px;
}

.section-heading h2 {
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: clamp(27px, 4vw, 42px);
    line-height: 1.05;
}

.section-heading h2 span {
    color: #800049;
}

.contestant-count {
    display: flex;
    flex-direction: column;
    align-items: flex-end;

    color: #800049;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 25px;
    font-weight: 700;
}

.contestant-count span {
    color: #a18c96;
    font-family: 'DM Sans', Arial, sans-serif;
    font-size: 8px;
    letter-spacing: 1px;
}


/* VERTICAL LIST */

.contestant-list {
    width: 100%;
}

.contestant-row {
    display: grid;
    grid-template-columns: 38px minmax(0, 1fr) auto auto;
    align-items: center;
    gap: 20px;

    min-height: 105px;
    padding: 15px 5px;

    border-bottom: 1px solid rgba(128, 0, 73, .15);

    transition: background .25s ease;
}

.contestant-row:hover {
    background: rgba(255, 250, 244, .65);
}

.row-number {
    color: #a18c96;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 18px;
}


/* OTHER CONTESTANT PROFILE */

.contestant-profile {
    min-width: 0;

    display: flex;
    align-items: center;
    gap: 17px;

    color: inherit;
    text-decoration: none;
}

.contestant-thumbnail {
    width: 65px;
    height: 75px;
    flex-shrink: 0;
    overflow: hidden;
    background: #e9dfe2;
}

.contestant-thumbnail img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail-placeholder {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #800049;
    background: linear-gradient(145deg, #f4e9ed, #dfcbd4);
    font-size: 25px;
}

.contestant-info {
    min-width: 0;
}

.contestant-info h3 {
    color: #292329;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 19px;
    font-weight: 600;
    text-transform: uppercase;
    overflow-wrap: anywhere;
    transition: color .25s ease;
}

.contestant-profile:hover h3 {
    color: #800049;
}

.contestant-info span {
    display: block;
    margin-top: 6px;

    color: #9b858f;
    font-size: 9px;
    letter-spacing: 1px;
    text-transform: uppercase;
}


/* OTHER CONTESTANT SOCIALS */

.contestant-socials {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 9px;
}


/* VIEW PROFILE */

.view-profile {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    color: #800049;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-decoration: none;
    white-space: nowrap;

    transition: gap .25s ease;
}

.view-profile:hover {
    gap: 12px;
}


/* EMPTY LIST */

.no-contestants {
    padding: 35px 0;
    color: #877a80;
    font-size: 13px;
}


/* TABLETS */

@media (max-width: 900px) {
    .details-content {
        padding: 45px 5% 85px;
    }

    .skater-details {
        gap: 35px;
    }

    .contestant-row {
        gap: 13px;
    }

    .contestant-socials {
        gap: 6px;
    }
}


/* MOBILE */

@media (max-width: 700px) {
    .details-content {
        padding: 35px 5% 75px;
    }

    .back-link {
        margin-bottom: 25px;
    }

    .skater-details {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .skater-image {
        max-width: 480px;
        margin: 0 auto;
    }

    .skater-description {
        max-width: none;
    }

    .nominee-label {
        margin-top: 25px;
    }

    .other-contestants {
        margin-top: 75px;
    }

    .contestant-row {
        grid-template-columns: 28px minmax(0, 1fr) auto;
        gap: 10px;
        min-height: 90px;
    }

    .contestant-socials {
        grid-column: 2;
        justify-content: flex-start;
        padding-left: 82px;
        margin-top: -8px;
    }

    .contestant-profile {
        gap: 12px;
    }

    .contestant-thumbnail {
        width: 58px;
        height: 65px;
    }

    .contestant-info h3 {
        font-size: 16px;
    }

    .view-profile {
        grid-column: 3;
        grid-row: 1;
        font-size: 8px;
    }

    .social-icon {
        width: 29px;
        height: 29px;
        font-size: 11px;
    }
}


/* VERY SMALL PHONES */

@media (max-width: 390px) {
    .section-heading {
        align-items: flex-start;
    }

    .section-heading h2 {
        font-size: 25px;
    }

    .contestant-row {
        grid-template-columns: 22px minmax(0, 1fr);
        gap: 8px;
    }

    .contestant-socials {
        grid-column: 2;
        padding-left: 70px;
    }

    .view-profile {
        grid-column: 2;
        grid-row: 3;
        margin-left: 70px;
        margin-top: 3px;
    }

    .contestant-thumbnail {
        width: 52px;
        height: 60px;
    }

    .contestant-info h3 {
        font-size: 15px;
    }
}
</style>
