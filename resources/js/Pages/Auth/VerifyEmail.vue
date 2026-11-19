<script setup>
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();

function resendVerification() {
    router.post('/email/verification-notification');
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="verification-container">
        <div class="verification-card">

            <h1>Verify Your Email</h1>

            <p>
                Thanks for registering with Skates Awards!
                Before getting started, please verify your email
                address by clicking the link we just sent you.
            </p>

            <p>
                Didn't receive the email? You can request another one below.
            </p>

            <p v-if="page.props.flash?.status === 'verification-link-sent'"
               class="success-message">
                A new verification link has been sent to your email.
            </p>

            <button @click="resendVerification">
                Resend Verification Email
            </button>

            <button @click="logout" class="logout-button">
                Log Out
            </button>

        </div>
    </div>
</template>

<style scoped>
.verification-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f5f5f5;
    padding: 20px;
}

.verification-card {
    background: white;
    padding: 35px;
    border-radius: 12px;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.verification-card h1 {
    margin-bottom: 20px;
}

.verification-card p {
    line-height: 1.6;
    margin-bottom: 18px;
    color: #555;
}

.verification-card button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: #d9534f;
    color: white;
    margin-top: 10px;
}

.verification-card .logout-button {
    background: #555;
}

.success-message {
    color: green !important;
}
</style>
