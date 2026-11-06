<!-- <script setup>
import { computed } from 'vue'

const props = defineProps({
    href: { type: String, default: null },
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'gold' }, // gold | light | dark
    disabled: { type: Boolean, default: false },
})

// renders a link if href is given, otherwise a real <button>
const tag = computed(() => (props.href ? 'a' : 'button'))
</script>

<template>
    <component
        :is="tag"
        :href="href"
        :type="href ? undefined : type"
        :disabled="!href && disabled"
        :class="['glass-btn', `glass-btn--${variant}`]"
    >
        <slot />
    </component>
</template>

<style scoped>
.glass-btn {
    --rgb: 206, 156, 95; /* default gold */

    position: relative;
    isolation: isolate;
    overflow: hidden;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 14px 30px;

    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    color: rgb(var(--rgb));
    cursor: pointer;

    background: linear-gradient(
        180deg,
        rgba(var(--rgb), 0.22) 0%,
        rgba(var(--rgb), 0.04) 100%
    );
    backdrop-filter: blur(12px) saturate(140%);
    -webkit-backdrop-filter: blur(12px) saturate(140%);

    border: 1px solid rgba(var(--rgb), 0.5);
    border-radius: 999px;

    box-shadow:
        inset 0 1px 1px rgba(255, 255, 255, 0.35),
        inset 0 -1px 2px rgba(0, 0, 0, 0.15),
        0 6px 20px rgba(0, 0, 0, 0.2);

    transition: transform 0.3s ease, background 0.3s ease,
        border-color 0.3s ease, box-shadow 0.3s ease;
}

/* glossy reflection on the top half */
.glass-btn::before {
    content: '';
    position: absolute;
    z-index: -1;
    top: 2px;
    left: 8%;
    width: 84%;
    height: 45%;
    border-radius: 999px;
    background: linear-gradient(
        180deg,
        rgba(255, 255, 255, 0.28),
        rgba(255, 255, 255, 0.02)
    );
    pointer-events: none;
}

.glass-btn:hover:not(:disabled) {
    transform: translateY(-3px);
    border-color: rgb(var(--rgb));
    background: linear-gradient(
        180deg,
        rgba(var(--rgb), 0.32) 0%,
        rgba(var(--rgb), 0.1) 100%
    );
    box-shadow:
        inset 0 1px 2px rgba(255, 255, 255, 0.45),
        inset 0 -1px 2px rgba(0, 0, 0, 0.15),
        0 10px 26px rgba(var(--rgb), 0.3);
}

.glass-btn:active:not(:disabled) {
    transform: translateY(0);
}

.glass-btn:focus-visible {
    outline: 2px solid rgb(var(--rgb));
    outline-offset: 3px;
}

.glass-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* variants only change the color */
.glass-btn--light { --rgb: 255, 255, 255; }
.glass-btn--dark  { --rgb: 30, 30, 30; }
</style> -->