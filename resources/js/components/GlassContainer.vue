<template>
    <div class="relative w-full flex justify-center items-center bg-black md:bg-zinc-700/20">
        <div class="liquid-card">
            <slot />
        </div>

        <svg>
            <!-- SVG filter for the liquid effect -->
            <filter id="liquid-glass-filter">
                <feTurbulence
                    type="fractalNoise"
                    baseFrequency="0.02"
                    numOctaves="1"
                    result="noise"
                />
                <feDisplacementMap
                    in="SourceGraphic"
                    in2="noise"
                    scale="140"
                    xChannelSelector="R"
                    yChannelSelector="G"
                />
            </filter>
        </svg>
    </div>

</template>


<script setup>
</script>

<style scoped>

/* Glass card element */
.liquid-card {
    width: 100%;
    background: rgba(
        255,
        255,
        255,
        0.1
    ); /* Semi-transparent white background */

    /* Use backdrop-filter to apply the SVG filter */
    backdrop-filter: blur(10px) url(#liquid-glass-filter);
    -webkit-backdrop-filter: blur(10px) url(#liquid-glass-filter); /* For Safari */
    animation: liquid-animation 20s infinite alternate; /* Animate the liquid effect */
}

/* Styling for content inside the card */
.card-content {
}


/* Keyframes for the animation */
@keyframes liquid-animation {
    0% {
        backdrop-filter: blur(10px) url(#liquid-glass-filter);
        -webkit-backdrop-filter: blur(10px) url(#liquid-glass-filter);
    }
    100% {
        backdrop-filter: blur(10px) url(#liquid-glass-filter);
        -webkit-backdrop-filter: blur(10px) url(#liquid-glass-filter);
    }
}

/* The SVG for the filter must be in the HTML */
svg {
    display: none;
}
</style>