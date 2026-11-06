
<script setup>
import { ref } from 'vue'; //creates reactive data
import { Link, useForm } from '@inertiajs/vue3'; //link component for navigation and useForm for form handling or submission

const showPassword = ref(false); //controls whether passw is visible or hidden

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', { //sends the form data to Laravel's /login route using a POST request
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <main class="auth-page">
        <section class="auth-brand">
            <div class="brand-content">
                <span class="brand-label">THE SKATING AWARDS</span>
                <h1>Your vote. Your voice. Your skaters.</h1>
                <p style="color: black;">
                    Sign in and show some love to the talent
                    shaping the skating community.
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

                <h2>Welcome back</h2>
                <p class="subtitle">
                    Sign in to continue to your account.
                </p>

                <form @submit.prevent="submit">
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
                        <div class="password-heading">
                            <label for="password">Password</label>
                            <a href="#" class="forgot-link">
                                Forgot password?
                            </a>
                        </div>

                        <div class="password-wrapper">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Enter your password"
                                autocomplete="current-password"
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
                        <span v-if="form.errors.email && !form.password" class="error">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <label class="remember-option">
                        <input
                            v-model="form.remember"
                            type="checkbox"/>Remember me
                    </label>

                    <button
                        type="submit"
                        class="submit-button"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Signing in...' : 'Log in' }}
                    </button>
                </form>

                <p class="auth-switch">
                    Don't have an account?
                    <Link href="/register">Create an account</Link>
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
    font-family: cursive;
    background: white;
    color: black;
}

.auth-brand {
    display: flex;
    align-items: center;
    padding: 70px;
    background: linear-gradient(
        to right,
        #FFF5E7 0%,
        #FFF5E7 35%,
        #FFF0E4 65%,
        #FFF0E4 100%
    );
    color: black;
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
    font-size: 17px;
    line-height: 1.7;
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
    color: black;
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
    margin-bottom: 35px;
}

.form-group {
    margin-bottom: 22px;
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

.password-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.forgot-link {
    font-size: 12px;
    color: rgb(243, 118, 139);
    text-decoration: none;
    margin-bottom: 8px;
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

.remember-option {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 13px;
    color: #555;
    margin: 5px 0 24px;
}

.remember-option input {
        accent-color: #f0cb98;

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
    margin-top: 28px;
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