
<script setup>
import { Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import PublicNavbar from '@/Components/PublicNavbar.vue';
// import Button from '@/Components/Button.vue';
</script>

<template>
    <main class="home-page">

        <PublicNavbar />

        <div class="page-side-label">
                SKATE CULTURE · TALENT · COMMUNITY · STREET RAMPAGE · SKATE CULTURE · TALENT · COMMUNITY · STREET RAMPAGE
        </div>

        <!-- HERO -->
        <section class="hero" id="home">

            <!-- <div class="hero-image"></div> -->
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <p class="eyebrow">
                    <span></span>
                    CELEBRATING THE SKATING COMMUNITY
                </p>

                <h1>
                    THE ART OF<br />
                    <span>SKATING.</span>
                </h1>

                <p class="hero-description">
                    Recognising the talent. Celebrating the passion.
                    Giving every skater the recognition they deserve.
                </p>

                <div class="hero-actions">
                    <Link href="/register" class="primary-button">
                        CAST YOUR VOTE <span>↗</span>
                    </Link>

                    <a href="#categories" class="secondary-button">
                        EXPLORE CATEGORIES
                    </a>
                </div>
            </div>

            <div class="hero-footer">
              <div class="hero-bottom">
                 <span> THE AWARDS</span>
                 <span>BUILT FOR THE LOVE OF SKATING</span>
              </div>
              <div class="scroll-indicator">
                 <span>SCROLL TO EXPLORE</span>
                 <span>↓</span>
              </div>
            </div>

        </section>

             <!-- CATEGORIES -->
              <section></section>

        <!-- ABOUT -->
        <section class="about-section" id="about">
            <p class="eyebrow">
                <span></span>
                MORE THAN A SPORT
            </p>

            <h2>
                EVERY SKATER HAS A STORY.
                <span>LET'S CELEBRATE IT.</span>
            </h2>

            <Link href="/register" class="primary-button">
                BE PART OF IT <span>↗</span>
            </Link>
        </section>

        <Footer />

    </main>
</template>

<style scoped>
:global(*) {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:global(html),
:global(body),
:global(#app) {
    width: 100%;
    min-height: 100%;
    margin: 0;
    padding: 0;
}

:global(body) {
    overflow-x: hidden;
}

.home-page {
    width: 100%;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    position: relative;

    background:
        linear-gradient(
            rgba(17, 17, 18, 0.45),
            rgba(17, 17, 18, 0.75)
        ),
        url('/images/backg.jpg') center top / cover no-repeat fixed;

    color: #f2eeee;
    font-family: cursive, Arial, sans-serif;
}



/* HERO */

.hero {
    min-height: 660px;
    height: calc(100vh - 90px);
    max-height: 850px;
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 10%;
    isolation: isolate;
}

.hero-image {
    display: none;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    background:
        linear-gradient(90deg,
            rgba(13, 13, 14, 0.96) 0%,
            rgba(13, 13, 14, 0.82) 36%,
            rgba(13, 13, 14, 0.25) 75%,
            rgba(13, 13, 14, 0.5) 100%
        ),
        linear-gradient(0deg, #111112 0%, transparent 25%);
}

.hero-content {
    max-width: 650px;
    padding-bottom: 30px;
}

.eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ce9c5f;
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 2.5px;
}

.eyebrow span {
    display: inline-block;
    width: 25px;
    height: 2px;
    background: #ce9c5f;
}

.hero h1 {
    font-size: clamp(55px, 8vw, 108px); /*responsive but keeps it within specified limits*/
    /* minimum,prefferred,maximum */
    line-height: 0.96;
    letter-spacing: -5px;
    margin: 30px 0;
    font-weight: 900;
}
.hero h1 span {
    color: #ce9c5f;
}

.hero-description {
    max-width: 430px;
    color: #c2bcbc;
    line-height: 1.8;
    font-size: 14px;
}

.hero-actions {
    display: flex;
    align-items: center;
    gap: 25px;
    margin-top: 35px;
    flex-wrap: wrap;
}

.primary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 25px;
    padding: 16px 22px;

    background: rgba(206, 156, 95, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    color: #ce9c5f;
    text-decoration: none;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 1px;

    border: 1px solid rgba(206, 156, 95, 0.55);
    border-radius: 50px;

    box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.12),
                0 4px 15px rgba(0, 0, 0, 0.12);

    transition: all 0.3s ease;
}

.primary-button:hover {
    background: rgba(206, 156, 95, 0.2);
    border-color: #ce9c5f;
    transform: translateY(-4px);
    box-shadow: 0 8px 22px rgba(206, 156, 95, 0.2),
                inset 0 1px 2px rgba(255, 255, 255, 0.2);
}

/* SECONDARY BUTTON */

.secondary-button {
    display: inline-flex;
    align-items: center;
    color: #e3dddd;
    text-decoration: none;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;

    padding: 10px 4px;
    border-bottom: 1px solid rgba(206, 156, 95, 0.7);

    transition: all 0.3s ease;
}

.secondary-button:hover {
    color: #ce9c5f;
    border-bottom-color: #ce9c5f;
    transform: translateY(-3px);
    text-shadow: 0 0 10px rgba(206, 156, 95, 0.3);
}



.page-side-label {
    position: absolute;
    right: 4%;
    top: -30%;
    writing-mode: vertical-rl;
    color: rgb(128, 0, 73);
    font-size: 9px;
    letter-spacing: 3px;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 5;
    pointer-events: none;
}

.hero-footer {
    position: absolute;
    bottom: 35px;
    left: 10%;
    right: 10%;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.hero-bottom {
    display: flex;
    align-items: center;
    gap: 35px;
    color: rgb(128, 0, 73);
    font-size: 9px;
    letter-spacing: 2px;
}

.scroll-indicator {
    display: flex;
    align-items: center;
    gap: 8px;

    color: rgb(128, 0, 73);
    font-size: 9px;
    letter-spacing: 1.5px;
    white-space: nowrap;
}

.scroll-indicator span:last-child {
    color: rgb(128, 0, 73);
    font-size: 15px;
}

/* CATEGORIES */

/* .categories-section {
    padding: 110px 10%;
    background: transparent;
} */

/* .section-heading {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 45px;
} */

.section-heading h2 {
    font-size: clamp(35px, 5vw, 50px);
    margin: 18px 0 0;
    letter-spacing: -2px;
}

.section-heading h2 span {
    color: rgb(128, 0, 73);
}

.section-description {
    max-width: 290px;
    color:rgb(128, 0, 73);
    font-size: 13px;
    line-height: 1.8;
}

/* ABOUT */

.about-section {
    padding: 110px 10%;
    text-align: center;
    background: transparent;
}
.about-section .eyebrow {
    justify-content: center;
}
.about-section h2 {
    max-width: 850px;
    margin: 30px auto;
    font-size: clamp(32px, 5vw, 58px);
    line-height: 1.2;
}
.about-section h2 span {
    display: block;
    color: #ce9c5f;
}

/* RESPONSIVE */

@media (max-width: 850px) {
    .navbar {
        padding: 0 5%;
    }
    .nav-links {
        display: none;
    }
    .hero {
        padding: 0 7%;
    }
    .hero-bottom {
        left: 7%;
        right: 7%;
    }
    .hero-side-label {
        display: none;
    }
    .categories-section,
    .about-section {
        padding: 80px 7%;
    }
}

@media (max-width: 600px) {
    .navbar {
        height: 75px;
    }

    .brand {
        font-size: 14px;
    }

    .nav-actions {
        gap: 13px;
    }

    .login-link {
        font-size: 9px;
    }

    .register-link {
        padding: 11px;
        font-size: 8px;
    }

    .hero {
        min-height: 650px;
        height: calc(100svh - 75px);
        padding: 0 7%;
    }

    .hero-image {
        background-position: 58% center;
    }

    .hero-overlay {
        background:
            linear-gradient(90deg,
                rgba(13, 13, 14, 0.91),
                rgba(13, 13, 14, 0.45)
            ),
            linear-gradient(0deg, #111112 0%, transparent 35%);
    }

    .hero h1 {
        font-size: clamp(52px, 13vw, 75px);
        letter-spacing: -3px;
    }

    .hero-description {
        font-size: 13px;
    }

    .hero-bottom {
        bottom: 25px;
        font-size: 8px;
    }

    .hero-bottom span:last-child {
        display: none;
    }

    .scroll-indicator {
        display: none;
    }

    .section-heading {
        align-items: start;
        flex-direction: column;
    }

    .category-grid {
        grid-template-columns: 1fr;
    }

    .category-card {
        min-height: 300px;
    }

    .footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
