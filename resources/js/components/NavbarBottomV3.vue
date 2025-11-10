<template>
    <div class="lg:bg-slate-100">
        <div
            class="container-2 flex items-center justify-between md:gap-3 lg:gap-10 flex-nowrap relative py-2 px-4 lg:py-4 border-t border-b border-slate-100 lg:border-none"
        >
            <!--==== Categories dropdown menu ====-->
            <button
                @click="showCategoryMobileMenu = true"
                class="block lg:hidden p-3 bg-primary-50 rounded-lg"
            >
                <GridIcon width="24" height="24" class="fill-primary" />
            </button>

            <div
                class="hidden lg:block min-w-80 relative"
                @mouseenter="showDropdown = true"
                @mouseleave="showDropdown = false"
            >
                <button
                    class="w-full px-3 flex justify-between items-center gap-2 rounded-lg bg-white h-12"
                >
                    <GridIcon width="24" height="24" class="fill-primary" />

                    <p
                        class="w-full text-primary-600 text-base font-medium font-['Inter'] leading-normal text-start text-nowrap"
                    >
                        {{ $t("All Categories") }}
                    </p>

                    <ChevronDownIcon class="text-primary w-5 h-5" />
                </button>

                <!-- category dropdown  -->
                <Transition name="slide-fade">
                    <ul
                        v-if="showDropdown"
                        class="absolute top-full left-0 h-auto w-full p-3 pt-0 bg-slate-100"
                    >
                        <CategoryMenuV2
                            :items="allCategories"
                            :showImage="true"
                            @close="showDropdown = false"
                        />
                    </ul>
                </Transition>
            </div>

            <!-- divider  -->
            <div
                class="block lg:hidden w-0 self-stretch origin-top-left rotate-0 outline outline-1 outline-offset-[-0.50px] outline-neutral-100 mx-2"
            ></div>

            <!-- Header Search -->
            <form
                @submit.prevent="goToSearch"
                class="w-full h-12 bg-white rounded-lg hidden lg:flex justify-between items-center overflow-hidden"
            >
                <input
                    type="search"
                    name="search"
                    class="form-control grow h-full inline-block p-6 placeholder:text-gray-400 text-zinc-900 text-base font-normal font-['Lato'] leading-normal outline-none"
                    :placeholder="$t('Search for products') + '...'"
                />
                <button
                    type="submit"
                    class="h-full px-6 py-3 bg-primary-600 flex justify-center items-center gap-3"
                >
                    <SearchIcon colorClass="text-white" />

                    <p
                        class="text-white text-base font-semibold leading-normal"
                    >
                        {{ $t("Search") }}
                    </p>
                </button>
            </form>

            <form
                @submit.prevent="goToSearch"
                class="w-full h-12 bg-white rounded-lg flex lg:hidden justify-between items-center overflow-hidden bg-neutral-100 rounded-lg outline outline-1 outline-offset-[-1px] outline-gray-100 p-2"
            >
                <button
                    type="submit"
                    class="h-full flex justify-start items-center"
                >
                    <SearchIcon colorClass="text-slate-400" />
                </button>
                <input
                    type="search"
                    name="search"
                    class="form-control grow h-full inline-block p-6 pl-2 placeholder:text-gray-400 text-zinc-900 text-base font-normal font-['Lato'] leading-normal outline-none"
                    :placeholder="$t('Search for products') + '...'"
                />
                <!-- <button
                    type="submit"
                    class="h-full px-6 py-3 bg-primary-600 flex justify-center items-center gap-3"
                >
                    <SearchIcon />

                    <p
                        class="text-white text-base font-semibold leading-normal"
                    >
                        Search
                    </p>
                </button> -->
            </form>
        </div>
    </div>

    <!-- mobile category menu  -->
    <MobileMenuCategory
        :mobileMenuCategoryOpen="showCategoryMobileMenu"
        :categories="allCategories"
        @close="showCategoryMobileMenu = false"
    />
</template>

<script setup>
import { RiHomeLine, RiSearchLine } from "vue-remix-icons";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import {
    DevicePhoneMobileIcon,
    ChevronDownIcon,
} from "@heroicons/vue/24/outline";
import MenuCategory from "./MenuCategory.vue";
import MenuIcon from "../icons/Menu.vue";

import { useMaster } from "../stores/MasterStore";
import MenuPages from "./MenuPages.vue";
import NavbarCategoryDropdown from "./NavbarCategoryDropdown.vue";
import { useRouter } from "vue-router";
import GridIcon from "../icons/GridIcon.vue";
import { onMounted, ref } from "vue";
import CategoryMenu from "./CategoryMenu.vue";
import CategoryMenuV2 from "./CategoryMenuV2.vue";
import SearchIcon from "../icons/SearchIcon.vue";
import MobileMenuCategory from "./MobileMenuCategory.vue";

// variable
const showDropdown = ref(false);
const allCategories = ref();
const showCategoryMobileMenu = ref(false);

const master = useMaster();
const router = useRouter();

const appStore = () => {
    if (master.appStoreLink) {
        window.open(master.appStoreLink, "_blank");
    }
};

const playStore = () => {
    if (master.playStoreLink) {
        window.open(master.playStoreLink, "_blank");
    }
};

const goToSearch = (e) => {
    e.preventDefault(); // Prevent default just in case
    const formData = new FormData(e.target);
    const searchValue = formData.get("search");
    console.log(searchValue); // The typed search text
    master.search = searchValue;
    router.push({ name: "products" });
};

const hiddenPopover = () => {
    open = false;
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
.router-link-active {
    @apply border-b-2 border-primary text-primary;
}
</style>
