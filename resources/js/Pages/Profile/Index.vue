<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const page = usePage();
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const editingProfile = ref(false);
const photoPreview = ref(null);

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    photo: null,
    remove_photo: false,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const profilePhoto = computed(() => {
    if (profileForm.remove_photo) {
        return null;
    }

    return photoPreview.value ||
        (props.user.profile_photo
            ? `/storage/${props.user.profile_photo}`
            : null);
});

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            setTimeout(() => {
                if (page.props.flash.success === message) {
                    page.props.flash.success = null;
                }
            }, 4000);
        }
    }
);

function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (file) {
        if (photoPreview.value) {
            URL.revokeObjectURL(photoPreview.value);
        }
        profileForm.photo = file;
        profileForm.remove_photo = false;

        photoPreview.value = URL.createObjectURL(file);
    }
}

function removeProfilePhoto() {
    if (photoPreview.value) {
        URL.revokeObjectURL(photoPreview.value);
    }

    photoPreview.value = null;
    profileForm.photo = null;
    profileForm.remove_photo = true;
    profileForm.clearErrors('photo');
}

function updateProfile() {
    profileForm
        .transform(data => ({
            ...data,
            _method: 'put',
        }))
        .post('/profile', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
            editingProfile.value = false;

            if (photoPreview.value) {
            URL.revokeObjectURL(photoPreview.value);
            }

            photoPreview.value = null;
            profileForm.photo = null;
            profileForm.remove_photo = false;
            },
        });
}

