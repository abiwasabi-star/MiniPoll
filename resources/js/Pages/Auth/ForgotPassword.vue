<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

function submit() {
    form.post('/forgot-password', {
        onFinish: () => form.reset(),
    });
}
</script>

<template>
    <main class="auth-page">
        <section class="auth-brand">
            <div class="brand-content">
                <span class="brand-label">THE SKATING AWARDS</span>

                <h1>Your vote. Your voice. Your skaters.</h1>

                <p>
                    Sign in and show some love to the talent
                    shaping the skating community.
                </p>

                <img
                    src="/public/Images/logo7.png" 
                    alt="Skate Awards"
                />
            </div>
        </section>

        <section class="auth-form-section">
            <div class="auth-card">
                <div class="mobile-brand">
                    <img src="/public/Images/logo7.png"  alt="Skate Awards" />
                    SKATING AWARDS
                </div>

                <h2>Forgot your password?</h2>

                <p class="subtitle">
                    No worries! Enter your email address and
                    we'll send you a link to reset your password.
                </p>

                <div v-if="$page.props.flash?.status" class="success">
                    {{ $page.props.flash.status }}
                </div>

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

                    <button
                        type="submit"
                        class="submit-button"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Sending...' : 'Send reset link' }}
                    </button>
                </form>

                <p class="auth-switch">
                    <Link href="/login">Back to login</Link>
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
    color: rgb(128, 0, 73);

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
    border-color:rgb(128, 0, 73);

}

.password-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.forgot-link {
    font-size: 12px;
    color: rgb(128, 0, 73);
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
    color: rgb(128, 0, 73);
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
        accent-color: rgb(128, 0, 73);


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

.success {
    color: #217a45;
    background: #eaf7ef;
    padding: 12px;
    border-radius: 7px;
    font-size: 13px;
    margin-bottom: 20px;
}

.auth-switch a {
    color: rgb(128, 0, 73);
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