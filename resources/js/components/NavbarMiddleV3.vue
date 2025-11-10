<template>
    <div
        class="container-2 py-4 lg:py-6 px-4 lg:px-0 flex justify-between items-center gap-8"
    >
        <RouterLink class="w-40 h-auto" to="/">
            <img
                v-if="master.logo"
                :src="master.logo"
                alt=""
                class="h-full w-full"
            />
            <img v-else src="../../../public/assets/logo.png" />
        </RouterLink>

        <div class="hidden lg:block">
            <MenuPages />
        </div>

        <div class="flex justify-between items-center gap-4">
            <button
                v-if="!AuthStore.user"
                @click="showLoginDialog"
                class="px-4 py-2 md:px-6 md:py-3 bg-primary text-white text-sm md:text-base rounded-lg duration-150 hover:bg-primary-700"
            >
                {{ $t("Login") }}
            </button>

            <ul v-if="AuthStore.user" class="hidden lg:flex items-center gap-4">
                <RouterLink to="/wishlist" class="relative">
                    <WishlistIconOutline
                        colorClass="fill-black"
                        height="24"
                        width="30"
                    />

                    <div
                        class="absolute -top-2.5 -right-2 w-3.5 h-3.5 bg-primary-500 rounded-2xl outline outline-1 outline-white inline-flex flex-col justify-center items-center gap-1.5 text-white text-[8.53px] font-bold leading-3"
                    >
                        {{ AuthStore.favoriteProducts }}
                    </div>
                </RouterLink>
                <li
                    v-if="
                        routerName !== 'checkout' &&
                        routerName !== 'blogs' &&
                        routerName !== 'blog-details'
                    "
                    @click="showCardCanvas"
                    class="cursor-pointer relative"
                >
                    <Cart2 colorClass="fill-black" height="24" width="30" />

                    <div
                        class="absolute -top-2.5 -right-2 w-3.5 h-3.5 bg-primary-500 rounded-2xl outline outline-1 outline-white inline-flex flex-col justify-center items-center gap-1.5 text-white text-[8.53px] font-bold leading-3"
                    >
                        {{ basketStore.total }}
                    </div>
                </li>
            </ul>

            <div
                v-if="AuthStore.user"
                class="w-14 h-14 hidden lg:flex justify-center rounded-[48px] overflow-hidden border-2 border-white"
            >
                <RouterLink to="/dashboard" class="h-full w-full">
                    <img
                        :src="AuthStore.user?.profile_photo"
                        alt="profile"
                        class="w-full h-full object-cover"
                    />
                </RouterLink>
            </div>

            <button
                type="button"
                class="flex lg:hidden justify-center items-center h-8 w-8 border-2 border-primary-300 ml-auto"
                @click="mobileMenuOpen = true"
            >
                <Bars3Icon class="w-6 h-6 text-primary-500" />
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
                                            class="text-primary-600 text-lg font-bold leading-normal tracking-tight"
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
                                                        class="w-5 h-5 text-primary-600"
                                                    />
                                                    <div
                                                        class="text-primary-600 text-sm font-normal leading-tight"
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
                                                    <WishlistIconOutline
                                                        colorClass="fill-primary-600"
                                                        height="24"
                                                        width="30"
                                                    />
                                                    <div
                                                        class="text-primary-600 text-sm font-normal leading-tight"
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
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <Cart2
                                                        colorClass="fill-primary-600"
                                                        height="24"
                                                        width="30"
                                                    />
                                                    <div
                                                        @click="showCardCanvas"
                                                        class="text-primary-600 text-sm font-normal leading-tight"
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
                                                        class="text-primary-600 block font-medium"
                                                    >
                                                        {{ $t(menu.name) }}
                                                    </router-link>

                                                    <div
                                                        v-else
                                                        class="space-y-2"
                                                    >
                                                        <div
                                                            class="text-primary-600 flex justify-between items-center font-medium capitalize cursor-pointer"
                                                            @click="
                                                                showCategoryDropdown =
                                                                    !showCategoryDropdown
                                                            "
                                                        >
                                                            <p>
                                                                {{
                                                                    $t(
                                                                        menu.name
                                                                    )
                                                                }}
                                                            </p>

                                                            <ChevronDownIcon
                                                                class="w-4 h-4 text-slate-500 transition-all duration-300"
                                                                :class="
                                                                    showCategoryDropdown
                                                                        ? 'rotate-180'
                                                                        : ''
                                                                "
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
                                                    class="block text-primary-600 font-medium"
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
import { RiHomeLine, RiMapPinLine, RiSearchLine } from "vue-remix-icons";

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
import AuthUserDropdown from "./AuthUserDropdown.vue";
import LoginModal from "./LoginModal.vue";

import { useAuth } from "../stores/AuthStore";
import { useBasketStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";
import SearchModal from "./SearchModal.vue";
import axios from "axios";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import MenuPagesV4 from "./MenuPagesV4.vue";
import Cart from "../icons/Cart.vue";
import Cart2 from "../icons/Cart2.vue";
import WishlistIconOutline from "../icons/WishlistIconOutline.vue";
import SearchIcon from "../icons/SearchIcon.vue";
import MenuPages from "./MenuPages.vue";

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

const goToSearch = (e) => {
    e.preventDefault(); // Prevent default just in case
    const formData = new FormData(e.target);
    const searchValue = formData.get("search");
    console.log(searchValue); // The typed search text
    master.search = searchValue;
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
