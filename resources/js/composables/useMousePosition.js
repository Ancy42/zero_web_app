import { ref, onMounted, onUnmounted, computed } from "vue";

export function useMousePosition(threshold = 50) {
  // Reactive state
  const mouseX = ref(0);
  const mouseY = ref(0);
  const windowWidth = ref(0);
  const windowHeight = ref(0);

  // Tracking control
  const isTracking = ref(true);

  // Computed: mouse is near bottom or right edge
  const isAtBottom = computed(() => mouseY.value >= windowHeight.value - threshold);
  const isAtRight = computed(() => mouseX.value >= windowWidth.value - threshold);

  // Event handlers
  const updateMouse = (e) => {
    if (!isTracking.value) return; // stop updating when tracking is paused
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
  };

  const updateWindowSize = () => {
    windowWidth.value = window.innerWidth;
    windowHeight.value = window.innerHeight;
  };

  // Control methods
  const stopTracking = () => {
    isTracking.value = false;
  };

  const startTracking = () => {
    isTracking.value = true;
  };

  onMounted(() => {
    updateWindowSize();
    window.addEventListener("mousemove", updateMouse);
    window.addEventListener("resize", updateWindowSize);
  });

  onUnmounted(() => {
    window.removeEventListener("mousemove", updateMouse);
    window.removeEventListener("resize", updateWindowSize);
  });

  return {
    mouseX,
    mouseY,
    windowWidth,
    windowHeight,
    isAtBottom,
    isAtRight,
    isTracking,
    stopTracking,
    startTracking,
  };
}