function updatePassword() {
    passwordForm.put('/profile/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <main class="profile-page">
        <div class="profile-container">

            <header class="profile-heading">
                <h1>My Profile</h1>
                <p>Manage your personal information and account security.</p>
            </header>

            <!-- Flash messages -->
            <Transition name="toast">
              <div
                v-if="page.props.flash?.success"
                class="success-toast">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ page.props.flash.success }}</span>
             </div>
            </Transition>

            <div class="profile-card">

                <!-- Profile header -->
                <section class="profile-header">
                    <div class="profile-avatar">
                        <img
                            v-if="profilePhoto"
                            :src="profilePhoto"
                            alt="Profile photo"
                        />

                        <i v-else class="fa-solid fa-user"></i>
                    </div>

                    <div class="profile-identity">
                        <h2>{{ user.name }}</h2>
                        <p>{{ user.email }}</p>
                        <span class="role-badge">{{ user.role }}</span>
                    </div>
                </section>

                <!-- Personal information -->
                <section class="profile-section">
                    <div class="section-heading">
                        <div>
                            <h3>Personal Information</h3>
                        </div>

                        <button
                            v-if="!editingProfile"
                            class="edit-button"
                            @click="editingProfile = true"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit Profile
                        </button>
                    </div>

                    <form v-if="editingProfile" @submit.prevent="updateProfile">

                        <div class="form-group">
                            <label>Profile Photo</label>
                              <input
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                @change="handlePhotoUpload"/>

                            <div v-if="profilePhoto" class="photo-actions">
                            <button
                              type="button"
                              class="remove-photo-button"
                              @click="removeProfilePhoto">
                            <i class="fa-solid fa-trash"></i>Remove Photo
                            </button>
                            </div>

                            <small v-else class="input-hint">
                            No profile photo selected. Your default avatar will be used.</small>

                            <small class="input-hint">
                             Accepted formats: JPG, JPEG, PNG, WEBP.
                             Maximum file size: 2MB.
                            </small>

                            <small v-if="profileForm.errors.photo" class="error">
                            {{ profileForm.errors.photo }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input
                                v-model="profileForm.name"
                                type="text"
                            />
                            <small v-if="profileForm.errors.name" class="error">
                                {{ profileForm.errors.name }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input
                                v-model="profileForm.email"
                                type="email"
                            />
                            <small v-if="profileForm.errors.email" class="error">
                                {{ profileForm.errors.email }}
                            </small>
                        </div>

                        <div class="form-actions">
                            <button
                                type="button"
                                class="cancel-button"
                                @click="editingProfile = false; profileForm.reset(); photoPreview = null"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="save-button"
                                :disabled="profileForm.processing"
                            >
                                {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>

                    <div v-else class="details-grid">
                        <div>
                            <span>Full Name</span>
                            <p>{{ user.name }}</p>
                        </div>

                        <div>
                            <span>Email Address</span>
                            <p>{{ user.email }}</p>
                        </div>

                        <div>
                            <span>Account Role</span>
                            <p>{{ user.role }}</p>
                        </div>
                    </div>
                </section>

                <!-- Password -->
                <section class="profile-section">
                    <div class="section-heading">
                        <div>
                            <h3>Change Password</h3>
                            <p>Keep your account secure with a strong password.</p>
                        </div>
                    </div>

                    <form @submit.prevent="updatePassword">

                        <div class="form-group">
                           <label>Current Password</label>

                           <div class="password-input-wrapper">
                           <input
                               v-model="passwordForm.current_password"
                               :type="showCurrentPassword ? 'text' : 'password'"
                               autocomplete="current-password"
                               placeholder="Enter your current password"/>

                        <button type="button"
                            class="toggle-password"
                            @click="showCurrentPassword = !showCurrentPassword"
                            :aria-label="showCurrentPassword ? 'Hide password' : 'Show password'">
                            <i :class="showCurrentPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                        </div>

                        <small v-if="passwordForm.errors.current_password" class="error">
                        {{ passwordForm.errors.current_password }}
                        </small>
                        </div>



                        <div class="form-group">
                            <label>New Password</label>
                                <div class="password-input-wrapper">
                                <input v-model="passwordForm.password"
                                    :type="showNewPassword ? 'text' : 'password'"
                                    autocomplete="new-password"
                                    placeholder="Enter your new password"/>

                            <button type="button"
                             class="toggle-password"
                             @click="showNewPassword = !showNewPassword"
                             :aria-label="showNewPassword ? 'Hide password' : 'Show password'">
                            <i :class="showNewPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <small v-if="passwordForm.errors.password" class="error">
                        {{ passwordForm.errors.password }}
                        </small>
                        </div>



                        <div class="form-group">
                            <label>Confirm New Password</label>
                              <div class="password-input-wrapper">
                                <input
                                v-model="passwordForm.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Confirm your new password"/>
                            <button
                              type="button"
                              class="toggle-password"
                              @click="showConfirmPassword = !showConfirmPassword"
                              :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                              <i :class="showConfirmPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                            </div>
                            <small
                              v-if="passwordForm.errors.password_confirmation || passwordForm.errors.password"
                              class="error">
                              {{ passwordForm.errors.password_confirmation || passwordForm.errors.password }}
                            </small>
                        </div>

                        <button
                            type="submit"
                            class="save-button"
                            :disabled="passwordForm.processing">
                            {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
                        </button>
                    </form>
                </section>



                <!--voting history placeholder/ for voters only-->
                <section v-if="user.role?.toLowerCase() !== 'admin'" class="profile-section">
                    <div class="section-heading">
                        <div>
                            <h3>Voting History</h3>
                            <p>Keep track of your participation in competitions.</p>
                        </div>

                        <span class="coming-soon">Coming Soon</span>
                    </div>

                    <div class="history-placeholder">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <h4>Your voting activity will appear here.</h4>
                        <p>You'll be able to view your previous votes and participation.</p>
                    </div>
                </section>

                <!-- Logout -->
                <section class="logout-section">
                    <div>
                        <h3>Sign Out</h3>
                        <p>Log out of your account on this device.</p>
                    </div>

                    <button class="logout-button" @click="logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </section>

            </div>
        </div>
    </main>
</template>

<style scoped>
.profile-page {
    min-height: 80vh;
    padding: 50px 20px;
}

.profile-container {
    max-width: 850px;
    margin: auto;
}

.profile-heading {
    margin-bottom: 25px;
}

.profile-heading h1 {
    font-size: 28px;
    margin-bottom: 8px;
}

.profile-heading p,
.section-heading p,
.logout-section p {
    color: #777;
    font-size: 14px;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.04);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.profile-avatar {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    background: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    font-size: 30px;
    color: #777;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-identity h2 {
    margin-bottom: 5px;
}

.profile-identity p {
    color: #777;
    font-size: 14px;
}

.photo-actions {
    margin-top: 10px;
}

.remove-photo-button {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    padding: 5px 0;
}

.remove-photo-button:hover {
    color: #a71d2a;
}

.role-badge,
.coming-soon {
    display: inline-block;
    margin-top: 8px;
    padding: 5px 10px;
    border-radius: 20px;
    background: #f3edf0;
    color: rgb(128, 0, 73);
    font-size: 12px;
}

.profile-section {
    padding: 28px 0;
    border-bottom: 1px solid #eee;
}

.success-toast {
    position: fixed;
    top: 25px;
    right: 25px;
    z-index: 9999;

    display: flex;
    align-items: center;
    gap: 12px;

    padding: 15px 20px;
    background: #e7f6ec;
    color: #24743d;
    border: 1px solid #b7e4c7;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);

    max-width: 350px;
    font-size: 14px;
}

.success-toast i {
    font-size: 18px;
}

/* Entrance and exit animation */
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
    .success-toast {
        left: 15px;
        right: 15px;
        top: 15px;
        max-width: none;
    }
}

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 22px;
}

.section-heading h3,
.logout-section h3 {
    margin-bottom: 6px;
    font-size: 17px;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.details-grid span {
    color: #888;
    font-size: 13px;
}

.details-grid p {
    margin-top: 6px;
    font-weight: 500;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    outline: none;
}

.password-input-wrapper {
    position: relative;
    width: 100%;
}

.password-input-wrapper input {
     padding-right: 45px;
}
.form-group input:focus {
    border-color: rgb(243, 118, 139);
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);

    background: none;
    border: none;
    padding: 5px;

    color: #f3c1da;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toggle-password:hover {
    color: #914c6a;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

button {
    cursor: pointer;
    border: none;
    border-radius: 6px;
    padding: 11px 16px;
    font-weight: 600;
}

button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.edit-button {
    background: #f3edf0;
    color: rgb(128, 0, 73);
}

.save-button {
    background: #914c6a;
    color: white;
}

.cancel-button {
    background: #eee;
    color: #444;
}

.error {
    display: block;
    color: #dc3545;
    margin-top: 5px;
    font-size: 12px;
}

.success-message {
    padding: 13px;
    margin-bottom: 20px;
    background: #e7f6ec;
    color: #24743d;
    border-radius: 6px;
}

.history-placeholder {
    padding: 35px 15px;
    text-align: center;
    background: #fafafa;
    border: 1px dashed #ddd;
    border-radius: 8px;
}

.history-placeholder i {
    font-size: 28px;
    color: #999;
    margin-bottom: 12px;
}

.history-placeholder h4 {
    margin-bottom: 8px;
}

.history-placeholder p {
    color: #888;
    font-size: 13px;
}

.logout-section {
    padding-top: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.logout-button {
    background: #fff0f0;
    color: #d93636;
}

@media (max-width: 600px) {
    .profile-card {
        padding: 20px;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .section-heading {
        align-items: flex-start;
    }

    .profile-avatar {
        width: 65px;
        height: 65px;
    }
}
</style>
