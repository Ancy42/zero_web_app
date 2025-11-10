<template>
    <Menu as="div" v-slot="{ open }">
        <div
            class="relative inline-block text-left w-full px-3 py-[10px] transition-all duration-100 bg-white"
            :class="open ? 'shadow-md shadow-green-200/50' : ''"
        >
            <MenuButton
                class="flex w-full items-center gap-1 rounded-lg text-primary-600 h-full"
            >
                <div class="relative">
                    <img
                        :src="authStore.user?.profile_photo"
                        alt=""
                        class="w-6 h-6 object-cover rounded-full border border-primary"
                    />
                    <!-- <div class="w-4 h-4 absolute -bottom-2 right-2/4 translate-x-2/4 rounded-2xl"
                        :class="open ? 'bg-primary-100' : 'bg-white'">
                        <ChevronDownIcon class="w-4 h-4" />
                    </div> -->
                </div>
                <span
                    class="max-w-36 truncate text-sm font-normal leading-normal"
                >
                    {{ authStore.user?.name }}
                </span>

                <!-- <ChevronRightIcon class="w-8 h-8 text-slate-600 " :class="master.langDirection == 'rtl' ? 'mr-auto rotate-180' : 'ml-auto'" /> -->
                 <img src="../../../public/assets/icons/chevron-right.svg" :class="master.langDirection == 'rtl' ? 'mr-auto rotate-180' : 'ml-auto'" alt="chevron">
            </MenuButton>

            <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <MenuItems
                    class="absolute z-50 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg border border-primary-300"
                    :class="
                        master.langDirection == 'rtl' ? 'left-0' : 'right-0'
                    "
                >
                    <div class="py-3 px-4 flex flex-col gap-2">
                        <MenuItem>
                            <router-link
                                to="/dashboard"
                                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem"
                            >
                                <DashboardIcon
                                    width="24"
                                    height="24"
                                    colorClass="currentColor"
                                />
                                {{ $t("Dashboard") }}
                            </router-link>
                        </MenuItem>

                        <MenuItem>
                            <router-link
                                to="/order-history"
                                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem"
                            >
                                <ArchiveBoxIcon class="w-5 h-5 md:w-6 md:h-6" />
                                {{ $t("Order History") }}
                            </router-link>
                        </MenuItem>

                        <MenuItem>
                            <router-link
                                to="/profile"
                                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem"
                            >
                                <UserIcon class="w-5 h-5 md:w-6 md:h-6" />
                                {{ $t("My Profile") }}
                            </router-link>
                        </MenuItem>

                        <MenuItem>
                            <router-link
                                to="/change-password"
                                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem"
                            >
                                <KeyIcon
                                    width="24"
                                    height="24"
                                    colorClass="currentColor"
                                />
                                {{ $t("Change Password") }}
                            </router-link>
                        </MenuItem>

                        <MenuItem>
                            <button
                                class="flex gap-2 text-red-500 text-base py-2 hover:text-red-600 menuLinkItem"
                                @click="logoutUser"
                            >
                                <LogoutIcon
                                    width="24"
                                    height="24"
                                    colorClass="currentColor"
                                />
                                {{ $t("Log Out") }}
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </div>
    </Menu>

    <div class="hidden">
        <div
            class="flex justify-between w-full items-center gap-2 rounded-lg text-primary pb-2 mb-2"
        >
            <span class="truncate text-base font-normal leading-normal">
                {{ authStore.user?.name }}
            </span>
            <div class="relative">
                <img
                    :src="authStore.user?.profile_photo"
                    alt=""
                    class="w-8 h-8 object-cover rounded-full"
                />
                <div
                    class="w-4 h-4 absolute -bottom-2 right-2/4 translate-x-2/4 rounded-2xl bg-primary-100"
                >
                    <ChevronDownIcon class="w-4 h-4" />
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2 p-2 bg-white rounded-lg">
            <router-link
                to="/dashboard"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
            >
                <span class="flex gap-2">
                    <DashboardIcon
                        width="24"
                        height="24"
                        colorClass="currentColor"
                    />
                    {{ $t("Dashboard") }}
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <router-link
                to="/order-history"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
            >
                <span class="flex gap-2 truncate overflow-hidden">
                    <ArchiveBoxIcon class="w-5 h-5 md:w-6 md:h-6" />
                    {{ $t("Order History") }}
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <router-link
                to="/profile"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
            >
                <span class="flex gap-2 truncate overflow-hidden">
                    <UserIcon class="w-5 h-5 md:w-6 md:h-6" />
                    {{ $t("My Profile") }}
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <router-link
                to="/change-password"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
            >
                <span class="flex gap-2 truncate overflow-hidden">
                    <KeyIcon width="24" height="24" colorClass="currentColor" />
                    {{ $t("Change Password") }}
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <button
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
                @click="logoutModal = true"
            >
                <span class="flex gap-2">
                    <LogoutIcon
                        width="24"
                        height="24"
                        colorClass="currentColor"
                    />
                    {{ $t("Log Out") }}
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </button>
        </div>
    </div>

    <!-- Logout modal -->
    <LogoutModal :showLogoutModal="logoutModal" @hideLogoutModal="() => logoutModal = false" />
</template>

<script setup>
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ChevronDownIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { UserIcon, ArchiveBoxIcon } from "@heroicons/vue/24/outline";
import { ref } from "vue";

import DashboardIcon from "../icons/Dashboard.vue";
import KeyIcon from "../icons/Key.vue";
import LogoutIcon from "../icons/Logout.vue";
import { useAuth } from "../stores/AuthStore";
import { useMaster } from "../stores/MasterStore";
import { useBasketStore } from "../stores/BasketStore";
import { useToast } from "vue-toastification";

import LogoutModal from "./LogoutModal.vue";


const emits = defineEmits(["close"]);

const master = useMaster();
const authStore = useAuth();
const basketStore = useBasketStore();

const toast = useToast();


const logoutModal = ref(false);


function logoutUser(){
    authStore.logout();
    basketStore.total = 0;
    basketStore.checkoutProducts = [];
    basketStore.products = [];
    basketStore.address = null;
    basketStore.selectedShopIds = [];
    basketStore.coupon_code = "";
    basketStore.payable_amount = 0;
    basketStore.delivery_charge = 0;
    basketStore.coupon_discount = 0;

    toast.success("Logout successfully", {
        position:
            master.langDirection === "rtl" ? "bottom-right" : "bottom-left",
    });

    emits("close");
}



</script>

<style>
.menuLinkItem:hover img {
    filter: brightness(0) saturate(100%) invert(39%) sepia(96%) saturate(6525%)
        hue-rotate(256deg) brightness(97%) contrast(91%);
}
</style>
