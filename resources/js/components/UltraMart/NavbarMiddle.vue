<template>
    <section
        class="container-2 px-4 py-4 xl:px-0 xl:py-6 flex justify-between items-center"
    >
        <RouterLink class="w-48 h-14 flex items-center" to="/">
            <img v-if="master.logo" :src="master.logo" alt="" class="object-contain" />
            <img v-else src="../../../../public/assets/logo.png" class="object-contain"/>
        </RouterLink>

        <!-- menu pages  -->
        <div class="hidden xl:block">
            <MenuPages />
        </div>

        <!-- profile section  -->
        <div class="hidden xl:flex justify-end items-center gap-4">
            <div
                v-if="AuthStore.user"
                class="flex items-center justify-between gap-4"
            >
                <IconBtn
                    @click="showWishlist()"
                    :count="AuthStore.favoriteProducts"
                >
                    <template #icon>
                        <WishlistIcon
                            colorClass="fill-primary"
                            width="24"
                            height="24"
                        />
                    </template>
                </IconBtn>
                <IconBtn
                    @click="master.basketCanvas = true"
                    :count="basketStore.total"
                >
                    <template #icon>
                        <BasketIcon
                            colorClass="fill-primary"
                            width="18"
                            height="18"
                        />
                    </template>
                </IconBtn>
            </div>

            <button
                v-if="!AuthStore.user"
                @click="showLoginDialog"
                class="max-w-36 self-stretch px-6 py-3 rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-600 text-base font-semibold text-primary-600 leading-normal transition-all duration-200 hover:bg-primary-600 hover:text-white"
            >
                Login
            </button>

            <div v-else class="login__btn flex">
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
                                                @click="showMyCart()"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <BellIcon
                                                        colorClass="text-primary-500"
                                                        width="24"
                                                        height="24"
                                                    />
                                                    <div
                                                        class="text-black text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("Notification") }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 bg-primary-500 rounded-full border border-white flex justify-center items-center text-white"
                                                >
                                                    <span
                                                        class="text-white text-[8px] font-bold"
                                                    >
                                                        0
                                                    </span>
                                                </div>
                                            </div>

                                            <div
                                                class="flex justify-between items-center px-3 py-[10px] bg-white rounded-md border border-slate-100 gap-2"
                                                @click="showWishlist()"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <WishlistIcon
                                                        colorClass="text-primary-500"
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
                                                        colorClass="text-primary-500"
                                                        width="24"
                                                        height="24"
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
import AuthUserDropdown from "../AuthUserDropdown.vue";
import LoginModal from "../LoginModal.vue";

import { useAuth } from "../../stores/AuthStore";
import { useBasketStore } from "../../stores/BasketStore";
import { useMaster } from "../../stores/MasterStore";
import SearchModal from "../SearchModal.vue";
import axios from "axios";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import MenuPages from "../UltraMart/MenuPages.vue";
import BasketIcon from "../../icons/BasketIcon.vue";
import IconBtn from "../IconBtn.vue";
import WishlistIcon from "../../icons/WishlistIcon.vue";
import BellIcon from "../../icons/BellIcon.vue";
import AuthUserDropdownSmall from "../AuthUserDropdownSmall.vue";

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

const routerName = ref(route.name);

watch(route, () => {
    routerName.value = route.name;
});

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
