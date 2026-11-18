
<script setup>
import { Link } from '@inertiajs/vue3';

const categories = [
    { name: 'Best Female Skater', number: '01', image: '/Images/categories/crew.jpg' },
    { name: 'Best Male Skater', number: '02', image: '/Images/categories/free.jpg' },
    { name: 'Best Trick', number: '03', image: '/Images/categories/trick.jpg' },
    { name: 'Rising Star', number: '04', image: '/Images/categories/star.jpg' },
    { name: 'Best Street Skater', number: '05', image: '/Images/categories/newbie.jpg' },
    { name: 'Best Freestyle', number: '06', image: '/Images/categories/trick.jpg' },
    { name: 'Best Skate Crew', number: '07', image: '/Images/categories/oftheyear.jpg' },
    { name: 'Best Newcomer', number: '08', image: '/Images/categories/star.jpg' },
    { name: 'People’s Choice', number: '09', image: '/Images/categories/pples.jpg' },
    { name: 'Skater of the Year', number: '10', image: '/Images/categories/street.jpg' },
];
</script>

<template>
    <section class="category-showcase" id="categories">

        <!-- LEFT COLUMN -->
        <div class="category-intro">

            <p class="eyebrow">
                <span></span>
                THE AWARDS
            </p>

            <h2>
                RECOGNISING
                <span>THE CULTURE.</span>
            </h2>

            <p class="intro-description">
                From fearless tricks to unmatched creativity,
                we celebrate the individuals shaping the skating
                scene. Every category tells a story. Every skater
                deserves their moment.
            </p>

            <div class="category-stat">
                <span class="stat-line"></span>
                <div>
                    <strong>MORE THAN JUST A SPORT.</strong>
                    <p>A CULTURE. A COMMUNITY. A MOVEMENT.</p>
                </div>
            </div>

            <Link href="/register" class="primary-button">
                BE PART OF THE MOVEMENT
            </Link>

        </div>


        <!-- RIGHT COLUMN -->
        <div class="category-gallery">

    <!-- Molecular connection network -->
    <svg
        class="gallery-lines"
        viewBox="0 0 720 480"
        fill="none"
        preserveAspectRatio="xMidYMid meet"
    >
        <!-- Main connecting bonds -->
        <g class="molecule-bonds">
            <path d="M235 85 L310 195 L460 195 L535 85" />
            <path d="M85 195 L160 85 L235 85" />
            <path d="M460 195 L610 195 L535 305" />
            <path d="M160 305 L235 415 L385 415 L460 305" />
            <path d="M310 195 L385 85 L535 85" />
            <path d="M85 195 L160 305 L310 305" />
            <path d="M460 195 L535 305 L610 195" />
            <path d="M310 305 L385 415 L535 305" />
        </g>

        <!-- Small molecular junctions -->
        <g class="molecule-nodes">
            <circle cx="235" cy="85" r="4" />
            <circle cx="310" cy="195" r="4" />
            <circle cx="460" cy="195" r="4" />
            <circle cx="535" cy="85" r="4" />
            <circle cx="160" cy="305" r="4" />
            <circle cx="535" cy="305" r="4" />
            <circle cx="385" cy="415" r="4" />
            <circle cx="85" cy="195" r="3" />
            <circle cx="610" cy="195" r="3" />
        </g>
    </svg>

    <div class="hex-grid">

        <div
            v-for="category in categories"
            :key="category.number"
            class="hex-card"
            :style="{
                backgroundImage: `linear-gradient(to top, rgba(0,0,0,.85), transparent 75%), url('${category.image}')`
            }"
        >
            <div class="hex-content">
                <span class="hex-number">{{ category.number }}</span>
                <h3>{{ category.name }}</h3>
            </div>
        </div>

    </div>

    <span class="gallery-caption">
        TEN CATEGORIES. ONE PASSION.
    </span>

</div>

    </section>
</template>

<style>
.category-showcase {
    width: 100%;
    min-height: 750px;
    padding: 100px 7%;

    display: grid;
    grid-template-columns: minmax(0, 0.85fr) minmax(0, 1.15fr);
    align-items: center;
    justify-items: center;
    gap: 40px;

    overflow: hidden;
}

/* LEFT COLUMN */

.category-intro {
    width: 100%;
    max-width: 470px;
    min-height: 500px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center;
}

.eyebrow {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;

    color: #800049;
    letter-spacing: 3px;
    font-size: 11px;
    margin-bottom: 25px;
}

