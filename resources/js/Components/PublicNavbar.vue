<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="navbar">
        <!-- Logo -->
        <div class="logo">
            <Link href="/" aria-label="Skate Awards home">
                <img src="/public/Images/logo7.png" alt="Skate Awards" />
                SKATE AWARDS
            </Link>
        </div>

        <!-- Navigation links -->
        <div class="nav-links">
            <Link href="/vote">Home</Link>
            <a href="/vote#categories">Categories</a>
            <a href="/vote#about">About</a>
        </div>

        <div class="nav-actions">
                <Link href="/login" class="secondary-button">LOG IN</Link>
                <Link href="/register" class="secondary-button">
                    VOTE NOW
                </Link>
        </div>
    </nav>
</template>

<style scoped>

.navbar {
    width: 100%;
    min-height: 85px;
    padding: 0 6%;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;

    box-sizing: border-box;
    background: #111112;
    border-bottom: 1px solid rgb(128, 0, 73);

    /* Fixed positioning */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
}

/* LOGO */

.logo {
    flex-shrink: 0;
    font-size: 21px;
    font-weight: 800;
    letter-spacing: 1px;
}

.logo a {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: rgb(128, 0, 73);
}

.logo img {
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

/* NAVIGATION LINKS */

.nav-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 35px;
}

.nav-links a {
    text-decoration: none;
    color: rgb(128, 0, 73);
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    transition: color 0.2s;
}

.nav-links a:hover {
    color: #fef0e3;
}

/* ACTION BUTTONS */

.nav-actions {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-shrink: 0;
}

.secondary-button {
    display: inline-flex;
    align-items: center;
        color: rgb(128, 0, 73);

    text-decoration: none;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;

    padding: 10px 4px;
    /* border-bottom: 1px solid rgb(128, 0, 73); */


    transition: all 0.3s ease;
}

.secondary-button:hover {
    transform: translateY(-3px);
   
}

/* TABLETS AND SMALLER LAPTOPS */

@media (max-width: 950px) {
    .navbar {
        padding: 0 3%;
        gap: 15px;
    }

    .logo {
        font-size: 17px;
    }

    .logo img {
        width: 45px;
    }

    .nav-links {
        gap: 18px;
    }

    .nav-actions {
        gap: 8px;
    }

    .login-link {
        padding: 10px 12px;
    }

    .register-link {
        padding: 12px 14px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .navbar {
        min-height: 110px;
        padding: 10px 4%;
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto;
        gap: 12px;
    }

    .logo {
        font-size: 16px;
    }

    .logo img {
        width: 40px;
    }

    .nav-actions {
        gap: 8px;
    }

    .login-link {
        font-size: 11px;
        padding: 9px 10px;
    }

    .register-link {
        font-size: 11px;
        padding: 10px 12px;
    }

    .nav-links {
        grid-column: 1 / -1;
        grid-row: 2;
        width: 100%;
        justify-content: center;
        gap: 30px;
    }

    .nav-links a {
        font-size: 12px;
    }
}

/* VERY SMALL PHONES */

@media (max-width: 380px) {
    .navbar {
        padding-left: 3%;
        padding-right: 3%;
    }

    .logo {
        font-size: 13px;
        letter-spacing: 0;
    }

    .logo img {
        width: 34px;
    }

    .nav-actions {
        gap: 5px;
    }

    .login-link,
    .register-link {
        font-size: 10px;
        padding: 8px 9px;
    }

    .nav-links {
        gap: 22px;
    }
}
</style>
