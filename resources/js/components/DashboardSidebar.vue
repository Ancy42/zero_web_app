<template>
    <div>
        <div class="text-center hidden md:block">
            <img
                :src="authStore.user?.profile_photo"
                alt="profile"
                class="md:w-20 md:h-20 lg:w-24 lg:h-24 object-cover rounded-full mx-auto"
            />

            <div
                class="mt-3 text-slate-900 text-2xl font-medium font-['Roboto'] leading-loose truncate"
            >
                {{ authStore.user?.name }}
            </div>

            <div class="text-slate-500 text-sm font-normal leading-tight">
                {{ authStore.user?.phone }}
            </div>
        </div>

        <div
            class="bg-slate-50 rounded-[10px] flex overflow-x-auto md:flex-col justify-start items-start md:mt-4"
        >
            <router-link to="/dashboard" class="routerLink group">
                <Squares2X2Icon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <Squares2X2IconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Dashboard") }}
            </router-link>

            <router-link
                to="/order-history"
                class="routerLink group"
                :class="
                    route.name == 'order-details' ? 'router-link-active' : ''
                "
            >
                <ArchiveBoxIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <ArchiveBoxIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Order History") }}
            </router-link>

            <router-link to="/wishlist" class="routerLink group">
                <HeartIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <HeartIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Wishlist") }}
            </router-link>

            <router-link to="/massages" class="routerLink group">
                <div class="relative">
                    <ChatBubbleLeftEllipsisIcon
                        class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                    />
                    <ChatBubbleLeftEllipsisIcon
                        class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                    />
                    <span
                        v-if="chatStore.unreadMessages > 0"
                        class="absolute top-0 right-[-5px] -translate-y-1/2 bg-green-500 text-white rounded-full w-4 h-4 flex justify-center items-center text-[10px]"
                    >
                        {{ chatStore.unreadMessages }}
                    </span>
                </div>
                <span>{{ $t("Message") }}</span>
                <!-- -->
            </router-link>

            <router-link to="/profile" class="routerLink group">
                <UserIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <UserIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("My Profile") }}
            </router-link>

            <router-link
                to="/manage-address"
                class="routerLink group"
                :class="
                    route.name == 'add-new-address' ||
                    route.name == 'edit-address'
                        ? 'router-link-active'
                        : ''
                "
            >
                <MapPinIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <MapPinIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Manage Address") }}
            </router-link>

            <router-link to="/support-tickets" class="routerLink group">
                <TicketIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <TicketIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Support Ticket") }}
            </router-link>

            <router-link to="/change-password" class="routerLink group">
                <KeyIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <KeyIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Change Password") }}
            </router-link>

            <router-link to="/terms-and-conditions" class="routerLink group">
                <ListBulletIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <ListBulletIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Terms & Conditions") }}
            </router-link>

            <router-link to="/privacy-policy" class="routerLink group">
                <ShieldCheckIcon
                    class="w-5 h-5 md:w-6 md:h-6 hidden group-[&.router-link-active]:block"
                />
                <ShieldCheckIconOutline
                    class="w-5 h-5 md:w-6 md:h-6 block group-[&.router-link-active]:hidden"
                />
                {{ $t("Privacy Policy") }}
            </router-link>

            <div @click="showLogoutModal = true" class="routerLink group cursor-pointer">
                <LogoutIcon width="24" height="24" colorClass="currentColor" />
                {{ $t("Logout") }}
            </div>
        </div>
    </div>

    <LogoutModal :showLogoutModal="showLogoutModal" @hideLogoutModal="() => showLogoutModal = false" />
</template>

<script setup>
import { useAuth } from "../stores/AuthStore";
import {
    Squares2X2Icon,
    ArchiveBoxIcon,
    HeartIcon,
    UserIcon,
    MapPinIcon,
    TicketIcon,
    ShieldCheckIcon,
    ListBulletIcon,
    KeyIcon,
    ChatBubbleLeftEllipsisIcon,
} from "@heroicons/vue/24/solid";
import {
    Squares2X2Icon as Squares2X2IconOutline,
    HeartIcon as HeartIconOutline,
    ArchiveBoxIcon as ArchiveBoxIconOutline,
    UserIcon as UserIconOutline,
    MapPinIcon as MapPinIconOutline,
    TicketIcon as TicketIconOutline,
    ShieldCheckIcon as ShieldCheckIconOutline,
    ListBulletIcon as ListBulletIconOutline,
    KeyIcon as KeyIconOutline,
} from "@heroicons/vue/24/outline";

import { useRoute } from "vue-router";

import { useChat } from "../stores/ChatStore";
import LogoutIcon from "../icons/Logout.vue";
import LogoutModal from "./LogoutModal.vue";
import { ref } from "vue";

const chatStore = useChat();
const authStore = useAuth();
const route = useRoute();

const showLogoutModal = ref(false);


</script>

<style scoped>
.routerLink {
    @apply md:p-4 p-[10px] rounded-lg md:rounded-[10px] justify-start items-center gap-3 inline-flex border-b-2 md:border-b-0 md:border-r-2 border-transparent hover:text-primary text-slate-600 text-sm md:text-base leading-normal transition duration-300 shrink-0 md:w-full;
}

.router-link-active {
    @apply border-primary bg-primary-100 text-primary;
}
</style>
