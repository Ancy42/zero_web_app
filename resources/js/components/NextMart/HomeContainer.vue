<template>
    <section class="">
        <HeroBanner :banners="banners" :ads="ads" :isLoading="isLoading" />
        <Categories :categories="categories" :isLoading="isLoadingCategory" />
        <div v-if="incomingFlashSale.length > 0">
            <FlashSaleIncoming
                v-for="incomingFlash in incomingFlashSale"
                :flashSale="incomingFlash"
            />
        </div>
        <div
            v-if="runningFlashSale.length > 0"
            class="py-4 lg:py-10 2xl:py-16 bg-neutral-100 space-y-10"
        >
            <FlashSaleRunning
                v-for="flashSale in runningFlashSale"
                :flashSale="flashSale"
            />
        </div>
        <OfferBanner />
        <PopularProducts :tabs="popularProducts" />
        <div v-if="master.getMultiVendor">
            <TopRatedShops :shops="topRatedShops" :isLoading="isLoading" />
        </div>

        <JustForYou :justForYou="justForYou" :isLoading="isLoading" />
    </section>
</template>

<script setup>
import { useMaster } from "../../stores/MasterStore";
import HeroBanner from "../NextMart/HeroBanner.vue";
import Categories from "./Categories.vue";
import FlashSaleIncoming from "./FlashSaleIncoming.vue";
import FlashSaleRunning from "./FlashSaleRunning.vue";
import OfferBanner from "./OfferBanner.vue";
import PopularProducts from "./PopularProducts.vue";
import TopRatedShops from "./TopRatedShops.vue";
import JustForYou from "./JustForYou.vue";

defineProps({
    banners: {
        type: Array,
        default: [],
    },
    categories: {
        type: Array,
        default: [],
    },
    incomingFlashSale: {
        type: Array,
        default: [],
    },
    runningFlashSale: {
        type: Array,
        default: [],
    },
    popularProducts: {
        type: Array,
        default: [],
    },
    topRatedShops: {
        type: Array,
        default: [],
    },
    justForYou: {
        type: Array,
        default: [],
    },
    recentlyViewProducts: {
        type: Array,
        default: [],
    },
    ads: {
        type: Array,
        default: [],
    },
    isLoading: {
        type: Boolean,
        default: true,
    },
    isLoadingCategory: {
        type: Boolean,
        default: true,
    },
});

const master = useMaster();
</script>
