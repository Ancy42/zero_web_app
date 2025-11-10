<template>
    <div class="flex justify-start items-center gap-8">
        <div
            v-for="(menu, index) in master.menus"
            :key="menu.id"
            class="flex items-center gap-2.5 lg:gap-3 xl:gap-5 2xl:gap-6"
        >
            <template v-if="!menu.is_external">
                <router-link
                    v-if="menu.name != 'categories'"
                    :to="menu.url"
                    :target="menu.target"
                    class="text-zinc-900 text-base font-normal font-['Montserrat'] leading-normal capitalize"
                >
                    {{ menu.name }}
                </router-link>
            </template>

            <a
                v-else
                :href="menu.url"
                :target="menu.target"
                class="text-zinc-900 text-base font-normal font-['Montserrat'] leading-normal capitalize"
            >
                {{ menu.name }}
            </a>
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

// apis
const categoryApi = "/categories";

// store
const master = useMaster();

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
