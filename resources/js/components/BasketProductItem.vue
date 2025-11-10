<template>
    <div class="bg-white flex gap-4 w-full group">
        <!-- product image -->
        <div class="w-[72px] h-[95px] shrink-0 cursor-pointer" @click="goToDetails()">
            <img :src="props.product?.thumbnail" alt="product" class="w-full h-full object-cover" loading="lazy" />
        </div>

        <div class="grow flex flex-col gap-2 border-b border-slate-200 pb-3 relative overflow-hidden">
            <!-- Brand -->
            <div class="text-primary text-xs font-normal leading-none">
                <span class="cursor-pointer" @click="goToDetails()">
                    {{ props.product?.brand }}
                </span>
            </div>
            <!-- Title -->
            <div class="text-slate-950 text-base font-normal leading-normal truncate">
                <span class="cursor-pointer" @click="goToDetails()">
                    {{ props.product?.name }}
                </span>
            </div>

            <!-- Price Discount -->
            <div class="flex items-center gap-2">
                <div class="text-primary text-base font-bold leading-normal">
                    {{ master.showCurrency(props.product?.discount_price > 0 ? props.product?.discount_price : props.product?.price) }}
                </div>
                <div v-if="props.product?.discount_price > 0" class="text-slate-400 text-sm font-normal line-through leading-tight">{{ master.showCurrency(props.product?.price ) }}</div>
                <div v-if="props.product?.discount_percentage > 0" class="px-1 py-0.5 bg-red-500 rounded-2xl text-white text-xs font-medium">
                    {{ props.product?.discount_percentage }}% {{ $t('OFF') }}
                </div>
            </div>

            <!-- Size -->
            <div class="flex justify-between gap-2 flex-wrap w-full items-center">
                <div class="flex gap-2">
                    <div v-if="props.product?.size" class="min-w-8 p-1 bg-slate-100 rounded text-center text-slate-800 text-xs font-normal">
                       {{ props.product?.size?.name }}
                    </div>
                    <div v-if="props.product?.color" class="min-w-8 p-1 bg-slate-100 rounded text-center text-slate-800 text-xs font-normal">
                       {{ props.product?.color?.name }}
                    </div>
                </div>

                <!-- Quantity Increase Or Decrease -->
                <div class="p-1 rounded-lg border border-slate-200 flex gap-2">
                    <button class="bg-slate-200 w-6 h-6 rounded" @click="basketStore.decrementQuantity(props.product)">
                        <MinusIcon class="w-6 h-6 text-slate-800" />
                    </button>

                    <div class="w-6 text-center text-slate-950 text-base font-medium leading-normal">
                        {{ props.product?.quantity }}
                    </div>

                    <button class="bg-slate-200 w-6 h-6 rounded" @click="basketStore.incrementQuantity(props.product)">
                        <PlusIcon class="w-6 h-6 text-slate-800" />
                    </button>
                </div>
            </div>

            <!-- Delete button -->
            <button class="absolute top-0 hidden group-hover:block transition-all duration-300" :class="master.langDirection == 'rtl' ? 'left-0' : 'right-0'" @click="basketStore.removeFromBasket(props.product)">
                <TrashIcon class="w-6 h-6 text-red-500"/>
            </button>

        </div>
    </div>
</template>

<script setup>
import { MinusIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/solid';

import { useBasketStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";
import { useRouter } from 'vue-router';

const router = useRouter();
const basketStore = useBasketStore();
const master = useMaster();

const props = defineProps({
    product: Object
});

const goToDetails = () => {
    master.basketCanvas = false,
    router.push(`/products/${props.product?.slug}`);
}

</script>
