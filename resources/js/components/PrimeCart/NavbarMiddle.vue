<template>
    <section
        class="container-2 px-4 2xl:px-0 xl:py-4 flex justify-between items-center"
    >
        <RouterLink class="w-40 h-14 flex items-center" to="/">
            <img
                v-if="master.logo"
                :src="master.logo"
                alt=""
                class="h-full w-full object-contain"
            />
            <img
                v-else
                src="../../../../public/assets/logo.png"
                class="h-full w-full object-contain"
            />
        </RouterLink>

        <!-- profile section  -->
        <div class="hidden xl:flex justify-end items-center gap-1">
            <!-- language toggle  -->
            <Menu
                as="div"
                class="relative inline-block text-left bg-neutral-100 px-2 py-1 rounded"
            >
                <div>
                    <MenuButton
                        class="flex justify-between items-center text-white gap-2 text-xs md:text-sm font-normal leading-tight"
                    >
                        <img
                            :src="currentLanguage.flag"
                            alt="flag"
                            class="w-5 h-4"
                        />
                        <div
                            class="justify-center text-zinc-900 text-sm font-medium font-['Montserrat'] leading-snug uppercase"
                        >
                            {{ currentLanguage.name }}
                        </div>

                        <ChevronDownIcon
                            class="w-5 h-5 text-zinc-900"
                            aria-hidden="true"
                        />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <MenuItems
                        class="absolute z-20 w-24 mt-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        :class="
                            master.langDirection == 'rtl' ? 'left-0' : 'right-0'
                        "
                    >
                        <div class="py-1">
                            <MenuItem
                                v-for="language in master.languages"
                                v-slot="{ active }"
                                :key="language.id"
                            >
                                <button
                                    type="button"
                                    @click="
                                        setCurrentLanguage(language.name);
                                        reloadPage();
                                    "
                                    class="w-full text-left"
                                    :class="[
                                        active
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700',
                                        'flex justify-between items-center px-4 py-2 text-xs',
                                    ]"
                                >
                                    <img
                                        :src="language.flag"
                                        alt="flag"
                                        class="w-5"
                                    />
                                    {{ language.title }}
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

            <!-- currency toggle  -->
            <Menu
                as="div"
                class="relative inline-block text-left bg-neutral-100 px-2 py-1 rounded"
            >
                <div>
                    <MenuButton
                        class="flex justify-between items-center text-white gap-2 text-xs md:text-sm font-normal leading-tight"
                    >
                        <div
                            class="justify-center text-zinc-900 text-sm font-medium font-['Montserrat'] leading-snug uppercase"
                        >
                            {{
                                (master.selectedCurrency?.name || "USD") +
                                ", " +
                                (master.selectedCurrency?.symbol || "$")
                            }}
                        </div>

                        <ChevronDownIcon
                            class="w-5 h-5 text-zinc-900"
                            aria-hidden="true"
                        />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <MenuItems
                        class="absolute z-20 w-24 mt-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        :class="
                            master.langDirection == 'rtl' ? 'left-0' : 'right-0'
                        "
                    >
                        <div class="px-[13px] py-[5px]">
                            <MenuItem
                                v-for="currency in master.currencies"
                                v-slot="{ active }"
                                :key="currency.id"
                            >
                                <button
                                    type="button"
                                    @click="setCurrentCurrency(currency)"
                                    class="w-full text-left"
                                    :class="[
                                        active
                                            ? ' text-primary'
                                            : 'text-gray-700',
                                        'block py-[7.5px] text-xs',
                                    ]"
                                >
                                    {{ currency.name + ", " + currency.symbol }}
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

            <div
                v-if="AuthStore.user"
                class="flex items-center justify-between gap-1"
            >
                <button
                    @click="showWishlist()"
                    class="px-2 py-1 bg-neutral-100 rounded inline-flex justify-center items-center gap-1"
                >
                    <div class="flex justify-center items-center gap-0.5">
                        <WishlistIconOutline
                            colorClass="fill-zinc-900"
                            width="18"
                            height="18"
                        />
                        <div
                            class="justify-start text-zinc-900 text-sm font-medium font-['Montserrat'] leading-snug"
                        >
                            {{ $t("Wishlist") }}
                        </div>
                        <div
                            class="w-4 h-4 bg-primary-500 rounded-2xl outline outline-1 outline-white flex justify-center items-center gap-1.5 text-white text-[8.53px] font-bold font-['Roboto'] leading-3"
                        >
                            {{ AuthStore.favoriteProducts }}
                        </div>
                    </div>
                </button>

                <button
                    @click="master.basketCanvas = true"
                    class="px-2 py-1 bg-neutral-100 rounded inline-flex justify-center items-center gap-1"
                >
                    <div class="flex justify-center items-center gap-0.5">
                        <Cart2
                            colorClass="fill-zinc-900"
                            width="24"
                            height="18"
                        />
                        <div
                            class="justify-start text-zinc-900 text-sm font-medium font-['Montserrat'] leading-snug"
                        >
                            My Cart
                        </div>
                        <div
                            class="w-4 h-4 bg-primary-500 rounded-2xl outline outline-1 outline-white flex justify-center items-center gap-1.5 text-white text-[8.53px] font-bold font-['Roboto'] leading-3"
                        >
                            {{ basketStore.total }}
                        </div>
                    </div>
                </button>
            </div>

            <button
                v-if="!AuthStore.user"
                @click="showLoginDialog"
                class="max-w-36 self-stretch px-6 py-2 rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-600 text-base font-semibold text-primary-600 leading-normal transition-all duration-200 hover:bg-primary-600 hover:text-white"
            >
                Login
            </button>

            <div v-else class="login__btn flex w-12 h-12">
                <RouterLink to="/dashboard" class="avatar">
                    <img :src="AuthStore.user?.profile_photo" alt="profile" />
                </RouterLink>
            </div>
        </div>

        <button
            @click="mobileMenuOpen = true"
            class="w-8 h-8 bg-neutral-100 rounded flex xl:hidden justify-center items-center gap-2"
        >
            <Bars3Icon class="w-6 h-6 text-slate-950" />
        </button>
    </section>

    <!-- Login Dialog Modal -->
    <LoginModal />
    <!-- End Login Dialog Modal -->

    <!-- Mobile Menu Canvas Drawer -->
    <TransitionRoot as="template" :show="mobileMenuOpen">
        <Dialog as="div" class="relative z-50" @close="mobileMenuOpen = false">
            <TransitionChild
                as="template"
                enter="ease-in-out duration-500"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in-out duration-500"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="pointer-events-none fixed inset-y-0 flex max-w-72"
                        :class="
                            master.langDirection == 'rtl'
                                ? 'left-0 '
                                : 'right-0 '
                        "
                    >
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            :enter-from="
                                master.langDirection == 'rtl'
                                    ? '-translate-x-[200%]'
                                    : 'translate-x-[200%]'
                            "
                            enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0"
                            :leave-to="
                                master.langDirection == 'rtl'
                                    ? '-translate-x-[200%]'
                                    : 'translate-x-[200%]'
                            "
                        >
                            <DialogPanel
                                class="pointer-events-auto relative w-screen max-w-md"
                            >
                                <TransitionChild
                                    as="template"
                                    enter="ease-in-out duration-500"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="ease-in-out duration-500"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <div
                                        class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"
                                    ></div>
                                </TransitionChild>
                                <div
                                    class="h-full bg-white shadow-xl py-4 px-3"
                                >
                                    <div
                                        class="flex justify-between items-cente pb-4"
                                    >
                                        <div
                                            class="text-black text-lg font-bold leading-normal tracking-tight font-['Roboto']"
                                        >
                                            {{ $t("Menu") }}
                                        </div>

                                        <button
                                            class="w-7 h-7 flex justify-center items-center bg-slate-100 rounded-full"
                                            @click="mobileMenuOpen = false"
                                        >
                                            <XMarkIcon
                                                class="w-5 h-5 text-slate-700"
                                            />
                                        </button>
                                    </div>

                                    <div
                                        class="w-full flex flex-col overflow-y-scroll max-h-screen scrollbar-hide"
                                    >
                                        <div
                                            class="p-2 bg-slate-50 rounded-lg border border-slate-100 flex flex-col gap-1 sticky top-0 z-10"
                                        >
                                            <div
                                                v-if="!AuthStore.user"
                                                class="flex justify-between items-center px-3 py-[7px] bg-white rounded-md border border-slate-100 gap-2"
                                                @click="showLoginDialog"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <UserIcon
                                                        class="w-6 h-6 text-primary-600"
                                                    />
                                                    <div
                                                        class="text-black text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("Login") }}
                                                    </div>
                                                </div>
                                                <ChevronRightIcon
                                                    class="w-6 h-6 text-slate-600"
                                                />
                                            </div>
                                            <div v-else class="w-full">
                                                <AuthUserDropdownSmall
                                                    @close="
                                                        mobileMenuOpen = false
                                                    "
                                                />
                                            </div>

                                            <div
                                                class="flex justify-between items-center px-3 py-[10px] bg-white rounded-md border border-slate-100 gap-2"
                                                @click="showWishlist()"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <WishlistIcon
                                                        colorClass="fill-primary-500"
                                                        width="24"
                                                        height="24"
                                                    />
                                                    <div
                                                        class="text-black text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("Wishlist") }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 bg-primary-500 rounded-full border border-white flex justify-center items-center text-white"
                                                >
                                                    <span
                                                        class="text-white text-[8px] font-bold"
                                                    >
                                                        {{
                                                            AuthStore.favoriteProducts
                                                        }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div
                                                class="flex justify-between items-center px-3 py-[10px] bg-white rounded-md border border-slate-100 gap-2"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <BasketIcon
                                                        colorClass="fill-primary-500"
                                                        width="24"
                                                        height="20"
                                                    />
                                                    <div
                                                        @click="showCardCanvas"
                                                        class="text-black text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("My Cart") }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 bg-primary-500 rounded-full border border-white flex justify-center items-center text-white"
                                                >
                                                    <span
                                                        class="text-white text-[8px] font-bold"
                                                    >
                                                        {{ basketStore.total }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- menues  -->
                                        <div
                                            class="justify-start inline-flex grow flex-col mt-5 gap-[15px]"
                                        >
                                            <div
                                                v-for="menu in master.menus"
                                                :key="menu.id"
                                                class="w-full text-base leading-normal"
                                            >
                                                <template
                                                    v-if="!menu.is_external"
                                                >
                                                    <router-link
                                                        v-if="
                                                            menu.name !=
                                                            'categories'
                                                        "
                                                        :to="menu.url"
                                                        class="text-black block font-medium"
                                                    >
                                                        {{ $t(menu.name) }}
                                                    </router-link>
                                                </template>
                                                <a
                                                    v-else
                                                    :href="menu.url"
                                                    :target="menu.target"
                                                    class="block text-primary-500 font-medium"
                                                >
                                                    {{ $t(menu.name) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- search modal  -->
    <SearchModal
        :showSearch="showSearch"
        @closeModal="(bool) => (showSearch = bool)"
    />
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { RiHomeLine, RiMapPinLine } from "vue-remix-icons";

import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3Icon,
    ChevronRightIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { MagnifyingGlassIcon, UserIcon } from "@heroicons/vue/24/solid";
import { ref, watch, onMounted } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import LoginModal from "../LoginModal.vue";
import localization from "../../localization";

import { useAuth } from "../../stores/AuthStore";
import { useBasketStore } from "../../stores/BasketStore";
import { useMaster } from "../../stores/MasterStore";
import SearchModal from "../SearchModal.vue";
import axios from "axios";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import BasketIcon from "../../icons/BasketIcon.vue";
import WishlistIcon from "../../icons/WishlistIcon.vue";
import BellIcon from "../../icons/BellIcon.vue";
import AuthUserDropdownSmall from "../AuthUserDropdownSmall.vue";
import WishlistIconOutline from "../../icons/WishlistIconOutline.vue";
import Cart2 from "../../icons/Cart2.vue";

// apis
const categoryApi = "/categories";

// stores
const route = useRoute();
const router = useRouter();
const basketStore = useBasketStore();

const AuthStore = useAuth();
const master = useMaster();

const search = ref("");
const showSearch = ref(false);

const currentLanguage = ref("English");

const routerName = ref(route.name);

watch(route, () => {
    routerName.value = route.name;
});

const setCurrentLanguage = (lang) => {
    master.locale = lang;
    localStorage.setItem("locale", lang);

    const language = master.languages.find(
        (lang) => lang.name === master.locale
    );
    if (language) {
        currentLanguage.value = language;
        master.langDirection = language.direction || "ltr";
    }
    localization.fetchLocalizationData();
};

const setCurrentCurrency = (currency) => {
    master.selectedCurrency = currency;
};

const reloadPage = () => {
    window.location.reload();
};

const showCardCanvas = () => {
    mobileMenuOpen.value = false;
    if (!master.basketCanvas) {
        basketStore.fetchCheckoutProducts();
    }
    master.basketCanvas = !master.basketCanvas;
};

const toggleSearch = () => {
    showSearch.value = !showSearch.value;
};

const showMyCart = () => {
    mobileMenuOpen.value = false;
    master.basketCanvas = true;
};

const showWishlist = () => {
    mobileMenuOpen.value = false;
    if (!AuthStore.token) {
        return showLoginDialog();
    }
    router.push("/wishlist");
};

watch(
    () => route.path,
    () => {
        mobileMenuOpen.value = false;
        if (route.path == "/products") {
            search.value = master.search;
        } else {
            search.value = "";
        }
    }
);

onMounted(() => {
    if (route.path == "/products") {
        search.value = master.search;
    } else {
        search.value = "";
    }
});

onMounted(() => {
    setCurrentLanguage(master.locale);
});

const mobileMenuOpen = ref(false);

const showLoginDialog = () => {
    mobileMenuOpen.value = false;
    AuthStore.showLoginModal();
};

const searchProducts = () => {
    master.search = search.value;
    if (route.path != "/products") {
        search.value = "";
    }
    router.push({ name: "products" });
};

const allCategories = ref();
const showCategoryDropdown = ref(false);

const getCategories = async () => {
    allCategories.value = master.categories;
};

onMounted(() => {
    getCategories();
});
</script>

<style scoped>
.router-link-active {
    @apply border-primary text-primary;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.category-menu-enter-active,
.category-menu-leave-active {
    transition: opacity 0.3s ease, max-height 0.3s ease, transform 0.3s ease;
    overflow: hidden;
}

.category-menu-enter-from,
.category-menu-leave-to {
    opacity: 0;
    max-height: 0;
    transform: translateY(10px);
}

.category-menu-enter-to,
.category-menu-leave-from {
    opacity: 1;
    max-height: 500px; /* Adjust this value based on your menu's maximum expected height */
    transform: translateY(0);
}
</style>
