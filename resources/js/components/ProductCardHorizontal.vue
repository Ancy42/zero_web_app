<template>
    <div class="rounded-lg border transition-all duration-300 group bg-white overflow-hidden relative"
        :class="props.product?.quantity > 0 ? 'hover:border-primary' : ''">

        <div class="grid grid-cols-3">
            <div class="w-full h-full overflow-hidden shrink-0 cursor-pointer" @click="showProductDetails"
                :class="props.product?.quantity > 0 ? '' : 'opacity-30'">
                <img :src="props.product?.thumbnail" class="w-full h-full group-hover:scale-110 transition duration-500 object-contain" loading="lazy"/>
            </div>

            <div class="bg-white p-2 flex flex-col items-start gap-2 col-span-2">
                <div class="flex flex-col items-start gap-2 w-full cursor-pointer" @click="showProductDetails">

                    <div class="text-slate-950 text-base font-normal leading-normal truncate w-full"
                        :class="props.product?.quantity > 0 ? '' : 'opacity-30'">
                        {{ props.product?.name }}
                    </div>

                    <div class="flex items-center gap-2 flex-wrap"
                        :class="props.product?.quantity > 0 ? '' : 'opacity-30'">
                        <div class="text-primary text-base font-bold leading-normal">
                            {{ masterStore.showCurrency(props.product?.discount_price > 0 ?
                                props.product?.discount_price : props.product?.price) }}
                        </div>
                        <div v-if="props.product?.discount_price > 0"
                            class="text-slate-400 text-sm font-normal line-through leading-tight">
                            {{ masterStore.showCurrency(props.product?.price) }}
                        </div>
                        <div v-if="props.product?.discount_percentage > 0"
                            class="px-1 py-0.5 bg-red-500 rounded-2xl text-white text-xs font-medium">
                            {{ props.product?.discount_percentage }}% {{ $t('OFF') }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center w-full">
                        <div class="flex items-center gap-1" :class="props.product?.quantity > 0 ? '' : 'opacity-30'">
                            <StarIcon class="w-4 h-4 text-yellow-400" />
                            <div class="text-slate-950 text-sm font-bold leading-tight">
                                {{ props.product?.rating.toFixed(1) }}
                            </div>
                            <div class="text-slate-500 text-sm font-normal leading-tight">
                                ({{ props.product?.total_reviews }})
                            </div>
                        </div>

                        <div class="h-3 w-[0px] border border-slate-200"></div>

                        <div v-if="props.product?.quantity > 0"
                            class="text-right text-slate-500 text-sm font-normal leading-tight">
                            {{ props.product?.total_sold }} {{ $t('Sold') }}
                        </div>
                        <div v-else class="text-right text-red-500 text-sm font-normal leading-tight">
                            {{ $t('Stock Out') }}
                        </div>
                    </div>
                </div>

                <div v-if="props.product?.quantity > 0" class="justify-start items-center gap-3 flex">
                    <button class="cursor-pointer" @click="addToBasket(props.product)">
                        <div class="w-5 h-5">
                            <BagIcon />
                        </div>
                    </button>

                    <div class="w-1.5 h-1.5 bg-slate-200 rounded-md"></div>

                    <button class="justify-center items-center gap-0.5 flex" @click="buyNow">
                        <div class="text-slate-600 text-sm font-normal leading-tight">
                            {{ $t('Buy Now') }}
                        </div>
                        <ArrowRightIcon class="w-4 h-4 text-slate-600" />
                    </button>
                </div>
                <button v-else class="justify-start items-center gap-2 flex">
                    <div class="text-red-500 text-sm font-normal leading-tight">{{ $t('Request Stock') }}</div>
                    <ArrowRightIcon class="w-4 h-4 text-red-500" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowRightIcon, StarIcon } from '@heroicons/vue/24/solid';
import { useRouter } from 'vue-router';
import BagIcon from '../icons/Bag.vue';
import { useAuth } from '../stores/AuthStore';
import { useBasketStore } from '../stores/BasketStore';
import { useMaster } from '../stores/MasterStore';

const router = new useRouter();
const authStore = useAuth();

const basketStore = useBasketStore();

const masterStore = useMaster();

const props = defineProps({
    product: Object
});

const orderData = {
    is_buy_now: false,
    product_id: props.product?.id,
    quantity: 1,
    size: null,
    color: null,
    unit: null
};

const addToBasket = (product) => {
    // add product to basket
    basketStore.addToCart(orderData, product);
};

const buyNow = () => {
    if (authStore.token === null) {
        return authStore.loginModal = true;
    }

    basketStore.addToCart({
        product_id: props.product?.id,
        is_buy_now: true,
        quantity: 1,
        size: null,
        color: null,
        unit: null
    }, props.product);

    basketStore.buyNowShopId = props.product?.shop.id;
    router.push({ name: 'buynow' })
};

const showProductDetails = () => {
    if (props.product.quantity > 0) {
        router.push({ name: 'productDetails', params: { slug: props.product.slug } })
    }
}

</script>
