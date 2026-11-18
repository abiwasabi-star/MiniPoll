<script setup>
import PrivateNavbar from '../../Components/PrivateNavbar.vue';
import Footer from '../../Components/Footer.vue';
import SkaterCard from '../../Components/SkaterCard.vue';
import SearchInput from '../../Components/SearchInput.vue';

import { ref, computed } from 'vue'; //creates derived reactive data

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');

//search input func
const filteredCategories = computed(() => {
    const term = search.value.trim().toLowerCase();

    return props.categories
        .map(category => {
            const skaters = category.skaters ?? [];

            return {
                ...category,
                skaters: skaters.filter(skater =>
                    skater.name.toLowerCase().includes(term) ||
                    category.name.toLowerCase().includes(term)
                ),
            };
        })
        .filter(category => category.skaters.length > 0);
});

//total number of contestants currently displayed
const filteredSkaterCount = computed(() => {
    return filteredCategories.value.reduce(
        (total, category) => total + category.skaters.length,
        0
    );
});
</script>

<template>
    <div class="voting-page">

        <PrivateNavbar />

        <main class="voting-content">

            <!-- PAGE INTRODUCTION -->
            <section class="voting-heading">

                <p class="eyebrow">
                    <span></span>
                    THE PEOPLE'S CHOICE · 2026
                    <span></span>
                </p>

                <h1>
                    YOUR VOTE.
                    <span>THEIR MOMENT.</span>
                </h1>

                <p class="description">
                    Recognise the talent. Celebrate the creativity.
                    Give your favourite skaters the recognition they deserve.
                </p>

                <!-- SEARCH -->
                <div class="search-area">
                    <SearchInput
                        v-model="search"
                        placeholder="Search contestants or categories..."
                    />
                </div>

                <!-- RESULTS COUNT -->
                <div class="results-summary">
                    <span class="summary-line"></span>

                    <p>
                        SHOWING
                        <strong>{{ filteredSkaterCount }}</strong>
                        {{ filteredSkaterCount === 1 ? 'CONTESTANT' : 'CONTESTANTS' }}
                    </p>

                    <span class="summary-line"></span>
                </div>

            </section>


            <!-- CATEGORY LIST -->
            <section class="category-list">

                <div
                    v-for="(category, index) in filteredCategories"
                    :key="category.id"
                    class="category-section"
                >

                    <!-- CATEGORY HEADING -->
                    <div class="category-heading">

                        <div class="category-title">
                            <span class="category-number">
                                {{ String(index + 1).padStart(2, '0') }}
                            </span>

                            <div>
                                <p class="category-overline">
                                    VOTING CATEGORY
                                </p>

                                <h2>{{ category.name }}</h2>
                            </div>
                        </div>

                        <div class="category-count">
                            <strong>
                                {{ String(category.skaters.length).padStart(2, '0') }}
                            </strong>

                            <span>
                                {{ category.skaters.length === 1 ? 'NOMINEE' : 'NOMINEES' }}
                            </span>
                        </div>

                    </div>


                    <!-- CONTESTANTS -->
                    <div class="skaters-grid">

                        <SkaterCard
                            v-for="skater in category.skaters"
                            :key="skater.id"
                            :id="skater.id"
                            :photo="skater.photo"
                            :name="skater.name"
                            :category="category.name"
                        />

                    </div>

                </div>

            </section>


            <!-- EMPTY SEARCH STATE -->
            <div
                v-if="filteredCategories.length === 0"
                class="empty-message"
            >
                <div class="empty-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <h2>NO SKATERS FOUND.</h2>

                <p>
                    {{
                        search
                            ? `We couldn't find any contestants or categories matching "${search}".`
                            : 'There are currently no categories or contestants available.'
                    }}
                </p>

                <button
                    v-if="search"
                    class="clear-search"
                    @click="search = ''"
                >
                    CLEAR SEARCH
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </main>

        <Footer />

    </div>
</template>

<style scoped>
/* PAGE */

