<template>
    <transition name="slide-sidebar">
        <div class="fixed transition-all h-[70vh] w-3/4 md:w-2/4 lg:w-1/4 right-0 ease-in-out duration-500 bg-white rounded-tl-2xl shadow-lg flex flex-col overflow-hidden z-[5]"
            :class="show ? 'bottom-0' : '-bottom-full'">
            <!-- Header -->
            <div class="bg-gray-900 text-white px-4 py-3 flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-2">
                    <img :src="props.shop?.logo ?? props.shop?.shop_logo" alt="Logo" class="w-8 h-8 rounded-full" />
                    <div>
                        <p class="font-semibold">{{ props.shop?.name ?? props.shop?.shop_name }}</p>
                        <p class="text-sm" :class="{ 'text-green-400': shopOnline, 'text-red-400': !shopOnline }">
                            {{ shopOnline ? 'Online' : 'Offline' }}</p>
                    </div>
                </div>
                <button @click="handleClose" class="text-white text-xl">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>

            <!-- Chat Messages -->
            <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 text-sm">
                <div v-if="isLoadingMessages" class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-pink-500"></div>
                </div>
                <!-- User Message -->
                <div class="flex justify-end items-start gap-1 w-full" v-for="chat in messages"
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
                                            masterStore.showCurrency(parseFloat(chat.product?.discount_price).toFixed(2)) :
                                            masterStore.showCurrency(parseFloat(chat.product?.price).toFixed(2)) }}
                                    </p>
                                    <div class="flex items-center gap-1 text-xs">
                                        <StarIcon class="w-4 h-4 text-amber-500" />
                                        <span class="font-semibold">{{ chat.product?.rating }}</span>
                                        <span class="text-gray-500">({{ chat.product?.total_reviews }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ moment(chat.created_at).format('hh:mm a, DD MMM, YYYY') }}</p>
                    </div>
                    <div v-else class="flex flex-col max-w-[75%]"
                        :class="chat.type == 'user' ? 'text-end justify-end items-end' : 'text-start justify-start items-start'">
                        <div class="w-fit max-w-full px-4 py-2 rounded-xl text-justify break-words"
                            v-html="linkify(chat.message)"
                            :class="chat.type == 'user' ? 'bg-[#EE446A] text-white' : 'bg-gray-50 text-black'">
                        </div>
                        <span class="text-[10px] text-gray-500 mt-0.5">{{ moment(chat.created_at).format('hh:mm a, DD MMM, YYYY') }}</span>
                    </div>


                    <!-- Product Info -->
                    <img v-if="chat.type == 'user'" :src="chat.user?.profile_photo" alt="User"
                        class="w-10 h-10 rounded-full" />
                    <img v-else :src="chat.shop?.logo" alt="User" class="w-10 h-10 rounded-full" />
                </div>
            </div>


            <div class="border-t-2 w-2/3 mx-auto"></div>
            <!-- Input Box -->
            <form @submit.prevent="sendMessage">
                <div class="p-3 flex items-center gap-2">
                    <div class="flex items-center gap-2 w-full border rounded-lg py-3 px-3">
                        <textarea type="text" rows="1" placeholder="Type a message" v-model="message"
                            @keydown.enter.exact.prevent="sendMessage" @keydown.shift.enter=""
                            class="flex-1 border-0 rounded-lg text-sm focus:outline-none resize-none break-words"></textarea>
                        <button type="submit" class="text-white mr-3">
                            <img src="/public/assets/icons/shop-chat/sent-chat.svg" alt="chat" class="w-6 h-6">
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted, watch, nextTick, onUnmounted } from 'vue';
import { StarIcon } from '@heroicons/vue/24/solid';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { useMaster } from '../stores/MasterStore';
import { useAuth } from '../stores/AuthStore';
import moment from 'moment';
import axios from 'axios';
import Pusher from 'pusher-js';

const emit = defineEmits(['close'])

const props = defineProps({
    shop: Object,
    show: Boolean,
});

const masterStore = useMaster();
const authStore = useAuth();
const chatContainer = ref(null);

let activeMessage = ref(false);
let message = ref('');

const currentPage = ref(1);
const hasMorePage = ref(true);
const perPage = 20;
const isLoadingMessages = ref(false);
const isScrollable = ref(false);
const shopOnline = ref(false);

