<script setup>
import { ref, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AdminNavbar from '../../Components/AdminNavbar.vue';
import Footer from '../../Components/Footer.vue';
import SearchInput from '../../Components/SearchInput.vue';


const props = defineProps({
    categories: Array,
});

const page = usePage();
const search = ref('');

const filteredCategories = computed(() => {
    return props.categories.filter(category =>
        category.name.toLowerCase().includes(search.value.toLowerCase()) ||
        category.slug.toLowerCase().includes(search.value.toLowerCase())
    );
});


const showModal = ref(false);
const editingCategory = ref(null);

const form = useForm({
    name: '',
});

//opens modal for adding a category
function openCreateModal() {
    editingCategory.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
}

//opens modal for editing a category
function openEditModal(category) {
    editingCategory.value = category;

    form.name = category.name;
    form.clearErrors();

    showModal.value = true;
}

//closes modal
function closeModal() {
    showModal.value = false;
    editingCategory.value = null;
    form.reset();
    form.clearErrors();
}

//saves category
function submitForm() {
    if (editingCategory.value) {
        form.put(
            route('admin.categories.update', editingCategory.value.id),
            {
                onSuccess: () => closeModal(),
            }
        );
    } else {
        form.post(
            route('admin.categories.store'),
            {
                onSuccess: () => closeModal(),
            }
        );
    }
}

//deletes category
function deleteCategory(category) {
    if (!confirm(`Are you sure you want to delete "${category.name}"?`)) {
        return;
    }

    router.delete(
        route('admin.categories.destroy', category.id)
    );
}
</script>


<template>

<div class="categories-page">
<AdminNavbar/>
<!--flash messages-->
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
                    <h1>Categories</h1>
                    <p class="description">
                        Add, view and manage your award categories.
                    </p>

                    <SearchInput
                      v-model="search"
                      placeholder="Search categories..."/>

                      <div class="section-heading">
                        <div>
                          <h2>Manage Categories</h2>
                        </div>
                      
                        <button
                          class="add-btn"
                          @click="openCreateModal">
                          + Add Category
                        </button>
                      </div>

            <!--categories list-->
            <section class="categories-section">

                <div class="section-title">
                    <h2>All Categories</h2>

                    <span>
                        {{ filteredCategories.length }}
                        categories
                    </span>
                </div>

                <div v-if="filteredCategories.length">

                    <div
                        v-for="category in filteredCategories"
                        :key="category.id"
                        class="category-card"
                    >
                        <div class="category-info">
                            <div class="category-icon">
                                {{ category.name.charAt(0).toUpperCase() }}
                            </div>

                            <div>
                                <h3>{{ category.name }}</h3>

                                <p>
                                    {{ category.skaters_count }}
                                    contestants
                                </p>

                                <small>{{ category.slug }}</small>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button
                                class="edit-btn"
                                @click="openEditModal(category)"
                                title="Edit category"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button
                                class="delete-btn"
                                @click="deleteCategory(category)"
                                title="Delete category"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div v-else class="empty-state">
                    <h3>{{ search ? 'No matching categories' : 'No categories yet' }}</h3>

                    <p>
                      {{ search
                      ? 'Try searching with a different name.'
                      : 'Add your first category to get started.'}}
                    </p>
                </div>
            </section>
        </main>

        <!-- Add/Edit modal -->
        <div
            v-if="showModal"
            class="modal-overlay"
            @click.self="closeModal">
            <div class="modal">

                <div class="modal-header">
                    <div>
                        <h2>
                            {{ editingCategory ? 'Edit Category' : 'Add Category' }}
                        </h2>

                        <p>
                            {{
                                editingCategory
                                ? 'Update the category details.'
                                : 'Create a new skating awards category.'
                            }}
                        </p>
                    </div>

                    <button
                        class="close-btn"
                        @click="closeModal"
                    >
                        &times;
                    </button>
                </div>

                <form @submit.prevent="submitForm">

                    <div class="form-group">
                        <label for="name">Category Name</label>

                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Best Female Skater"
                        />

                        <p
                            v-if="form.errors.name"
                            class="error-text"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="modal-actions">
                        <button
                            type="button"
                            class="cancel-btn"
                            @click="closeModal"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="save-btn"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                ? 'Saving...'
                                : editingCategory
                                ? 'Update Category'
                                : 'Add Category'
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
<Footer/>
</div>
</template>

<style scoped>
.categories-page{
    font-family: cursive;
    min-height: 100vh;
    width: 100%;
}
.admin-content {
    padding: 65px 7%;
    min-height: 65vh;
}

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin: 40px 0 25px;
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

.add-btn,
.save-btn {
    background: #9b6872;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.categories-section {
    margin-top: 45px;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title span {
    color: #888;
    font-size: 13px;
}

.category-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ddd;
    gap: 15px;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.category-icon {
    width: 45px;
    height: 45px;
    background: #f2eeee;
    color: #9b6872;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
}

.category-info h3 {
    font-size: 17px;
}

.category-info p {
    color: #888;
    font-size: 13px;
    margin-top: 5px;
}

.category-info small {
    display: block;
    color: #aaa;
    font-size: 11px;
    margin-top: 4px;
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

.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #888;
}

.empty-state h3 {
    color: #444;
    margin-bottom: 10px;
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

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    justify-content: center;
    align-items: center;
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

.modal-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.modal-header p {
    color: #888;
    font-size: 13px;
    margin-top: 6px;
}

.close-btn {
    border: none;
    background: none;
    font-size: 25px;
    cursor: pointer;
    align-self: flex-start;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 9px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    font-family: inherit;
}

.form-group input:focus {
    border-color: #9b6872;
}

.error-text {
    color: #c0394b;
    font-size: 12px;
    margin-top: 6px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.cancel-btn {
    background: #eee;
    color: #555;
}

.save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 600px) {
    .section-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .category-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .actions {
        width: 100%;
    }

    .modal {
        padding: 22px;
    }
}
</style>