<script setup>
import {usePage} from '@inertiajs/vue3'; //accesses the current page's props and data

import AdminNavbar from '../../Components/AdminNavbar.vue';
import Footer from '../../Components/Footer.vue';

const props = defineProps({
    totalSkaters: Number,
    totalCategories: Number,
    categories: Array,
});

const page = usePage(); //accesses the current page's props and data

</script>

<template>
    <div class="admin-page">
        <AdminNavbar />

        <div
           v-if="page.props.flash?.success"
           class="flash-message success">
           {{ page.props.flash.success }}
        </div>

        <div
            v-if="page.props.flash?.error"
            class="flash-message error-message">
            {{ page.props.flash.error }}
        </div>

        <main class="admin-content">
            <p class="eyebrow">ADMINISTRATION</p>
            <h1>Dashboard</h1>
            <p class="description">
                Manage your skating awards contestants.
            </p>

            <section class="stats">
                <div class="stat-card">
                    <p>Total contestants</p>
                    <h2>{{ totalSkaters }}</h2>
                </div>

                <div class="stat-card">
                    <p>Total categories</p>
                    <h2>{{ totalCategories }}</h2>
                </div>
            </section>

            <section class="category-section">
                <h2>Contestant overview</h2>

                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="category-row"
                >
                    <div>
                        <h3>{{ category.name }}</h3>
                        <p>{{ category.skaters_count }} contestants</p>
                    </div>
                </div>
            </section>

            
            <section class="results-section">
                <h2>Voting Results</h2>
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="results-category">
                            <h3>{{ category.name }}</h3>

                                <div
                        v-for="skater in category.skaters"
                        :key="skater.id"
                        class="result-row">
                    <div>
                        <strong>{{ skater.name }}</strong>
                    </div>

                    <span>
                        {{ skater.votes_count }} votes
                    </span>
                    </div>
                    </div>
            </section>

        </main>

        <Footer />
    </div>
</template>

<style scoped>
.admin-page {
    min-height: 100vh;
    width: 100%;
    font-family: cursive;
}

.admin-content {
    padding: 65px 7%;
    min-height: 65vh;
}

.eyebrow {
    color: #9b6872;
    letter-spacing: 3px;
    font-size: 12px;
}

h1 {
    font-size: 38px;
    margin: 12px 0;
}

.description {
    color: #888;
}

.stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin: 35px 0 50px;
}

.stat-card {
    padding: 25px;
    background: #f2eeee;
    border-radius: 8px;
}

.stat-card p {
    color: #777;
    font-size: 14px;
}

.stat-card h2 {
    font-size: 32px;
    margin-top: 12px;
}

.category-section h2 {
    margin-bottom: 20px;
}

.category-row {
    padding: 20px 0;
    border-bottom: 1px solid #ddd;
}

.category-row h3 {
    font-size: 17px;
}

.category-row p {
    color: #888;
    font-size: 13px;
    margin-top: 5px;
}

.results-section {
    margin-top: 50px;
}

.results-category {
    margin-top: 25px;
}

.result-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #ddd;
}

.result-row span {
    color: #9b6872;
    font-weight: 600;
    font-size: 14px;
}

@media (max-width: 600px) {
    .stats {
        grid-template-columns: 1fr;
    }
}

.section-heading p,
.modal-description {
    color: #888;
    font-size: 13px;
    margin-top: 6px;
}

.add-btn,
.save-btn {
    background: #9b6872;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.contestant-info {
    display: flex;
    align-items: center;
    gap: 15px;
}
.flash-message {
    padding: 14px 20px;
    margin: 20px 7% 0;
    border-radius: 6px;
    font-size: 14px;
}

.success {
    background: #e8f5e9;
    color: #276738;
}

.error-message {
    background: #fdebec;
    color: #a83242;
}


@media (max-width: 600px) {
    .section-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .modal {
        padding: 22px;
    }
}
</style>