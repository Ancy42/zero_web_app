<script setup>
import { ChevronRightIcon } from "@heroicons/vue/20/solid";
import { ref, Transition, onMounted, nextTick } from "vue";
import { useMousePosition } from "../composables/useMousePosition";

defineProps({
    items: {
        type: Array,
        required: true,
    },
    showImage: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["close"]);

const { mouseY, isAtBottom } = useMousePosition(60) // 60px from bottom


const hoveredId = ref(null);
const openDirection = ref({}); // store left/right per category id

const onHover = async (id, event) => {
    hoveredId.value = id;

    await nextTick();
    const rect = event.currentTarget.getBoundingClientRect();
    const spaceRight = window.innerWidth - rect.right;
    const spaceLeft = rect.left;

    // Decide direction dynamically
    if (spaceRight < 250 && spaceLeft > 250) {
        openDirection.value[id] = `right-full ${mouseY.value > 650 ? "bottom-0" : "top-0"}`; // open to the left
    } else {
        openDirection.value[id] = `left-full ${mouseY.value > 650 ? "bottom-0" : "top-0"}`; // default (open to the right)
    }
};

const onLeave = () => {
    hoveredId.value = null;
};

const closeMenu = () => {
    emits("close");
    hoveredId.value = null;
}
</script>

<template>
    <ul class="bg-white border rounded shadow-md w-64">
        <li
            v-for="item in items"
            :key="item.id"
            class="relative group"
            @mouseenter="(e) => onHover(item.id, e)"
            @mouseleave="onLeave"
        >
            <!-- Category row -->
            <RouterLink
                :to="`/categories/${item.id}`"
                @click="closeMenu"
                class="flex items-center gap-2 p-3 hover:bg-gray-100 cursor-pointer"
            >
                <img
                    v-if="showImage"
                    :src="item.thumbnail"
                    alt="thumb"
                    class="w-10 h-8 rounded border border-neutral-100 object-cover rounded"
                />
                <p
                    class="text-zinc-800 text-sm font-normal font-['Lato'] leading-snug"
                >
                    {{ item.name }}
                </p>

                <!-- Arrow if has children -->
                <ChevronRightIcon v-if="item.sub_categories?.length" class="w-4 h-4 text-slate-400 ml-auto" />
            </RouterLink>

            <!-- Subcategories (dropdown) -->
            <Transition name="slide-fade">
                <div
                    v-if="item.sub_categories?.length && hoveredId === item.id"
                    class="absolute "
                    :class="openDirection[item.id]"
                >
                    <CategoryMenu :items="item.sub_categories" />
                </div>
            </Transition>
        </li>
    </ul>
</template>

<style scoped>
/* dropdown animation */
.slide-fade-enter-active {
    transition: all 0.4s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.4s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
