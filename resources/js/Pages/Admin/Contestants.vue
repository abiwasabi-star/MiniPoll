<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue';
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

// Toast notification
const toast = ref(null);
let toastTimer = null;

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    ([success, error]) => {
        const message = success || error;

        if (!message) return;

        clearTimeout(toastTimer);

        toast.value = {
            message,
            type: success ? 'success' : 'error',
        };

        toastTimer = setTimeout(() => {
            if (toast.value?.message === message) {
                toast.value = null;
            }
        }, 4000);
    },
    { immediate: true }
);

onBeforeUnmount(() => {
    clearTimeout(toastTimer);
});

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
    remove_photo: false,
});

// Capture the selected contestant photo.
function handlePhotoUpload(event) {
    form.photo = event.target.files[0];
}

// Capture the replacement photo.
function handleEditPhotoUpload(event) {
    const file = event.target.files[0];

    if (file) {
        editForm.photo = file;
        editForm.remove_photo = false;
    }
}

//remove a contestant's photo
function removeContestantPhoto() {
    editForm.photo = null;
    editForm.remove_photo = true;
    editForm.clearErrors('photo');
}

// Open the edit modal and populate the form.
function openEditModal(skater) {
    selectedSkater.value = skater;

    editForm.reset();
    editForm.clearErrors();

    editForm.name = skater.name;
    editForm.category_id = skater.category_id;
    editForm.photo = null;
    editForm.remove_photo = false;

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
            name: data.name,
            category_id: data.category_id,
            remove_photo: data.remove_photo,
            ...(data.photo ? { photo: data.photo } : {}),
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

        <Transition name="toast">
            <div
              v-if="toast"
              class="toast-notification"
              :class="toast.type"
              >
              <i
              :class="toast.type === 'success'
                ? 'fa-solid fa-circle-check'
                : 'fa-solid fa-circle-exclamation'"
              ></i>
              <span>{{ toast.message }}</span>
            </div>
        </Transition>

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
                            v-if="skater.photo"
                            :src="`/storage/${skater.photo}`"
                            :alt="skater.name">
                        <div v-else class="contestant-photo-placeholder">
                            <i class="fa-solid fa-user"></i>
                        </div>

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
                            <label>Contestant Photo</label>
                            <div class="current-photo-container">
                            <img
                                v-if="selectedSkater?.photo && !editForm.remove_photo"
                                :src="`/storage/${selectedSkater.photo}`"
                                :alt="selectedSkater.name"
                                class="current-photo"
                            >

                            <div v-else class="current-photo placeholder-photo">
                              <i class="fa-solid fa-user"></i>
                            </div>
                            </div>

                            <button
                                v-if="selectedSkater?.photo && !editForm.remove_photo"
                                type="button"
                                class="remove-photo-button"
                                @click="removeContestantPhoto"
                            >
                            <i class="fa-solid fa-trash"></i>Remove Photo
                            </button>

                            <p v-if="editForm.remove_photo" class="input-hint">
                            Photo will be removed when you save your changes.
                            </p>

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

.contestant-photo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    /* background: #f3edf0; */
    color: #800049;
    font-size: 50px;
}

.management-category {
    margin-bottom: 30px;
}

.management-category h3 {
    margin-bottom: 12px;
}

.placeholder-photo {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3edf0;
    color: #800049;
    font-size: 40px;
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

/* Toast notification */
.toast-notification {
    position: fixed;
    top: 25px;
    right: 25px;
    z-index: 9999;

    display: flex;
    align-items: center;
    gap: 12px;

    padding: 15px 20px;
    max-width: 350px;

    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);

    font-size: 14px;
}

.toast-notification i {
    font-size: 18px;
    flex-shrink: 0;
}

.toast-notification.success {
    background: #e7f6ec;
    color: #24743d;
    border: 1px solid #b7e4c7;
}

.toast-notification.error {
    background: #fdebec;
    color: #a83242;
    border: 1px solid #f5c2c7;
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateY(-15px);
}

@media (max-width: 600px) {
    .toast-notification {
        left: 15px;
        right: 15px;
        top: 15px;
        max-width: none;
    }
}

.current-photo-container {
    margin: 10px 0;
}

.current-photo {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eee;
}

.placeholder-photo {
    background: #f3f3f3;
    object-fit: contain;
}

.remove-photo-button {
    display: block;
    background: none;
    color: #dc3545;
    border: none;
    padding: 5px 0;
    margin-bottom: 15px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.remove-photo-button:hover {
    color: #a71d2a;
}

.input-hint {
    color: #777;
    font-size: 12px;
    margin: 5px 0 12px;
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
