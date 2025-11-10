<template>
    <div class="flex flex-col md:flex-row xl:flex-col items-center gap-6">

        <!-- Shipping Charge and Estimated delivery time -->
        <div
            class="w-full grow md:flex lg:gap-6 xl:block p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2 xl:space-y-4">

            <!-- Delivery charge -->
            <div class="flex grow flex-col items-start xl:flex-row xl:items-center gap-2 xl:gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl justify-center items-center flex">
                    <img :src="'/assets/icons/money.svg'" alt="" class="w-6 h-6">
                </div>
                <div>
                    <div class="text-slate-500 text-base font-normal leading-normal">
                        {{ $t('Delivery charge') }}
                    </div>
                    <div class="mt-1 text-slate-950 text-base font-bold leading-normal">
                        {{ masterStore.showCurrency(product.shop?.delivery_charge) }}
                    </div>
                </div>
            </div>

            <!-- Estimated delivery time -->
            <div class="flex grow flex-col items-start xl:flex-row xl:items-center gap-2 xl:gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl justify-center items-center flex">
                    <img :src="'/assets/icons/clock.svg'" alt="" class="w-6 h-6">
                </div>
                <div>
                    <div class="text-slate-500 text-base font-normal leading-normal">
                        {{ $t('Estimated delivery') }}
                    </div>
                    <div class="mt-1 text-slate-950 text-base font-bold leading-normal">
                        {{ product.shop?.estimated_delivery_time }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop info -->
        <div v-if="masterStore.multiVendor"
            class="bg-slate-50 hover:border-primary transition w-full grow rounded-xl border border-slate-100 xl:mt-6">
            <div class="flex justify-between items-center gap-4 p-4">
                <router-link :to="`/shops/` + product.shop?.id" class="flex items-center gap-4 overflow-hidden">
                    <div class="w-14 h-14 rounded-full overflow-hidden shrink-0">
                        <img :src="product.shop?.logo" loading="lazy" class="w-full h-full object-cover">
                    </div>

                    <div class="overflow-hidden">
                        <div class="text-slate-500 text-sm font-normal leading-tight">
                            {{ $t('Sold by') }}
                        </div>
                        <div class="mt-1.5 text-slate-950 text-base font-medium leading-normal truncate">
                            {{ product.shop?.name }}
                        </div>
                    </div>
                </router-link>

                <div class="">
                    <StarIcon class="w-5 h-5 text-amber-500" />
                    <div class="text-slate-800 text-sm font-bold mt-1">
                        {{ product.shop?.rating.toFixed(1) }}
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 text-red-500 text-sm font-normal leading-tight flex items-center px-4 py-1 cursor-pointer"
                :class="authStore.token ? 'justify-between' : 'justify-center'">
                <router-link :to="`/shops/` + product.shop?.id" class="py-2 block text-center">
                    {{ $t('Visit Store') }}
                </router-link>
                <div v-if="authStore.token"
                    class="w-8 h-8 flex justify-center items-center bg-red-50 rounded-lg cursor-pointer"
                    @click="showChat(product.shop?.id, authStore?.user?.id, product.id)">
                    <img src="/public/assets/icons/shop-chat/chat.svg" alt="chat" class="w-6 h-6">
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <RightChatSidebar :show="showSidebar" @close="showSidebar = false" :shop="product?.shop" />
            </div>
        </div>

    </div>

    <!-- Popular Products -->
    <div class="mt-8">
        <div class="text-slate-800 text-base font-medium leading-normal">
            {{ $t('Popular Products From Them') }}
        </div>

        <div class="flex gap-4 md:grid md:grid-cols-2 lg:grid-cols-3 xl:block xl:space-y-4 mt-4 overflow-x-auto">
            <div v-for="product in popularProducts" :key="product.id" class="w-[320px]  md:w-full shrink-0">
                <ProductCardHorizontal :product="product" />
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { StarIcon } from '@heroicons/vue/24/solid';
import ProductCardHorizontal from './ProductCardHorizontal.vue';
import RightChatSidebar from './RightChatSidebar.vue';

import { useMaster } from "../stores/MasterStore";
import { useAuth } from '../stores/AuthStore';
import axios from 'axios';

const authStore = useAuth();
const masterStore = useMaster();
const showSidebar = ref(false);

const props = defineProps({
    product: Object,
    popularProducts: Array
});

const showChat = async (sid, uid, pid) => {
    const response = await axios.post('/store-message', {
        shop_id: sid,
        user_id: uid,
        product_id: pid,
        type: 'user',
    }, {
        headers: {
            Authorization: authStore.token,
        }
    });

    showSidebar.value = true;
}

</script>