.eyebrow span {
    width: 30px;
    height: 1px;
    background: #800049;
}

.category-intro h2 {
    font-size: clamp(38px, 5vw, 65px);
    line-height: 1.05;
    letter-spacing: -2px;
    margin-bottom: 25px;
}

.category-intro h2 span {
    display: block;
    color: #800049;
}

.intro-description {
    color: black;
    font-size: 15px;
    line-height: 1.9;
    max-width: 400px;
    width: 100%;
}

.category-stat {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin: 35px 0;
    text-align: left;
}

.stat-line {
    width: 3px;
    height: 45px;
    flex-shrink: 0;
    background: #800049;
}

.category-stat strong {
    font-size: 11px;
    letter-spacing: 1.5px;
}

.category-stat p {
    color: black;
    font-size: 10px;
    margin-top: 5px;
    letter-spacing: 1px;
}

.primary-button {
    display: inline-flex;
    align-items: center;
    color: rgb(128, 0, 73);
    text-decoration: none;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;

    padding: 10px 4px;
    border-bottom: 1px solid black;


    transition: all 0.3s ease;
   
}

.primary-button:hover {
    transform: translateY(-4px);
    
}


/* RIGHT COLUMN */
.category-gallery {
    position: relative;
    width: min(100%, 488px);
    aspect-ratio: 488 / 594;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 0;
}

.hex-grid {
    position: absolute;
    width: 487.5px;
    height: 594px;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%) scale(1);
    transform-origin: center;
}

/* HEXAGON CARDS */

.hex-card {
    position: absolute;

    width: 150px;
    height: 132px;

    background-size: cover;
    background-position: center;
    background-color: #bbaab1;

    clip-path: polygon(
        25% 0,
        75% 0,
        100% 50%,
        75% 100%,
        25% 100%,
        0 50%
    );

    display: flex;
    align-items: flex-end;
    padding: 0 20px 22px;

    cursor: pointer;
    transition: filter .35s ease;

    filter: drop-shadow(0 0 1px #800049);
}

.hex-card:hover {
    filter: brightness(1.15);
}


/*connected honeycomb layout*/
/* First row */
.hex-card:nth-child(1) {
    left: 112.5px;
    top: 66px;
}

.hex-card:nth-child(2) {
    left: 225px;
    top: 132px;
}

.hex-card:nth-child(3) {
    left: 337.5px;
    top: 198px;
}

/* Middle row */
.hex-card:nth-child(4) {
    left: 0;
    top: 132px;
}

.hex-card:nth-child(5) {
    left: 112.5px;
    top: 198px;
}

.hex-card:nth-child(6) {
    left: 225px;
    top: 264px;
}

.hex-card:nth-child(7) {
    left: 337.5px;
    top: 330px;
}

/* Bottom row */
.hex-card:nth-child(8) {
    left: 112.5px;
    top: 330px;
}

.hex-card:nth-child(9) {
    left: 225px;
    top: 396px;
}

.hex-card:nth-child(10) {
    left: 337.5px;
    top: 462px;
}








.hex-content {
    color: white;
    width: 100%;
}

.hex-number {
    font-size: 10px;
    color: #f0b5d0;
    letter-spacing: 2px;
}

.hex-content h3 {
    font-size: 12px;
    line-height: 1.2;
    margin-top: 5px;
    max-width: 110px;
}

/* MOLECULAR CONNECTIONS */

.gallery-lines {
    display: none;
}

.molecule-bonds path {
    stroke: #bd8da4;
    stroke-width: 1.4;
    stroke-linecap: round;
    opacity: .75;
}

.molecule-nodes circle {
    fill: #800049;
    stroke: #f8f5f6;
    stroke-width: 1.5;
}

.gallery-caption {
    position: absolute;
    bottom: 0;
    right: 0;
    font-size: 9px;
    letter-spacing: 3px;
    color: black;
}
/* TABLETS */
@media (max-width: 1000px) {
    .category-gallery {
        width: min(100%, 488px);
    }

    .hex-grid {
        transform: translate(-50%, -50%) scale(.9);
    }
}

/* SMALL TABLETS */
@media (max-width: 750px) {
    .hex-grid {
        transform: translate(-50%, -50%) scale(.78);
    }
}

/* MOBILE */
@media (max-width: 600px) {
    .hex-grid {
        transform: translate(-50%, -50%) scale(.65);
    }
}

/* SMALL PHONES */
@media (max-width: 400px) {
    .hex-grid {
        transform: translate(-50%, -50%) scale(.55);
    }
}
</style>