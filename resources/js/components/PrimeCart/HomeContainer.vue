<template>
    <section class="overflow-x-hidden">
        <HeroBanner :banners="banners" :ads="ads" :isLoading="isLoading" />
        <Categories :categories="categories" :isLoading="isLoadingCategory" />

        <div
            v-if="incomingFlashSale.length > 0"
            class="py-4 lg:py-10 2xl:py-16 bg-primary-50 relative overflow-hidden"
        >
            <div
                class="h-full w-full absolute top-0 right-0 flex justify-start items-start z-0"
            >
                <div
                    class="w-full h-[200px] bg-warning container-2 origin-top-left rotate-45 blur-[200px]"
                ></div>
            </div>

            <div class="relative z-10 space-y-10">
                <FlashSaleIncoming
                    v-for="incomingFlash in incomingFlashSale"
                    :flashSale="incomingFlash"
                />
            </div>
        </div>
        <div
            v-if="runningFlashSale.length > 0"
            class="py-4 lg:py-10 2xl:py-16 bg-primary-50 relative overflow-hidden"
        >
            <div
                class="h-full w-full absolute top-0 right-0 flex justify-start items-start z-0"
            >
                <div
                    class="w-full h-[200px] bg-warning container-2 origin-top-left rotate-45 blur-[200px]"
                ></div>
            </div>

            <div class="relative z-10 space-y-10">
                <FlashSaleRunning
                    v-for="flashSale in runningFlashSale"
                    :flashSale="flashSale"
                />
            </div>
        </div>

        <div class="relative">
            <div
                class="h-full w-full absolute top-0 right-0 flex justify-start items-center z-0"
            >
                <div
                    class="w-full h-[100px] bg-warning container-2 origin-center rotate-45 blur-[200px]"
                ></div>
            </div>

            <div class="relative z-10">
                <PopularProducts :tabs="popularProducts" />

                <OfferBanner />
                <div v-if="master.getMultiVendor">
                    <TopRatedShops
                        :shops="topRatedShops"
                        :isLoading="isLoading"
                    />
                </div>
            </div>
        </div>

        <!-- shade -->
        <div class="relative container-2">
            <div
                class="w-[200px] h-full left-0 top-24 absolute rotate-[-40deg] bg-yellow-300 origin-top-left blur-[250px]"
            ></div>

            <JustForYou :justForYou="justForYou" :isLoading="isLoading" />
        </div>

        

        <OfferBanner2 />
    </section>
</template>

<script setup>
import HeroBanner from "../PrimeCart/HeroBanner.vue";
import Categories from "../PrimeCart/Categories.vue";
import FlashSaleIncoming from "../PrimeCart/FlashSaleIncoming.vue";
import FlashSaleRunning from "../PrimeCart/FlashSaleRunning.vue";
import PopularProducts from "../PrimeCart/PopularProducts.vue";
import OfferBanner from "../PrimeCart/OfferBanner.vue";
import TopRatedShops from "../PrimeCart/TopRatedShops.vue";
import JustForYou from "../PrimeCart/JustForYou.vue";
import OfferBanner2 from "../PrimeCart/OfferBanner2.vue";
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
