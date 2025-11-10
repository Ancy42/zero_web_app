<template>
    <div>
        <HeroBanner :banners="banners" :ads="ads" :isLoading="isLoading" />
        <Categories :categories="categories" :isLoading="isLoadingCategory" />
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
        <OfferBanners />
        <PopularProducts :tabs="popularProducts" />
        <div v-if="master.getMultiVendor">
            <TopRatedShops :shops="topRatedShops" :isLoading="isLoading" />
        </div>
        
        <JustForYou :justForYou="justForYou" :isLoading="isLoading" />
    </div>
</template>

<script setup>
import Categories from "../NovaStore/Categories.vue";
import FlashSaleIncoming from "../NovaStore/FlashSaleIncoming.vue";
import OfferBanners from "../NovaStore/OfferBanners.vue";
import HeroBanner from "../NovaStore/HeroBanner.vue";
import FlashSaleRunning from "../NovaStore/FlashSaleRunning.vue";
import PopularProducts from "../NovaStore/PopularProducts.vue";
import TopRatedShops from "../NovaStore/TopRatedShops.vue";
import JustForYou from "../NovaStore/JustForYou.vue";
import { useMaster } from "../../stores/MasterStore";

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