const messages = ref([]);
let scrollTimeout;

let data = ref({
    message: null,
    created_at: null,
    type: 'user',
    user: {
        profile_photo: null,
    }
});

const handleClose = () => {
    messages.value = [];
    currentPage.value = 1;
    emit('close')
}

watch(() => props.show, () => {
    if (props.show) {
        currentPage.value = 1;
        hasMorePage.value = true;
        messages.value = [];
        handlePusherChanel();
        fetchMessages(true);
        initScroll();
    }
});

const fetchMessages = async (goToScrollBottom = false) => {

    if (isLoadingMessages.value || !hasMorePage.value) return;

    isLoadingMessages.value = true;

    let shop_id = props.shop?.shop_id ?? props.shop?.id


    try {
        const response = await axios.get('/get-message', {
            params: {
                shop_id: shop_id,
                page: currentPage.value,
                per_page: perPage,
            },
            headers: {
                Authorization: authStore.token,
            },
        });


        const fetchedMessages = (response.data.data?.data ?? []).reverse();

        console.log('fetchedMessages');

        // First load or reload
        if (currentPage.value === 1) {
            messages.value = fetchedMessages;
            isScrollable.value = true;
        } else {
            const scrollHeightBefore = chatContainer.value.scrollHeight;
            messages.value = [...fetchedMessages, ...messages.value];
            isScrollable.value = false;
            await nextTick();
            const scrollHeightAfter = chatContainer.value.scrollHeight;
            chatContainer.value.scrollTop += (scrollHeightAfter - scrollHeightBefore);
        }

        // Optional: update shop online status
        shopOnline.value = messages.value.some((message) => message?.shop_active_status === true);

        setTimeout(() => {
            isLoadingMessages.value = false;
        }, 1000);

        if (goToScrollBottom) {
            scrollToBottom();
        }

    } catch (error) {
        setTimeout(() => {
            isLoadingMessages.value = false;
        }, 1000);
        console.error('Failed to fetch messages:', error);
    } finally {
        setTimeout(() => {
            isLoadingMessages.value = false;
        }, 1000);
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
    messages.value = [...messages.value, data.value];

    data.value = {
        message: null,
        created_at: null,
        type: 'user',
        user: {
            profile_photo: null,
        }
    }
    scrollToBottom();

    activeMessage.value = true
    try {
        const response = axios.post('/send-message', {
            shop_id: props.shop?.id ?? props.shop?.shop_id,
            message: sendableMessage,
            type: 'user',
        }, {
            headers: {
                Authorization: authStore.token,
            }
        });

        // messages.value.push((await response).data.data?.data);

        isLoadingMessages.value = false;
        hasMorePage.value = true;
        currentPage.value = 1;

        message.value = '';
        activeMessage.value = false

        scrollToBottom();

    } catch (error) {
        activeMessage.value = false
    }
};

const handlePusherChanel = () => {
    let userId = authStore.user.id;

    if (!masterStore.pusher_app_key) {
        return;
    }

    // Pusher.logToConsole = true;

    const pusher = new Pusher(masterStore.pusher_app_key, {
        cluster: masterStore.pusher_app_cluster,
        encrypted: true,
    });

    const channel = pusher.subscribe('chat_user_' + userId);

    channel.bind('send-message-to-user', function (data) {
        console.log('Received message:');
        fetchMessages(true);
        scrollToBottom();
    });
}

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
};

const handleScroll = () => {
    if (!chatContainer.value || isLoadingMessages.value || !hasMorePage.value) return;
    const container = chatContainer.value;
    // If user scrolled to within 1px of bottom
    if (container.scrollTop < 50) {
        if (scrollTimeout) clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            currentPage.value += 1;
            fetchMessages();
        }, 500); // debounce for smoother performance
    }
};

const initScroll = () => {
    scrollToBottom();
    if (chatContainer.value) {
        chatContainer.value.addEventListener('scroll', handleScroll);
    }
};

onUnmounted(() => {
    if (chatContainer.value) {
        chatContainer.value.removeEventListener('scroll', handleScroll);
    }
});

// Optionally, re-scroll on `show` prop change
watch(() => messages.value, (newVal) => {

    if (isScrollable.value) {
        scrollToBottom();
        isScrollable.value = false;
    }
}, { deep: true });

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
