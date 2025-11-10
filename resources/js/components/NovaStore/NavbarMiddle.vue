<template>
    <div class="main-container py-3 flex justify-start items-center gap-8">
        <RouterLink class="" to="/">
            <img v-if="master.logo" :src="master.logo" alt="" class="h-[40px] w-full object-contain" />
            <img v-else src="../../../../public/assets/logo.png" class="h-[40px] w-full object-contain" />
        </RouterLink>
        <div class="text-slate-500 flex items-center gap-1 font-normal text-sm leading-[22px]">
            <div class="icon">
                <img
                    src="../../../../public/assets/images/location-pin.svg"
                    alt=""
                    class="w-6 h-6"
                />
            </div>
               <span class="hidden md:block">{{ truncate(master?.address, 60)}}</span>
               <span class="block md:hidden" >{{ truncate(master?.address, 10) }}</span>
        </div>

        <div
            class="hidden lg:flex justify-between items-center gap-4"
            :class="master.langDirection == 'rtl' ? 'mr-auto' : 'ml-auto'"
        >
            <ul v-if="AuthStore.user" class="header__meta">

                <li>
                    <RouterLink to="/wishlist">
                        <div class="icon">
                            <img
                                src="../../../../public/assets/images/heart.svg"
                                alt="icon"
                            />
                        </div>

                        {{ $t("Wishlist") }}
                        <span class="count">{{
                            AuthStore.favoriteProducts
                        }}</span>
                    </RouterLink>
                </li>

                <li>
                    <a
                        v-if="
                            routerName !== 'checkout' &&
                            routerName !== 'blogs' &&
                            routerName !== 'blog-details'
                        "
                        @click="showCardCanvas"
                        class="cursor-pointer"
                    >
                        <div class="icon">
                            <img
                                src="../../../../public/assets/images/cart-bag.svg"
                                alt="icon"
                            />
                        </div>
                        {{ $t("My Cart") }}

                        <span class="count">{{ basketStore.total }}</span>
                    </a>
                </li>
            </ul>
            <div v-if="AuthStore.user" class="login__btn flex">
                <RouterLink to="/dashboard" class="avatar">
                    <img :src="AuthStore.user?.profile_photo" alt="profile" />
                </RouterLink>
            </div>

            <button
                v-else
                class="flex items-center gap-2 lg:p-2.5 text-slate-600 hover:text-primary"
                @click="showLoginDialog"
            >
                <span class="text-base font-normal leading-normal">{{
                    $t("Login")
                }}</span>
                <UserIcon class="w-5 h-5" />
            </button>
        </div>

        <div
            class="flex lg:hidden justify-between items-center gap-4"
            :class="master.langDirection == 'rtl' ? 'mr-auto' : 'ml-auto'"
        >
            <button
                type="button"
                class="header__search"
                @click="showSearch = true"
            >
                <img
                    src="../../../../public/assets/images/search-icon.svg"
                    alt=""
                />
            </button>

            <button
                type="button"
                class="flex justify-center items-center h-8 w-8 bg-neutral-100"
                @click="mobileMenuOpen = true"
            >
                <Bars3Icon class="w-6 h-6 text-slate-950" />
            </button>
        </div>
    </div>

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
                                <div class="h-full bg-white shadow-xl p-4">
                                    <div
                                        class="flex justify-between items-cente pb-4"
                                    >
                                        <div
                                            class="text-slate-950 text-lg font-bold leading-normal tracking-tight font-['Roboto']"
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
                                                        class="w-5 h-5 text-slate-950"
                                                    />
                                                    <div
                                                        class="text-slate-950 text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("Login") }}
                                                    </div>
                                                </div>
                                                <ChevronRightIcon
                                                    class="w-6 h-6 text-slate-600"
                                                />
                                            </div>
                                            <div v-else class="w-full">
                                                <AuthUserDropdown />
                                            </div>

                                            <div
                                                class="flex justify-between items-center px-3 py-[10px] bg-white rounded-md border border-slate-100 gap-2"
                                                @click="showWishlist()"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <img
                                                        src="../../../../public/assets/images/heart.svg"
                                                        class="w-6 h-6 text-slate-950"
                                                    />
                                                    <div
                                                        class="text-slate-950 text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("Wishlist") }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 bg-primary rounded-full border border-white flex justify-center items-center text-white"
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
                                                
                                                @click="showMyCart()"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <img
                                                        src="../../../../public/assets/images/cart-bag.svg"
                                                        class="w-6 h-6 text-slate-950"
                                                    />
                                                    <div
                                                        @click="showCardCanvas"
                                                        class="text-slate-950 text-sm font-normal leading-tight"
                                                    >
                                                        {{ $t("My Cart") }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 bg-primary rounded-full border border-white flex justify-center items-center text-white"
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
                                                        class="text-slate-950 block font-medium"
                                                    >
                                                        {{ $t(menu.name) }}
                                                    </router-link>

                                                    <div
                                                        v-else
                                                        class="space-y-2"
                                                    >
                                                        <div
                                                            class="text-slate-950 flex justify-between items-center font-medium capitalize cursor-pointer"
                                                            @click="
                                                                showCategoryDropdown =
                                                                    !showCategoryDropdown
                                                            "
                                                        >
                                                            <p>{{ $t(menu.name) }}</p>

                                                            <ChevronDownIcon
                                                                class="w-4 h-4 text-slate-500 transition-all duration-300"
                                                                :class="showCategoryDropdown ? 'rotate-180' : ''"
                                                            />
                                                        </div>
                                                        <Transition
                                                            name="category-menu"
                                                        >
                                                            <ul
                                                                v-if="
                                                                    showCategoryDropdown
                                                                "
                                                                class="space-y-[15px] overflow-hidden ml-3"
                                                            >
                                                                <RouterLink
                                                                    :to="`/categories/${category.id}`"
                                                                    v-for="category in allCategories"
                                                                    class="cursor-pointer text-base block"
                                                                >
                                                                    {{
                                                                        category.name
                                                                    }}
                                                                </RouterLink>
                                                            </ul>
                                                        </Transition>
                                                    </div>
                                                </template>
                                                <a
                                                    v-else
                                                    :href="menu.url"
                                                    :target="menu.target"
                                                    class="block text-slate-950 font-medium"
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
    UserIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/solid";
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
import { useTruncateText } from "../../composables/useTruncateText";

// apis
const categoryApi = "/categories";

// stores
const route = useRoute();
const router = useRouter();
const basketStore = useBasketStore();

const AuthStore = useAuth();
const master = useMaster();
const { truncate } = useTruncateText();

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
