<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <AuthPageHeader :title="$t('Dashboard')" />

        <div
            class="grid grid-cols-1 xl:grid-cols-5 gap-4 lg:gap-6 px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6 pb-0 h-full">

            <div class="col-span-1 xl:col-span-3 flex flex-col gap-4">

                <!-- Orders Status -->
                <div class="flex flex-wrap md:flex-nowrap gap-4">
                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-lime-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg md:text-2xl font-medium leading-normal tracking-tight">
                                {{ statusOrders.pending + statusOrders.confirm + statusOrders.processing + statusOrders.on_the_way }}
                            </div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                {{ $t('On Going Order') }}
                            </div>
                        </div>
                        <div>
                            <img :src="'/assets/icons/truck-time.svg'" alt="" class="w-6 h-6">
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-primary-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                                {{ basketStore.total }}
                            </div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                {{ $t('Products in Cart') }}
                            </div>
                        </div>
                        <div>
                            <BagIcon width="24" height="24" />
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-red-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                                {{ authStore.favoriteProducts }}
                            </div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                {{ $t('Products in Wishlist') }}
                            </div>
                        </div>
                        <div>
                            <HeartIcon class="w-6 h-6 text-red-500" />
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-blue-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">{{ statusOrders.all }}</div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                {{ $t('Products Ordered') }}
                            </div>
                        </div>
                        <div>
                            <img :src="'/assets/icons/box-tick.svg'" alt="" class="w-6 h-6">
                        </div>
                    </div>
                </div>

                <div class="grow">
                    <DashboardMyCart />
                </div>

            </div>

            <div class="col-span-1 xl:col-span-2 flex flex-col gap-4">
                <div>
                    <DashboardDefaultShippingAddress />
                </div>

                <div class="grow">
                    <DashboardRecentlyView />
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { HeartIcon } from '@heroicons/vue/24/outline';
import { onMounted, ref } from 'vue';
import AuthPageHeader from '../components/AuthPageHeader.vue';
import DashboardDefaultShippingAddress from '../components/DashboardDefaultShippingAddress.vue';
import DashboardMyCart from '../components/DashboardMyCart.vue';
import DashboardRecentlyView from '../components/DashboardRecentlyView.vue';
import BagIcon from '../icons/Bag.vue';

import { useAuth } from '../stores/AuthStore';
import { useBasketStore } from '../stores/BasketStore';

import { useRouter } from 'vue-router';
const router = useRouter();

const basketStore = useBasketStore();
const authStore = useAuth();

onMounted(() => {
    fetchOrders();
});

const statusOrders = ref({
    all: 0,
    pending: 0,
    confirm: 0,
    processing: 0,
    on_the_way: 0,
    delivered: 0,
    cancelled: 0
});

const fetchOrders = async () => {
    axios.get('/orders', {
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        statusOrders.value = response.data.data.status_wise_orders;
    }).catch((error) => {
        if (error.response.status === 401) {
            authStore.token = null;
            authStore.user = null;
            authStore.addresses = [];
            authStore.favoriteProducts = 0;
            router.push('/');
        }
    });
};


</script>
