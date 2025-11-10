<template>
    <div
        class="hidden md:inline-flex justify-start items-center gap-2.5 lg:gap-3 xl:gap-8 grow"
    >
        <div
            v-for="(menu, index) in master.menus"
            :key="menu.id"
            class="flex items-center gap-2.5 lg:gap-3 xl:gap-5 2xl:gap-6"
        >
            <template v-if="!menu.is_external">
                <router-link
                    v-if="menu.name.toLowerCase() !== 'categories'"
                    :to="menu.url"
                    :target="menu.target"
                    class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-medium text-zinc-900 whitespace-nowrap transtion-all duration-300 hover:text-primary"
                >
                    {{ menu.name }}
                </router-link>

                <div v-else>
                    <button
                        class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-medium whitespace-nowrap cursor-pointer capitalize text-zinc-900 transtion-all duration-300 hover:text-primary"
                        :class="showDropdown ? 'text-primary' : 'text-zinc-900'"
                        @click="showDropdown = !showDropdown"
                        @mouseenter="
                            () => {
                                showDropdown = true;
                                stopTracking();
                            }
                        "
                        @mouseleave="
                            () => {
                                showDropdown = false;
                                startTracking();
                            }
                        "
                    >
                        {{ menu.name }}
                    </button>
                    <Transition name="slide-fade">
                        <ul
                            v-if="showDropdown"
                            class="fixed top-22 left-0 h-auto bg-transparent w-full"
                        >
                            <div
                                class="relative h-auto w-full max-h-[80vh] overflow-y-scroll overflow-x-visible scrollbar-hide"
                            >
                                <div
                                    class="absolute top-0 w-fit"
                                    :style="{ left: mouseX-50 + 'px' }"
                                >
                                    <CategoryMenu
                                        @mouseenter="
                                            () => {
                                                showDropdown = true;
                                                stopTracking();
                                            }
                                        "
                                        @mouseleave="
                                            () => {
                                                showDropdown = false;
                                                startTracking();
                                            }
                                        "
                                        :items="allCategories"
                                        :showImage="true"
                                        @close="showDropdown = false"
                                    />
                                </div>
                            </div>
                        </ul>
                    </Transition>
                </div>
            </template>

            <a
                v-else
                :href="menu.url"
                :target="menu.target"
                class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-normal text-zinc-900"
            >
                {{ menu.name }}
            </a>
        </div>

        <div>
            <!-- <button
                class="flex items-center gap-2.5 lg:gap-3 xl:gap-5 2xl:gap-6"
                @click="showDropdown = !showDropdown"
            >
                <p
                    class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-normal text-slate-600 outline-none cursor-pointer"
                >
                    Categories
                </p>
            </button> -->

            <!-- categories  -->
            <!-- <Transition name="slide-fade">
                <ul
                    v-if="showDropdown"
                    class="absolute top-12 left-0 h-auto bg-white max-h-[50vh]"
                >
                    <CategoryMenu
                        :items="allCategories"
                        :showImage="true"
                        @close="showDropdown = false"
                    />
                </ul>
            </Transition> -->
        </div>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { useMaster } from "../stores/MasterStore";
import NavbarCategoryDropdown from "./NavbarCategoryDropdown.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import CategoryMenu from "./CategoryMenu.vue";
import { useMousePosition } from "@/composables/useMousePosition";

// apis
const categoryApi = "/categories";

// store
const master = useMaster();

// composable
const { mouseX, mouseY, stopTracking, startTracking, isTracking } =
    useMousePosition();

// variables
const popoverButton = ref(null);
const showDropdown = ref(false);
const allCategories = ref();

// Function to toggle the Popover by programmatically clicking the PopoverButton
const togglePopover = () => {
    if (popoverButton.value) {
        popoverButton.value.$el.click(); // Trigger a click on the PopoverButton
    }
};

// functions
const getCategories = async () => {
    allCategories.value = master.categories;
};

// on mount calls
onMounted(() => {
    getCategories();
});
</script>

<style scoped>
/* dropdown  */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(20px);
    opacity: 0;
}

/* route styles  */
.router-link-active {
    @apply text-primary;
}
.main__menu li:has(.router-link-active) a {
    @apply text-primary;
}
.main__menu li:has(.router-link-active) a::before {
    @apply w-full;
}

.main__menu {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 32px;
}
.main__menu li {
    list-style: none;
    position: relative;
    display: flex;
    align-items: center;
}
.main__menu li.has__dropdown::after {
    content: "\ea4e";
    font-family: "remixicon";
    position: relative;
    top: 2px;
    color: var(--color-state-600);
    font-size: 18px;
    cursor: pointer;
}
.main__menu li a,
div {
    font-family: var(--font-default);
    font-weight: var(--font-medium);
    font-size: 16px;
    line-height: 24px;
    color: var(--primary-600);
    padding: 0;
    position: relative;
    text-transform: capitalize;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.main__menu li a::before,
div::before {
    content: "";
    position: absolute;
    right: 0;
    bottom: -8px;
    height: 2px;
    width: 0;
    background: var(--primary);
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.main__menu li a.active,
.main__menu li a:hover {
    color: var(--primary);
}
.main__menu li a.active::before,
.main__menu li a:hover::before {
    width: 100%;
    left: 0;
}
.main__menu li .sub__menu {
    position: absolute;
    top: 140%;
    background: white;
    width: 160px;
    padding: 15px;
    border-radius: 4px;
    -webkit-box-shadow: 0px 17px 30px 0px rgba(112, 112, 112, 0.1019607843);
    box-shadow: 0px 17px 30px 0px rgba(112, 112, 112, 0.1019607843);
    opacity: 0;
    z-index: 99;
    visibility: hidden;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.main__menu li .sub__menu li a {
    font-size: 15px;
    padding-bottom: 5px;
    display: block;
}
.main__menu li .sub__menu li a:hover {
    color: var(--primary);
}
.main__menu li .sub__menu li a::before {
    display: none;
}
.main__menu li .sub__menu li:last-of-type a {
    padding-bottom: 0;
}
.main__menu li:hover .sub__menu {
    opacity: 1;
    visibility: visible;
    top: 120%;
}
</style>
