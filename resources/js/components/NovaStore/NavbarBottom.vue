<template>
    <div class="bg-neutral-100 hidden lg:block relative">
        <div
            class="main-container flex items-center justify-between md:gap-3 lg:gap-4 flex-wrap md:flex-nowrap relative py-[16px]"
        >
            <!--==== Categories dropdown menu ====-->
            <MenuPages />

            <!-- Header Search -->
            <form
                @submit.prevent="goToSearch"
                class="w-[500px] h-12 bg-white rounded-lg hidden md:flex justify-between items-center overflow-hidden"
            >
                <input
                    type="search"
                    name="search"
                    class="form-control grow h-full inline-block p-6 placeholder:text-gray-400 text-zinc-900 text-base font-normal font-['Lato'] leading-normal outline-none"
                    :placeholder="$t('Search for products') + '...'"
                />
                <button type="submit" class="h-full px-4 py-3 bg-primary">
                    <RiSearchLine class="w-6 h-6 text-white" />
                </button>
            </form>

            <!-- <ul class="absolute top-10 left-0 h-auto bg-white w-full overflow-y-auto max-h-[50vh]">
                <CategoryMenu
                    :items="master.categories"
                    :showImage="true"
                    @close="showDropdown = false"
                />
            </ul> -->
        </div>


        
    </div>
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
import MenuPages from "../MenuPages.vue";
import NavbarCategoryDropdown from "../NavbarCategoryDropdown.vue";
import { useRouter } from "vue-router";
import CategoryMenu from "../CategoryMenu.vue";

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
</script>

<style scoped>
.router-link-active {
    @apply border-b-2 border-primary text-primary;
}
</style>
