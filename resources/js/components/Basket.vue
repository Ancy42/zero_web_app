<template>
    <div class="space-y-4">

        <!-- Shop Wise Basket products -->
        <div v-for="shop in basketStore.products" :key="shop.id" class="border border-slate-100 rounded-xl">

            <!-- Shop info -->
            <div v-if="master.multiVendor"
                class="p-4 bg-slate-50 rounded-t-xl justify-between items-center gap-4 flex cursor-pointer"
                @click="basketStore.selectCartItemsForCheckout(shop.shop_id)">
                <div class="flex gap-4 items-center grow overflow-hidden">
                    <div class="flex gap-2 items-center overflow-hidden">
                        <RadioCheckIcon
                            :colorClass="basketStore.checkShopIsSelected(shop.shop_id) ? 'text-primary' : 'text-slate-300'" />
                        <div class="text-slate-950 text-base font-medium truncate">
                            {{ shop.shop_name }}
                        </div>
                    </div>
                </div>

                <div class="flex gap-1 items-center">
                    <StarIcon class="w-5 h-5 text-amber-500" />
                    <div class="text-slate-800 text-sm font-medium leading-tight">{{ shop.shop_rating }}</div>
                </div>
                <div class="w-8 h-8 flex justify-center items-center bg-red-50 rounded-lg cursor-pointer"
                    @click.stop="openChat(shop)">
                    <img src="/public/assets/icons/shop-chat/chat.svg" alt="chat" class="w-6 h-6">
                </div>
            </div>

            <!-- product item -->
            <div class="p-4 flex flex-col gap-3">
                <div v-for="product in shop.products" :key="product.id">
                    <BasketProductItem :product="product" />
                </div>
            </div>

            <div v-if="master.multiVendor"
                class="px-4 bg-white border-t border-slate-100 rounded-b-xl flex justify-between items-center cursor-pointer"
                @click="getShopVoucher(shop)">
                <div class="flex gap-2 items-center py-2">
                    <img :src="'/assets/icons/ticket.svg'" class="w-6 h-6" />
                    <div class="text-slate-500 text-sm font-normal leading-tight">{{ $t('Apply Store voucher') }}</div>
                </div>
                <ChevronRightIcon class="w-[18px] h-[18px] text-slate-500" />
            </div>

        </div>
        <!-- chat sidebar -->
        <RightChatSidebar v-if="activeChatShop" :show="showSidebar" @close="showSidebar = false"
            :shop="activeChatShop" />

        <!-- Store voucher modal -->
        <TransitionRoot as="template" :show="showVoucherModal">
            <Dialog as="div" class="relative z-50" @close="showVoucherModal = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl">
                                <div class="bg-white p-4 sm:p-8 relative">

                                    <!-- close button -->
                                    <div class="w-9 h-9 bg-slate-100 rounded-[32px] justify-center items-center flex absolute top-3 right-3 cursor-pointer"
                                        @click="showVoucherModal = false">
                                        <XMarkIcon class="w-5 h-5 text-slate-700" />
                                    </div>

                                    <div class="text-slate-500 text-base font-normal leading-normal">
                                        {{ $t('Voucher from') }} :
                                    </div>

                                    <!-- Shop name -->
                                    <div class="text-slate-950 mt-0.5 text-lg sm:text-2xl font-bold leading-loose">
                                        {{ shopName }}
                                    </div>

                                    <div class="mt-4 sm:mt-8 space-y-3 sm:space-y-5">

                                        <div v-for="coupon in coupons" :key="coupon.id"
                                            class="px-4 py-3 bg-primary-50 rounded-xl transition-all border border-dashed"
                                            :class="coupon.isCollected ? 'border-transparent' : 'border-primary'">
                                            <div class="flex justify-between items-start">

                                                <div class="flex flex-col gap-2">
                                                    <!-- Price -->
                                                    <div class="text-gray-900 text-2xl font-bold">
                                                        {{ coupon.discount_type == "Percentage" ? coupon.discount + "%"
                                                            : master.showCurrency(coupon.discount) }}
                                                    </div>
                                                    <!-- Title -->
                                                    <div class="text-slate-950 text-sm font-normal  leading-tight">
                                                        Minimum Spend {{ master.showCurrency(coupon.min_order_amount) }}
                                                    </div>
                                                </div>

                                                <!-- Collect button -->
                                                <div class="px-4 transition  py-2.5 rounded-[100px] text-white text-base font-medium bg-primary"
                                                    :class="coupon.is_collected ? 'opacity-25 rotate-[-15deg]' : 'bg-primary-500 cursor-pointer'"
                                                    @click="collectVoucher(coupon)">
                                                    {{ coupon.is_collected ? 'Collected' : 'Collect' }}
                                                </div>

                                            </div>
                                            <!-- Validity -->
                                            <div class="mt-3 text-primary text-xs font-normal leading-none">
                                                {{ $t('Validity till') }}: {{ coupon.validity }}
                                            </div>
                                        </div>
                                        <div v-if="coupons.length == 0">
                                            <h3 class="text-slate-500 text-lg italic font-normal leading-normal">
                                                {{ $t('Sorry there is no voucher available') }}
                                            </h3>
                                        </div>

                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>


    </div>

