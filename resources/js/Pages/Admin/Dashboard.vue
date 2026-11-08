<script setup>
import {usePage, useForm} from '@inertiajs/vue3'; //accesses the current page's props and data

import AdminNavbar from '../../Components/AdminNavbar.vue';
import Footer from '../../Components/Footer.vue';

const props = defineProps({
    totalSkaters: Number,
    totalCategories: Number,
    categories: Array,
    settings: Array,
});

function formatDateTime(value) {
    if (!value) return '';

    const date = new Date(value);
    const pad = number => String(number).padStart(2, '0');

    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function updateVotingSchedule() {
    form.put(route('admin.voting-settings.update'));
}

const form = useForm({
    voting_starts_at: formatDateTime(props.settings?.voting_starts_at),
    voting_ends_at: formatDateTime(props.settings?.voting_ends_at),
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


            <section class="schedule-section">
                <h2>Voting Schedule</h2>

                 <p class="schedule-description">
                    Configure when voting opens and closes.
                 </p>
                 <form @submit.prevent="updateVotingSchedule">
                <div class="date-field">
                 <label for="voting_starts_at">Voting starts</label>

                <input
                id="voting_starts_at"
                type="datetime-local"
                v-model="form.voting_starts_at"
                required>

                <p v-if="form.errors.voting_starts_at" class="field-error">
                {{ form.errors.voting_starts_at }}
                </p>
                </div>

                <div class="date-field">
                    <label for="voting_ends_at">Voting closes</label>

                <input
                id="voting_ends_at"
                type="datetime-local"
                v-model="form.voting_ends_at"
                :min="form.voting_starts_at"
                required>

                  <p v-if="form.errors.voting_ends_at" class="field-error">
                   {{ form.errors.voting_ends_at }}
                  </p>
               </div>

                <button
                    type="submit"
                    class="save-btn"
                    :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Schedule' }}
                </button>
                </form>
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
    color: rgb(128, 0, 73);
    letter-spacing: 3px;
    font-size: 12px;
    text-align: center;
}

h1 {
    font-size: 38px;
    text-align: center;
    margin: 12px 0;
}

.description {
    color: #888;
    text-align: center;
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

.schedule-section {
    margin-top: 50px;
    padding: 25px;
    background: #f2eeee;
    border-radius: 8px;
}

.schedule-section h2 {
    margin-bottom: 8px;
}

.schedule-description {
    color: #888;
    font-size: 14px;
    margin-bottom: 25px;
}

.date-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.date-field label {
    font-size: 14px;
    font-weight: 600;
}

.date-field input {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: inherit;
    width: 100%;
}

.field-error {
    color: #c0394b;
    font-size: 13px;
}

.save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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