.voting-page {
    width: 100%;
    min-height: 100vh;
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

.voting-content {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 75px 7% 110px;
}


/* PAGE INTRODUCTION */

.voting-heading {
    text-align: center;
    max-width: 850px;
    margin: 0 auto 90px;
}

.eyebrow {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 13px;

    color: #800049;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 3px;
    margin-bottom: 25px;
}

.eyebrow span {
    width: 28px;
    height: 1px;
    background: #800049;
}

.voting-heading h1 {
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: clamp(48px, 7vw, 88px);
    line-height: .98;
    letter-spacing: -2px;
    font-weight: 800;
    margin: 0 0 25px;
}

.voting-heading h1 span {
    display: block;
    color: #800049;
}

.description {
    max-width: 500px;
    margin: 0 auto;
    color: #796e73;
    font-size: 14px;
    line-height: 1.9;
}

.search-area {
    width: min(100%, 510px);
    margin: 35px auto 25px;
}

.results-summary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.results-summary p {
    color: #93858b;
    font-size: 9px;
    letter-spacing: 2px;
}

.results-summary strong {
    color: #800049;
}

.summary-line {
    width: 35px;
    height: 1px;
    background: #d6bdc8;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: 85px;
}

.category-section {
    width: 100%;
}

.category-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    padding-bottom: 20px;
    margin-bottom: 28px;
    border-bottom: 1px solid rgba(128, 0, 73, .2);
}

.category-title {
    display: flex;
    align-items: center;
    gap: 17px;
}

.category-number {
    color: #800049;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 38px;
    font-weight: 700;
    line-height: 1;
}

.category-overline {
    color: #a18c96;
    font-size: 8px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 5px;
}

.category-title h2 {
    color: #292329;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: clamp(22px, 3vw, 32px);
    font-weight: 700;
    line-height: 1.1;
    text-transform: uppercase;
}

.category-count {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 3px;
}

.category-count strong {
    color: #800049;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 25px;
}

.category-count span {
    color: #a18c96;
    font-size: 8px;
    letter-spacing: 1.5px;
}

.skaters-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 22px;
    align-items: start;
}
.empty-message {
    max-width: 500px;
    margin: 30px auto 80px;
    padding: 65px 25px;
    text-align: center;
    border: 1px solid rgba(128, 0, 73, .12);
    background: rgba(255, 250, 244, .6);
}

.empty-icon {
    color: #800049;
    font-size: 28px;
    margin-bottom: 20px;
}

.empty-message h2 {
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    font-size: 26px;
    margin-bottom: 12px;
}

.empty-message p {
    color: #877a80;
    font-size: 13px;
    line-height: 1.8;
}

.clear-search {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-top: 25px;
    padding: 12px 18px;

    border: none;
    background: #800049;
    color: white;
    font-size: 10px;
    letter-spacing: 1px;
    cursor: pointer;
    transition: .3s ease;
}

.clear-search:hover {
    background: #a0005d;
    transform: translateY(-2px);
}

/* Smaller desktops */
@media (max-width: 1250px) {
    .skaters-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
    }
}


/* Tablets */
@media (max-width: 950px) {
    .voting-content {
        padding: 65px 5% 90px;
    }

    .skaters-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .voting-heading {
        margin-bottom: 70px;
    }
}


/* Small tablets and phones */
@media (max-width: 650px) {
    .voting-content {
        padding: 50px 5% 75px;
    }

    .voting-heading {
        margin-bottom: 60px;
    }

    .voting-heading h1 {
        font-size: clamp(43px, 11vw, 65px);
        letter-spacing: -1px;
    }

    .description {
        font-size: 13px;
    }

    .category-list {
        gap: 60px;
    }

    .skaters-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .category-heading {
        margin-bottom: 20px;
    }

    .category-number {
        font-size: 31px;
    }
}


/* Very narrow phones */
@media (max-width: 370px) {
    .skaters-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .category-heading {
        gap: 10px;
    }

    .category-title {
        gap: 10px;
    }

    .category-title h2 {
        font-size: 20px;
    }
}
</style>
