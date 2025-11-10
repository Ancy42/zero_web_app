<template>
  <div class="relative w-full flex justify-center items-center min-h-[300px] bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="liquid-card">
      <slot />
    </div>

    <svg>
      <filter id="liquid-glass-filter">
        <feTurbulence
          type="fractalNoise"
          baseFrequency="0.015"
          numOctaves="3"
          seed="2"
          result="turbulence"
        >
          <animate attributeName="baseFrequency" dur="10s" values="0.01;0.02;0.01" repeatCount="indefinite" />
        </feTurbulence>
        <feDisplacementMap
          in="SourceGraphic"
          in2="turbulence"
          scale="25"
          xChannelSelector="R"
          yChannelSelector="G"
        />
      </filter>
    </svg>
  </div>
</template>

<style scoped>
.liquid-card {
  width: 80%;
  padding: 2rem;
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);

  /* Apply liquid distortion */
  filter: url(#liquid-glass-filter);

  /* Glass blur on background */
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  color: white;
  text-align: center;
}
</style>
