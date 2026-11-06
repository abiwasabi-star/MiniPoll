<script setup>
import { ref } from 'vue'; //creates reactive data
import { Link, useForm } from '@inertiajs/vue3'; //link component for navigation and useForm for form handling or submission

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);//controls whether passw is visible or hidden

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/register', { //sends the form data to Laravel's /register route using a POST request
        onFinish: () => {
            form.reset('password', 'password_confirmation');//resets/clears the password
        },
    });
}
</script>

<template>
    <main class="auth-page">
        <section class="auth-brand">
            <div class="brand-content">
                <span class="brand-label">THE SKATING AWARDS</span>
                <h1>Every skater deserves their moment.</h1>
                <p>
                    Celebrate talent, recognise dedication and vote
                    for the skaters who inspire you.
                </p>
                <img 
                 style="width: 100px; height: auto; display: block; margin: 20px auto 0;"
                 src="/public/Images/logo7.png" 
                 alt="Skate Awards" />

            </div>
        </section>

        <section class="auth-form-section">
            <div class="auth-card">
                <div class="mobile-brand">
                    <img src="/public/Images/logo7.png" alt="Skate Awards" />
                    SKATING AWARDS</div>

                <h2>Create your account</h2>
                <p class="subtitle">
                    Join the community and support your favourite skaters.
                </p>

                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label for="name">Full name</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Your full name"
                            autocomplete="name"
                            required
                        />
                        <span v-if="form.errors.name" class="error">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        />
                        <span v-if="form.errors.email" class="error">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="At least 8 characters"
                                autocomplete="new-password"
                                required
                            />
                            <button
                                type="button"
                                class="toggle-password"
                                @click="showPassword = !showPassword"
                            >
                                <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <span v-if="form.errors.password" class="error">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            Confirm password
                        </label>
                        <div class="password-wrapper">
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                placeholder="Re-enter your password"
                                autocomplete="new-password"
                                required
                            />
                            <button
                                type="button"
                                class="toggle-password"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                            >
                                <i :class="showPasswordConfirmation ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="submit-button"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Creating account...' : 'Create account' }}
                    </button>
                </form>

                <p class="auth-switch">
                    Already have an account?
                    <Link href="/login">Log in</Link>
                </p>
            </div>
        </section>
    </main>
</template>

<style scoped>
.auth-page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-family: Arial, sans-serif;
    background: #fff;
    color: black;
}

.auth-brand {
    display: flex;
    align-items: center;
    padding: 70px;
    background: #FFF5E7;
    color: black;
}

.brand-content {
    max-width: 480px;
}

.brand-label,
.mobile-brand {
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 3px;
}

.brand-content h1 {
    font-size: clamp(36px, 4vw, 58px);
    line-height: 1.12;
    margin: 30px 0 20px;
}

.brand-content p {
    color: black;
    font-size: 17px;
    line-height: 1.7;
}

.brand-decoration {
    margin-top: 50px;
    font-size: 80px;
    color: black;
}

.brand-content img {
    width: 55px;
    height: auto;
    display: block;

    transform-origin: top center;
    animation: hangingSwing 3s ease-in-out infinite;
}

@keyframes hangingSwing {
    0% {
        transform: rotate(-17deg);
    }

    50% {
        transform: rotate(17deg);
    }

    100% {
        transform: rotate(-17deg);
    }
}

.auth-form-section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 24px;
}

.auth-card {
    width: 100%;
    max-width: 430px;
}

.mobile-brand {
    display: none;
    color:black;
    margin-bottom: 35px;
}

.mobile-brand img {
    width: 55px;
    height: auto;
    display: block;

    transform-origin: top center;
    animation: hangingSwing 3s ease-in-out infinite;
}

@keyframes hangingSwing {
    0% {
        transform: rotate(-17deg);
    }

    50% {
        transform: rotate(17deg);
    }

    100% {
        transform: rotate(-17deg);
    }
}

.auth-card h2 {
    font-size: 30px;
    margin: 0 0 10px;
}
.subtitle {
    color: #777;
    line-height: 1.6;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 19px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group input {
    box-sizing: border-box;
    width: 100%;
    padding: 14px;
    border: 1px solid #dedce8;
    border-radius: 7px;
    outline: none;
    font-size: 14px;
}

.form-group input:focus {
    border-color: rgb(243, 118, 139);
}

.password-wrapper {
    position: relative;
}

.password-wrapper input {
    padding-right: 65px;
}

.toggle-password {
    background: none;
    border: none;
    cursor: pointer;
    color: #f0cb98;
    font-size: 16px;
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
}

.error {
    display: block;
    color: #c0394b;
    font-size: 12px;
    margin-top: 6px;
}

.submit-button {
    width: 100%;
    padding: 15px;
    margin-top: 8px;
    border: none;
    border-radius: 7px;
    background: #FEF0E3;
    color: black;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
}
.submit-button:hover {
    background: #eed4b9;
}
.submit-button:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

.auth-switch {
    margin-top: 25px;
    text-align: center;
    font-size: 14px;
    color: #777;
}

.auth-switch a {
    color: rgb(243, 118, 139);
    font-weight: bold;
    text-decoration: none;
}

@media (max-width: 800px) {
    .auth-page {
        grid-template-columns: 1fr;
    }

    .auth-brand {
        display: none;
    }

    .mobile-brand {
        display: block;
    }
}
</style>
