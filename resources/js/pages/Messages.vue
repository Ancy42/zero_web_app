<template>
    <div class="h-full lg:h-[80vh] flex flex-col">
        <!-- Header -->
        <AuthPageHeader :title="$t('Messages')" />

        <div class="flex-1 overflow-hidden p-3">
            <!-- Outer Flex: Column on small, Row on large -->
            <div class="flex flex-col lg:grid lg:grid-cols-12 h-full overflow-hidden p-3 bg-white gap-2 lg:gap-4 rounded-2xl">

                <!-- Sidebar -->
                <div class="w-full col-span-12 lg:col-span-5 xl:col-span-4 h-[40vh] lg:h-full border-r bg-gray-100 p-2 lg:p-3 rounded-2xl overflow-auto">
                    <div ref="shopContainer" class="bg-white rounded-2xl h-full max-h-[90vh] overflow-y-auto">
                        <!-- Search and Sellers List -->
                        <div class="p-2 lg:p-4 border-b relative">
                            <input type="text" placeholder="Search Seller" @keyup="searchShops($event)"
                                class="w-full pl-10 py-3 md:py-4 pr-4 border rounded-lg text-sm md:text-base" />
                            <img src="/public/assets/icons/shop-chat/search.svg" alt="search"
                                class="w-5 h-5 lg:w-6 lg:h-6 absolute top-[50%] translate-y-[-50%] left-4 lg:left-8">
                        </div>
                        <!-- Seller List -->
                        <ul class="divide-y divide-gray-50 p-1 xl:p-2">
                            <li v-for="shop in shops" @click="handleClick(shop)"
                                class="flex flex-col-reverse xl:flex-row gap-2 items-start justify-between p-2 xl:p-4 transition-all duration-500 rounded-lg cursor-pointer"
                                :class="chatStore.activeShop?.id == shop.shop.id ? 'bg-pink-100 hover:bg-pink-100' : 'bg-white hover:bg-gray-100'">
                                <div class="flex items-center flex-wrap gap-1 space-x-1">
                                    <img :src="shop.shop.logo" alt=""
                                        class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10 rounded-full border"
                                        :class="chatStore.activeShop?.id == shop.shop.id ? 'border-red-500' : 'border-white'">
                                    <div>
                                        <div class="font-semibold text-xs lg:text-base flex items-center gap-1"
                                            :class="chatStore.activeShop?.id == shop.shop.id ? 'text-red-500' : 'text-gray-900'">
                                            <span>{{ shop.shop.name }}</span>
                                            <span v-if="shop.unread_message_shop > 0"
                                                :id="'shop-unread-message' + shop.shop.id"
                                                class="text-xs text-white bg-red-500 w-4 h-4 rounded-full flex items-center justify-center">
                                                {{ shop.unread_message_shop }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600 line-clamp-2"
                                            :id="'user-last-message' + shop.shop.id">
                                            {{ shop.last_message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 whitespace-nowrap">{{ shop.last_message_time }}</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Chat Area -->
                <div v-if="chatStore.activeShop" class="p-3 bg-gray-100 rounded-2xl w-full col-span-12 lg:col-span-7 xl:col-span-8">
                    <div class="h-[70vh] lg:h-full bg-white rounded-2xl shadow-lg flex flex-col overflow-y-auto z-50">
                        <!-- Chat Header -->
                        <div class="bg-gray-900 text-white px-4 py-3 flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-2">
                                <img :src="chatStore.activeShop?.logo" alt="Logo" class="w-8 h-8 rounded-full" />
                                <div>
                                    <p class="font-semibold">{{ chatStore.activeShop?.name }}</p>
                                    <p class="text-sm"
                                        :class="{ 'text-green-400': shopOnline, 'text-red-400': !shopOnline }">
                                        {{ shopOnline ? 'Online' : 'Offline' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div ref="chatContainer" class="overflow-y-auto p-4 space-y-4 text-sm flex-1">
                            <div v-if="loadingMore" class="flex justify-center items-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-pink-500">
                                </div>
                            </div>
                            <!-- User Message -->
                            <div class="flex justify-end items-start gap-1 w-full" v-for="chat in chats"
                                :class="chat.type == 'user' ? '' : 'flex-row-reverse'">
                                <!-- Product Info -->
                                <div v-if="chat.product && chat.message == null" class="space-y-1">
                                    <div class="flex items-center gap-3 p-4 border rounded-lg">
                                        <img :src="chat.product?.thumbnail" alt="Product"
                                            class="w-12 h-12 rounded-lg object-cover" />
                                        <div class="flex flex-col text-sm">
                                            <p class="font-semibold  leading-tight text-wrap">
                                                {{ chat.product?.name }}
                                            </p>
                                            <div class="flex justify-between items-center gap-2 mt-1">
                                                <p class=" font-semibold">
                                                    {{ chat.product?.discount_price > 0 ?
                                                        masterStore.showCurrency(parseFloat(chat.product?.discount_price).toFixed(2))
                                                        :
                                                        masterStore.showCurrency(parseFloat(chat.product?.price).toFixed(2))
                                                    }}
                                                </p>
                                                <div class="flex items-center gap-1 text-xs">
                                                    <StarIcon class="w-4 h-4 text-amber-500" />
                                                    <span class="font-semibold">{{ chat.product?.rating }}</span>
                                                    <span class="text-gray-500">({{ chat.product?.total_reviews
                                                        }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ moment(chat.created_at).format('hh:mm a, DD MMM, YYYY') }}
                                    </p>
                                </div>
                                <div v-else class="flex flex-col max-w-[75%]"
                                    :class="chat.type == 'user' ? 'text-end justify-end items-end' : 'text-start justify-start items-start'">
                                    <div class="w-fit max-w-full px-4 py-2 rounded-xl break-words text-justify"
                                        v-html="linkify(chat.message)"
                                        :class="chat.type == 'user' ? 'bg-[#EE446A] text-white' : 'bg-gray-50 text-black'">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ moment(chat.created_at).format('hh:mm a, DD MMM, YYYY') }}
                                    </p>
                                </div>

                                <!-- Product Info -->
                                <img v-if="chat.type == 'user'" :src="chat.user?.profile_photo" alt="User"
                                    class="w-10 h-10 rounded-full" />
                                <img v-else :src="chat.shop?.logo" alt="User" class="w-10 h-10 rounded-full" />
                            </div>

                        </div>

                        <!-- Divider -->
                        <div class="border-t-2 w-2/3 mx-auto"></div>

                        <!-- Input Box -->
                        <form @submit.prevent="sendMessage">
                            <div class="p-3 flex items-center gap-2">
                                <div class="flex items-center gap-2 w-full border rounded-lg py-3 px-3">
                                    <textarea type="text" rows="1" placeholder="Type a message" v-model="message"
                                        @keydown.enter.exact.prevent="sendMessage" @keydown.shift.enter=""
                                        class="flex-1 border-0 rounded-lg text-sm focus:outline-none resize-none break-words"></textarea>
                                    <button type="submit" class="text-white mr-3">
                                        <img src="/public/assets/icons/shop-chat/sent-chat.svg" alt="chat"
                                            class="w-6 h-6">
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else
                    class="text-center text-gray-500 py-4 border w-full h-full flex flex-col items-center justify-center col-span-12 lg:col-span-7 xl:col-span-8">
                    <div class="space-y-2">
                        <p class="font-semibold">No User Selected</p>
                        <p class="text-sm">Please select a user to see their messages</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import AuthPageHeader from "../components/AuthPageHeader.vue";
import Pusher from 'pusher-js';
import { StarIcon } from '@heroicons/vue/24/solid';
import axios from "axios";
import { ref, onMounted, watch, nextTick, onUnmounted } from 'vue';
import { useRoute } from "vue-router";
import { useAuth } from "@/stores/AuthStore";
import { useChat } from "@/stores/ChatStore";
import { useMaster } from "@/stores/MasterStore";
import moment from 'moment';

const route = useRoute();

const chatContainer = ref(null);
const shopContainer = ref(null);
const activeMessage = ref(false);
const authStore = useAuth();
const chatStore = useChat();
const masterStore = useMaster();
let shopOnline = ref(false);
let debounceTimer;


let shops = ref([]);
let message = ref('');
let search = ref('');
let chats = ref([]);
let page = ref(1);
const perPage = 20;
let loadingMore = ref(false);
let allLoaded = ref(false);

// for shop page
let shopPage = ref(1);
const shopPerPage = 20;
let shopAllLoaded = ref(false);
let shopLoadingMore = ref(false);
let canFetchMore = true;


let data = ref({
    message: null,
    created_at: null,
    type: 'user',
    user: {
        profile_photo: null,
    }
});

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
};

const handleClick = async (shop) => {
    shop.unread_message_shop = 0;
    chatStore.activeShop = null;
    chatStore.activeShop = shop.shop;
    page.value = 1;
    allLoaded.value = false;
    getMessages();
};

const fetchUnreadMessages = async () => {
    const response = await axios.get('/unread-messages', {
        params: {
            user_id: authStore.user?.id,
        }
    });

    chatStore.unreadMessages = response.data.data?.unread_messages;

};

const fetchShops = async () => {

    if (shopLoadingMore.value || shopAllLoaded.value) return;

    shopLoadingMore.value = true;

    try {
        const response = await axios.get('get-shops', {
            params: {
                search: search.value,
                page: shopPage.value,
                per_page: shopPerPage,
            },
            headers: {
                Authorization: authStore.token,
            }
        })

        if (response.data.data) {
            const newShops = response.data.data.data;

            if (newShops.length === 0) {
                shopAllLoaded.value = true;
            } else {
                if (shopPage.value > 1) {
                    // Append new shops at the end
                    shops.value = [...shops.value, ...newShops];
                } else {
                    shops.value = newShops;
                }
            }

            shopLoadingMore.value = false;
        }
    } catch (error) {
        console.log('Error fetching shops:', error);
        shopLoadingMore.value = false;
        shopAllLoaded.value = true;
    }
    finally {
        shopLoadingMore.value = false;
    }
}

const getMessages = async (loadMore = false) => {
    if (loadingMore.value || allLoaded.value) return;

    loadingMore.value = true;

    try {
        const response = await axios.get('/get-message', {
            params: {
                shop_id: chatStore.activeShop?.id,
                page: page.value,
                per_page: perPage
            },
            headers: {
                Authorization: authStore.token,
            }
        });

        const newMessages = response.data.data?.data;

        if (newMessages.length === 0) {
            allLoaded.value = true;
        } else {
            if (loadMore) {
                // Preserve scroll position
                const scrollHeightBefore = chatContainer.value.scrollHeight;
                chats.value = [...newMessages.reverse(), ...chats.value];
                await nextTick();
                const scrollHeightAfter = chatContainer.value.scrollHeight;
                chatContainer.value.scrollTop += (scrollHeightAfter - scrollHeightBefore);
            } else {
                chats.value = newMessages.reverse();
                scrollToBottom();
            }
        }

        shopOnline.value = chats.value.some((chat) => chat?.shop_active_status === true);
        fetchUnreadMessages();
    } finally {
        loadingMore.value = false;
    }
};


const sendMessage = async () => {

    if (!message.value) {
        return;
    }

    const sendableMessage = message.value;
    message.value = '';

    data.value.message = sendableMessage
    data.value.created_at = new Date(),
        data.value.user.profile_photo = authStore.user.profile_photo
    chats.value = [...chats.value, data.value]
    data.value = {
        message: null,
        created_at: null,
        type: 'user',
        user: {
            profile_photo: null,
        }
    }

    scrollToBottom();
    activeMessage.value = false

    try {
        activeMessage.value = true
        document.getElementById(`user-last-message${chatStore.activeShop?.id}`).innerText = message.value

        const response = await axios.post('/send-message', {
            shop_id: chatStore.activeShop?.id,
            message: sendableMessage,
            type: 'user',
        }, {
            headers: {
                Authorization: authStore.token,
            }
        });


        activeMessage.value = false

        // chats.value.push((await response.data).data?.data);
        fetchShops();
        updateStatusOnline();
        scrollToBottom();

    } catch (error) {
        message.value = '';
        activeMessage.value = false
        scrollToBottom();
    }

    activeMessage.value = false
};

onMounted(async () => {

    await nextTick();
    // chatStore.activeShop = null;
    scrollToBottom();
    fetchShops();
    getMessages();
    handlePusherChanel();
    chatContainer.value?.addEventListener('scroll', handleScrollTop);
    shopContainer.value?.addEventListener('scroll', handleScrollBottom);
});

const handleScrollTop = () => {
    if (chatContainer.value.scrollTop < 300 && !loadingMore.value && !allLoaded.value) {
        page.value += 1;
        getMessages();
    }
};

const handleScrollBottom = () => {
    if (!shopContainer.value) return;

    const scrollTop = shopContainer.value.scrollTop;

    // Re-enable fetching after user scrolls away
    if (scrollTop > 400) {
        canFetchMore = true;
    }

    // Trigger only if in top zone and allowed
    if (scrollTop < 300 && canFetchMore && !shopLoadingMore.value && !shopAllLoaded.value) {
        canFetchMore = false; // prevent retrigger until scrolls away
        shopPage.value += 1;
        fetchShops(true);
    }
};


onUnmounted(() => {
    chatContainer.value?.removeEventListener('scroll', handleScrollTop);
    shopContainer.value?.removeEventListener('scroll', handleScrollBottom);
});


const handlePusherChanel = () => {

    Pusher.logToConsole = false;

    if (!masterStore.pusher_app_key) {
        return;
    }

    const pusher = new Pusher(masterStore.pusher_app_key, {
        cluster: masterStore.pusher_app_cluster,
        encrypted: true,
    });

    let userId = authStore.user.id;

    const channel = pusher.subscribe('chat_user_' + userId);

    channel.bind('send-message-to-user', function (data) {
        if (route.path == '/massages') {
            fetchShops();
            getMessages();
            fetchUnreadMessages();
        }
    });
}

const searchShops = (e) => {
    clearTimeout(debounceTimer); // clear previous timer
    debounceTimer = setTimeout(() => {
        search.value = e.target.value;
        shopPage.value = 1;
        shopAllLoaded.value = false;
        fetchShops();
    }, 500);
}

const updateStatusOnline = async () => {
    await axios.post('/update-last-seen', {}, {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: authStore.token,
        },
    });
}


const escapeHtml = (text) => {
    const div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
};

const linkify = (text) => {
    if (!text) return '';
    const urlRegex = /(\bhttps?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, (url) => {
        const escapedUrl = escapeHtml(url);
        return `<a href="${escapedUrl}" target="_blank" rel="noopener noreferrer" class="underline text-blue-300 hover:text-blue-100">${escapedUrl}</a>`;
    });
};



</script>
