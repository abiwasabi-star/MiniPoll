<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="navbar">
        <!--logo-->
        <div class="logo">
            <Link href="/" aria-label="Skate Awards home">
                <img src="/public/Images/logo7.png" alt="Skate Awards" />
                SKATE AWARDS
            </Link>
            
        </div>

        <div class="nav-links">
            <Link href="/admin/dashboard">Overview</Link>
            <Link href="/admin/contestants">Contestants</Link>
            <Link href="/admin/categories">Categories</Link>
        </div>

        <!-- Profile dropdown -->
        <div class="profile-container">
          <Link
            href="/profile"
            class="profile-icon"
            aria-label="View profile"
            title="Profile">
            <i class="fa-solid fa-user"></i>
          </Link>
        </div>
    </nav>
</template>

<style scoped>
.navbar {
    width: 100%;
    min-height: 75px;
    padding: 0 6%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border-bottom: 1px solid #eeeeee;
    box-sizing: border-box;
    background: black;
}


.logo {
    font-size: 21px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #222222;
}
.logo a {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #ce9c5f;
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

.nav-links {
    display: flex;
    align-items: center;
    gap: 35px;
}
.nav-links a {
    text-decoration: none;
    color: #ce9c5f;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
}
.nav-links a:hover {
    color: #fef0e3;
}
@media (max-width: 600px) {
    .navbar {
        padding: 15px 4%;
        flex-wrap: wrap;
        gap: 15px;
    }

    .nav-links {
        order: 3;
        width: 100%;
        justify-content: center;
        gap: 22px;
    }
}
.profile-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border: 1px solid #ce9c5f;
    border-radius: 50%;
    color: #ce9c5f;
    font-size: 18px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.profile-icon:hover {
    background: #ce9c5f;
    color: #111112;
}
.logout-btn {
    width: 100%;
    padding: 10px;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    color: #bd6674;
    font-size: 14px;
}

.logout-btn:hover {
    background: #fdf2f4;
    border-radius: 5px;
}

@media (max-width: 600px) {
    .navbar {
        padding: 15px 4%;
        flex-wrap: wrap;
        gap: 15px;
    }

    .nav-links {
        order: 3;
        width: 100%;
        justify-content: center;
        gap: 22px;
    }
}
</style>
