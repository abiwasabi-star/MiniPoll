<script setup>
import { Link } from '@inertiajs/vue3';

const year = new Date().getFullYear();
</script>

<template>
    <footer class="footer">

        <!-- COLUMN 1: LOGO -->
        <div class="footer-column footer-logo">
            <Link href="/" aria-label="Skate Awards home">
                <img src="/public/Images/logo7.png" alt="Skate Awards" />
                SKATE AWARDS
            </Link>
        </div>

        <!-- COLUMN 2: TAGLINE -->
        <div class="footer-column footer-tagline">
            <p>CELEBRATING SKATING. RECOGNISING EXCELLENCE.</p>
        </div>

        <!-- COLUMN 3: COPYRIGHT -->
        <div class="footer-column footer-copyright">
            <span>
                © {{ year }} SKATE AWARDS. ALL RIGHTS RESERVED.
            </span>
        </div>

    </footer>
</template>

<style scoped>
.footer {
    width: 100%;
    min-height: 110px;
    padding: 25px 7%;

    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    align-items: center;
    gap: 20px;

    border-top: 1px solid #ffffff14;
    background: black;
    font-family: cursive;
}

.footer-column {
    display: flex;
    align-items: center;
}

.footer-logo a {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #ce9c5f;
}

.footer-logo img {
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

.footer-tagline,
.footer-copyright {
    justify-content: center;
    text-align: center;
}

.footer-tagline p,
.footer-copyright span {
    color:#ce9c5f;
    font-size: 9px;
    letter-spacing: 1.2px;
    line-height: 1.7;
}

@media (max-width: 700px) {
    .footer {
        grid-template-columns: 1fr;
        justify-items: center;
        text-align: center;
        padding: 30px 20px;
        gap: 22px;
    }
}
</style>
