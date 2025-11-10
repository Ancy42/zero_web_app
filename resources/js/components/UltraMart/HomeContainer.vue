<template>
    <section>
        <HeroBanner :banners="banners" :ads="ads" :isLoading="isLoading" />
        <Categories :categories="categories" :isLoading="isLoadingCategory" />

        <div v-if="incomingFlashSale.length > 0">
            <FlashSaleIncoming
                v-for="flashSale in incomingFlashSale"
                :flashSale="flashSale"
            />
        </div>

        <div v-if="runningFlashSale.length > 0">
            <FlashSaleRunning
                v-for="flashSale in runningFlashSale"
                :flashSale="flashSale"
            />
        </div>

        <PopularProducts :tabs="popularProducts" />

        <div v-if="master.getMultiVendor">
            <TopRatedShops :shops="topRatedShops" :isLoading="isLoading" />
        </div>
        <JustForYou :justForYou="justForYou" :isLoading="isLoading" />
    </section>
</template>

<script setup>
import { useMaster } from "../../stores/MasterStore";
import HeroBanner from "../UltraMart/HeroBanner.vue";
import Categories from "../UltraMart/Categories.vue";
import PopularProducts from "../UltraMart/PopularProducts.vue";
import TopRatedShops from "../UltraMart/TopRatedShops.vue";
import JustForYou from "../UltraMart/JustForYou.vue";
import FlashSaleRunning from "../UltraMart/FlashSaleRunning.vue";
import FlashSaleIncoming from "../UltraMart/FlashSaleIncoming.vue";

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
