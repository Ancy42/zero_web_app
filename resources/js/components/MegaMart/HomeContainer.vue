<template>
    <div>
        <HeroBanner :banners="banners" :ads="ads" :isLoading="isLoading" />
        <div v-if="incomingFlashSale.length > 0">
            <FlashSaleIncoming
                v-for="incomingFlash in incomingFlashSale"
                :flashSale="incomingFlash"
            />
        </div>
        <div v-if="runningFlashSale.length > 0">
            <FlashSaleRunning
                v-for="flashSale in runningFlashSale"
                :flashSale="flashSale"
            />
        </div>

        <Categories :categories="categories" :isLoading="isLoadingCategory" />

        
        <PopularProducts :tabs="popularProducts" />

        <div v-if="master.getMultiVendor">
            <TopRatedShops :shops="topRatedShops" :isLoading="isLoading" />
        </div>


        <JustForYou :justForYou="justForYou" :isLoading="isLoading" />
    </div>
</template>

<script setup>
import { useMaster } from "../../stores/MasterStore";
import Categories from "../MegaMart/Categories.vue";
import FlashSaleIncoming from "../MegaMart/FlashSaleIncoming.vue";
import FlashSaleRunning from "../MegaMart/FlashSaleRunning.vue";
import HeroBanner from "../MegaMart/HeroBanner.vue";
import JustForYou from "../MegaMart/JustForYou.vue";
import PopularProducts from "../MegaMart/PopularProducts.vue";
import TopRatedShops from "../MegaMart/TopRatedShops.vue";

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
