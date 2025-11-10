<template>
    <div class="lg:bg-neutral-100">
        <div
            class="container-2 flex items-center justify-between md:gap-3 lg:gap-10 flex-nowrap relative py-2 px-4 2xl:px-0 lg:py-4 border-t border-b border-slate-100 lg:border-none"
        >
            <!--==== Categories dropdown menu ====-->
            <button
                @click="showCategoryMobileMenu = true"
                class="block lg:hidden p-3 bg-primary-50 rounded-lg"
            >
                <GridIcon width="24" height="24" class="fill-primary" />
            </button>

            <div class="flex items-center gap-10">
                <div
                    class="hidden lg:block min-w-72 relative"
                    @mouseenter="showDropdown = true"
                    @mouseleave="showDropdown = false"
                >
                    <button
                        class="w-full px-3 flex justify-between items-center gap-2 rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500 bg-white h-12"
                    >
                        <GridIcon
                            width="24"
                            height="24"
                            class="fill-zinc-900"
                        />

                        <p
                            class="w-full text-zinc-900 text-base font-medium font-['Montserrat'] leading-normal text-start text-nowrap"
                        >
                            {{ $t("All Categories") }}
                        </p>

                        <ChevronDownIcon class="text-zinc-900 w-5 h-5" />
                    </button>

                    <!-- category dropdown  -->
                    <!-- <Transition name="slide-fade">
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
                    </Transition> -->
                </div>

                <!-- menu pages  -->
                <div class="hidden xl:block">
                    <MenuPagesV2 />
                </div>
            </div>

            <!-- divider  -->
            <div
                class="block lg:hidden w-0 self-stretch origin-top-left rotate-0 outline outline-1 outline-offset-[-0.50px] outline-neutral-100 mx-2"
            ></div>

            <!-- Header Search -->
            <button
                @click="showSearch = true"
                class="h-full p-2 border border-primary bg-white rounded-lg justify-center items-center gap-3 hidden xl:flex 3xl:hidden"
            >
                <SearchIcon colorClass="text-gray-600" />
            </button>

            <Transition name="appear-fade">
                <section
                    v-if="showSearch"
                    class="absolute top-0 right-0 w-full h-full rounded-lg outer"
                >
                    <div
                        class="relative w-full h-full flex justify-end items-center px-4"
                    >
                        <div
                            class="absolute top-0 left-0 w-full h-full bg-gradient-to-l from-primary-100 z-0"
                            @click="showSearch = false"
                        ></div>

                        <form
                            @submit.prevent="goToSearch"
                            class="w-full xl:max-w-96 h-12 outline outline-1 outline-offset-[-1px] outline-gray-100 bg-white rounded-lg flex justify-between items-center overflow-hidden relative z-10"
                        >
                            <button
                                type="submit"
                                class="h-full pl-4 pr-2 flex justify-center items-center gap-3"
                            >
                                <SearchIcon colorClass="text-gray-600" />
                            </button>

                            <input
                                type="search"
                                name="search"
                                class="form-control grow h-full inline-block pr-2 placeholder:text-slate-500 text-zinc-900 text-base font-normal font-['Montserrat'] leading-normal outline-none"
                                :placeholder="$t('Search for products') + '...'"
                            />
                        </form>
                    </div>
                </section>
            </Transition>

            <form
                @submit.prevent="goToSearch"
                class="w-full xl:max-w-96 h-12 outline outline-1 outline-offset-[-1px] outline-gray-100 bg-white rounded-lg flex xl:hidden 3xl:flex justify-between items-center overflow-hidden"
            >
                <button
                    type="submit"
                    class="h-full pl-4 pr-2 flex justify-center items-center gap-3"
                >
                    <SearchIcon colorClass="text-gray-600" />
                </button>

                <input
                    type="search"
                    name="search"
                    class="form-control grow h-full inline-block pr-2 placeholder:text-slate-500 text-zinc-900 text-base font-normal font-['Montserrat'] leading-normal outline-none"
                    :placeholder="$t('Search for products') + '...'"
                />
            </form>
        </div>

        <Transition name="slide-fade">
            <div v-if="showDropdown" class="h-0 relative w-full">
                <div
                    class="container-2 relative max-h-[80vh] overflow-y-scroll overflow-x-visible scrollbar-hide"
                >
                    <ul class="w-80 bg-slate-100 p-2">
                        <CategoryMenuV2
                            @mouseenter="showDropdown = true"
                            @mouseleave="showDropdown = false"
                            :items="allCategories"
                            :showImage="true"
                            @close="showDropdown = false"
                        />
                    </ul>
                </div>
            </div>
        </Transition>
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
import MenuCategory from "../MenuCategory.vue";
import MenuIcon from "../../icons/Menu.vue";

import { useMaster } from "../../stores/MasterStore";
import { useRouter } from "vue-router";
import GridIcon from "../../icons/GridIcon.vue";
import { onMounted, ref } from "vue";
import CategoryMenuV2 from "../CategoryMenuV2.vue";
import SearchIcon from "../../icons/SearchIcon.vue";
import MobileMenuCategory from "../MobileMenuCategory.vue";
import MenuPagesV2 from "../MenuPagesV2.vue";

// variable
const showDropdown = ref(false);
const allCategories = ref();
const showCategoryMobileMenu = ref(false);
const showSearch = ref(false);

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