</template>

<script setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ChevronRightIcon, StarIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import BasketProductItem from './BasketProductItem.vue';
import RightChatSidebar from './RightChatSidebar.vue';

import axios from 'axios';
import { useRouter } from 'vue-router';
import RadioCheckIcon from '../icons/RadioCheck.vue';
import { useAuth } from '../stores/AuthStore';
import { useBasketStore } from '../stores/BasketStore';
import { useMaster } from '../stores/MasterStore';
import { useChat } from '../stores/ChatStore';

const toast = useToast();
const router = useRouter();

const basketStore = useBasketStore();
const master = useMaster();
const authStore = useAuth();


const showVoucherModal = ref(false)

const coupons = ref([]);

const shopName = ref('');
const showSidebar = ref(false);
const activeChatShop = ref(null);

const openChat = (shop) => {
    activeChatShop.value = shop;
    showChat();
};

const showChat = async () => {
    const response = await axios.post('/store-message', {
        shop_id: activeChatShop.value.shop_id,
        user_id: authStore?.user?.id,
        type: 'user',
    }, {
        headers: {
            Authorization: authStore.token,
        }
    });

    showSidebar.value = true;
}


onMounted(() => {
    if (!master.multiVendor) {
        basketStore.products.forEach(shop => {
            if (!basketStore.checkShopIsSelected(shop.shop_id)) {
                basketStore.selectCartItemsForCheckout(shop.shop_id);
            }
            basketStore.selectedShopIds = [shop.shop_id];
        });
    }
});

const getShopVoucher = (shop) => {
    shopName.value = shop.shop_name
    axios.get('/get-vouchers?shop_id=' + shop.shop_id, {
        headers: {
            'Authorization': authStore.token
        }
    }).then((response) => {
        coupons.value = response.data.data.coupons
        showVoucherModal.value = true
    }).catch((error) => {
        toast.error(error.response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
        if (error.response.status === 401) {
            authStore.token = null;
            authStore.user = null;
            authStore.addresses = [];
            authStore.favoriteProducts = 0;
            router.push('/');
        }
    })
}

const collectVoucher = (coupon) => {
    if (!coupon.is_collected) {
        axios.post('/vouchers-collect', { coupon_id: coupon.id }, {
            headers: {
                'Authorization': authStore.token
            }
        }).then((response) => {
            toast.success(response.data.message, {
                position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
            });
            coupon.is_collected = true
        }).catch((error) => {
            toast.error(error.response.data.message, {
                position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
            });
        })
    }
}

</script>
