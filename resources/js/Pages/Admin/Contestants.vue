<script setup>
import { ref, computed } from 'vue';
import { useForm, router, usePage, Link } from '@inertiajs/vue3';

import AdminNavbar from '../../Components/AdminNavbar.vue';
import Footer from '../../Components/Footer.vue';
import SearchInput from '../../Components/SearchInput.vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const search = ref('');

const filteredCategories = computed(() => {
    const term = search.value.trim().toLowerCase();

    return props.categories
        .map(category => {
            const categoryMatches = category.name
                .toLowerCase()
                .includes(term);

            const skaters = !term || categoryMatches
                ? category.skaters
                : category.skaters.filter(skater =>
                    skater.name.toLowerCase().includes(term)
                );

            return {
                ...category,
                skaters,
            };
        })
        .filter(category =>
            !term ||
            category.name.toLowerCase().includes(term) ||
            category.skaters.length > 0
        );
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedSkater = ref(null);

const form = useForm({
    name: '',
    category_id: '',
    photo: null,
});

const editForm = useForm({
    name: '',
    category_id: '',
    photo: null,
});

// Capture the selected contestant photo.
function handlePhotoUpload(event) {
    form.photo = event.target.files[0];
}

// Capture the replacement photo.
function handleEditPhotoUpload(event) {
    editForm.photo = event.target.files[0];
}

// Open the edit modal and populate the form.
function openEditModal(skater) {
    selectedSkater.value = skater;

    editForm.reset();
    editForm.clearErrors();

    editForm.name = skater.name;
    editForm.category_id = skater.category_id;
    editForm.photo = null;

    showEditModal.value = true;
}

// Submit a new contestant.
function addContestant() {
    form.post('/admin/skaters', {
        forceFormData: true,
        preserveScroll: true,

        onSuccess: () => {
            showAddModal.value = false;
            form.reset();
        },
    });
}

// Update an existing contestant.
function updateContestant() {
    editForm
        .transform(data => ({
            ...data,
            _method: 'put',
        }))
        .post(`/admin/skaters/${selectedSkater.value.id}`, {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                showEditModal.value = false;
                editForm.reset();
                selectedSkater.value = null;
            },
        });
}

// Delete a contestant after confirmation.
function deleteContestant(skater) {
    const confirmed = window.confirm(
        `Are you sure you want to delete ${skater.name}?`
    );

    if (!confirmed) return;

    router.delete(`/admin/skaters/${skater.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div class="admin-page">
        <AdminNavbar />

        <div
            v-if="page.props.flash?.success"
            class="flash-message success"
        >
            {{ page.props.flash.success }}
        </div>

        <div
            v-if="page.props.flash?.error"
            class="flash-message error-message"
        >
            {{ page.props.flash.error }}
        </div>

        <p
           v-if="filteredCategories.length === 0"
            class="empty-message">
           {{ search
            ? `No contestants or categories match "${search}".`
            : 'No categories have been added yet.'
           }}
         </p>

        <main class="admin-content">
            <p class="eyebrow">ADMINISTRATION</p>
            <h1>Contestants</h1>

            <p class="description">
                Add, view and manage your skating awards contestants.
            </p>

            <SearchInput
          v-model="search"
          placeholder="Search contestants or categories..."/>

            

            <div class="section-heading">
                <div>
                    <h2>Manage Contestants</h2>
                </div>

                <button class="add-btn" @click="showAddModal = true">
                    + Add Contestant
                </button>
            </div>

            <section
                v-for="category in filteredCategories"
                :key="category.id"
                class="management-category"
            >
                <h3>{{ category.name }}</h3>

                <div
                    v-if="category.skaters.length"
                    v-for="skater in category.skaters"
                    :key="skater.id"
                    class="management-row"
                >
                    <div class="contestant-info">
                        <img
                            :src="skater.photo
                                ? `/storage/${skater.photo}`
                                : '/images/skater-placeholder.jpg'"
                            :alt="skater.name"
                        >

                        <div>
                            <strong>{{ skater.name }}</strong>
                            <p>{{ skater.votes_count }} votes</p>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button
                            class="edit-btn"
                            @click="openEditModal(skater)"
                            title="Edit contestant"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="delete-btn"
                            @click="deleteContestant(skater)"
                            title="Delete contestant"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <p v-else class="empty-message">
                    No contestants have been added to this category yet.
                </p>
            </section>

            <!-- Add Contestant Modal -->
            <div
                v-if="showAddModal"
                class="modal-overlay"
                @click.self="showAddModal = false"
            >
                <div class="modal">
                    <h2>Add Contestant</h2>
                    <p class="modal-description">
                        Enter the contestant's details below.
                    </p>

                    <form @submit.prevent="addContestant">
                        <div class="form-group">
                            <label>Contestant Name</label>

                            <input
                                type="text"
                                v-model="form.name"
                                placeholder="Enter contestant name"
                            >

                            <p v-if="form.errors.name" class="error">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Category</label>

                            <select v-model="form.category_id">
                                <option value="" disabled>
                                    Select category
                                </option>

                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <p v-if="form.errors.category_id" class="error">
                                {{ form.errors.category_id }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Contestant Photo</label>

                            <input
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                @change="handlePhotoUpload"
                            >

                            <p v-if="form.errors.photo" class="error">
                                {{ form.errors.photo }}
                            </p>
                        </div>

                        <div class="modal-actions">
                            <button
                                type="button"
                                class="cancel-btn"
                                @click="showAddModal = false; form.reset(); form.clearErrors()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="save-btn"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Saving...' : 'Save Contestant' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Contestant Modal -->
            <div
                v-if="showEditModal"
                class="modal-overlay"
                @click.self="showEditModal = false"
            >
                <div class="modal">
                    <h2>Edit Contestant</h2>
                    <p class="modal-description">
                        Update the contestant's details.
                    </p>

                    <form @submit.prevent="updateContestant">
                        <div class="form-group">
                            <label>Contestant Name</label>

                            <input
                                type="text"
                                v-model="editForm.name"
                                placeholder="Enter contestant name"
                            >

                            <p v-if="editForm.errors.name" class="error">
                                {{ editForm.errors.name }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Category</label>

                            <select v-model="editForm.category_id">
                                <option value="" disabled>
                                    Select category
                                </option>

                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <p v-if="editForm.errors.category_id" class="error">
                                {{ editForm.errors.category_id }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Current Photo</label>

                            <img
                                v-if="selectedSkater?.photo"
                                :src="`/storage/${selectedSkater.photo}`"
                                :alt="selectedSkater.name"
                                class="current-photo"
                            >

                            <label>Replace Photo (optional)</label>

                            <input
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                @change="handleEditPhotoUpload"
                            >

                            <p v-if="editForm.errors.photo" class="error">
                                {{ editForm.errors.photo }}
                            </p>
                        </div>

                        <div class="modal-actions">
                            <button
                                type="button"
                                class="cancel-btn"
                                @click="showEditModal = false; editForm.reset(); editForm.clearErrors()"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="save-btn"
                                :disabled="editForm.processing"
                            >
                                {{ editForm.processing ? 'Updating...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
    margin: 12px 0;
    text-align: center;
}

.description {
    color: #888;
    text-align: center;
    margin-bottom: 15px;
}

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin: 40px 0 25px;
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

.management-category {
    margin-bottom: 30px;
}

.management-category h3 {
    margin-bottom: 12px;
}

.management-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #ddd;
}

.contestant-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.contestant-info img {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 6px;
}

.contestant-info p {
    color: #888;
    font-size: 12px;
    margin-top: 5px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}

.edit-btn,
.delete-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 17px;
    padding: 6px;
}

.edit-btn {
    color: #555;
}

.delete-btn {
    color: #dc3545;
}

.empty-message {
    color: #888;
    font-size: 14px;
    padding: 15px 0;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 1000;
}

.modal {
    background: white;
    width: 100%;
    max-width: 450px;
    padding: 30px;
    border-radius: 10px;
}

.modal h2 {
    margin-bottom: 5px;
}

.form-group {
    margin-top: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    font-family: inherit;
}

.error {
    color: #c0394b;
    font-size: 12px;
    margin-top: 5px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 25px;
}

.cancel-btn {
    background: #eee;
    border: none;
    padding: 12px 18px;
    border-radius: 5px;
    cursor: pointer;
}

.save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

.current-photo {
    display: block;
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 15px;
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
