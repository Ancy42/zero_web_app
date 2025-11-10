import { ref, onMounted, onUnmounted } from 'vue'


export function useScroll() {
  const scrollY = ref(0)
  const viewportHeight = ref(window.innerHeight)
  const viewportWidth = ref(window.innerWidth) // Added viewportWidth
  const documentHeight = ref(document.documentElement.scrollHeight)
  const scrollPercentage = ref(0)

  const updateScroll = () => {
    scrollY.value = window.scrollY
    viewportHeight.value = window.innerHeight
    viewportWidth.value = window.innerWidth // Update viewportWidth
    documentHeight.value = document.documentElement.scrollHeight
    
    // Calculate scroll percentage relative to viewport
    const scrollableHeight = documentHeight.value - viewportHeight.value
    scrollPercentage.value = scrollableHeight > 0 
      ? (scrollY.value / scrollableHeight) * 100 
      : 0
  }

  onMounted(() => {
    window.addEventListener('scroll', updateScroll)
    window.addEventListener('resize', updateScroll)
    updateScroll() // Initial calculation
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', updateScroll)
    window.removeEventListener('resize', updateScroll)
  })

  return {
    scrollY, // Absolute scroll position in pixels
    viewportHeight, // Current viewport height
    viewportWidth, // Current viewport width
    documentHeight, // Total document height
    scrollPercentage // Scroll position as percentage of scrollable content
  }
}