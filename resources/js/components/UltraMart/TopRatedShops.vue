<template>
    <div>
        <SectionContainer :title="'Top Rated Shops'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <router-link
                        to="/shops"
                        class="text-primary text-base md:text-lg font-normal leading-normal"
                    >
                        {{ $t("View All") }}
                    </router-link>
                </div>
            </template>
            <!-- Shops -->
            <div
                :dir="master.langDirection || 'ltr'"
                class="grid grid-cols-12 gap-4 xl:grid-rows-[350px]"
            >
                <a
                    v-if="master.offerBanners.length >= 4"
                    class="hidden md:block md:col-span-4 lg:col-span-3"
                    :href="master.offerBanners[3].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[3]?.offer_banner"
                        alt=""
                        class="w-full h-full"
                    />
                </a>

                <div
                    class="flex justify-start items-center relative"
                    :class="
                        master.offerBanners.length < 4
                            ? 'col-span-12'
                            : 'col-span-12 md:col-span-8 lg:col-span-9'
                    "
                >
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        class="shops_swiper_ultramart"
                        :autoplay="{
                            delay: 4000,
                            disableOnInteraction: false,
                        }"
                    >
                        <swiper-slide
                            v-if="!isLoading"
                            v-for="shop in shops"
                            :key="shop.id"
                        >
                            <ShopCardTop :shop="shop" />
                        </swiper-slide>

                        <!-- loading -->
                        <swiper-slide v-else v-for="i in 3" :key="i">
                            <SkeletonLoader
                                class="w-full h-[200px] sm:h-[250px]"
                            />
                        </swiper-slide>
                    </swiper>

                    <div
                        class="absolute bottom-0 left-0 w-full gap-3 hidden"
                        :class="
                            master.langDirection == 'rtl'
                                ? 'xl:flex justify-center items-center flex-row-reverse'
                                : 'xl:flex justify-center items-center'
                        "
                    >
                        <button
                            class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-neutral-100 transition-all duration-200 hover:bg-primary group"
                            @click="swiperPrevSlide"
                        >
                            <ChevronLeftIcon
                                class="w-6 h-6 text-slate-600 group-hover:text-white"
                            />
                        </button>
                        <button
                            class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-neutral-100 transition-all duration-200 hover:bg-primary group"
                            @click="swiperNextSlide"
                        >
                            <ChevronRightIcon
                                class="w-6 h-6 text-slate-600 group-hover:text-white"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </SectionContainer>

        <section class="container-4 space-y-2 lg:space-y-16 pb-4 lg:pb-16">
            <!-- banner parts  -->
            <div
                class="grid grid-cols-2 gap-2 lg:gap-8 grid-rows-[80px] sm2:grid-rows-[150px] md:grid-rows-[200px] lg:grid-rows-[288px]"
            >
                <a
                    v-if="master.offerBanners.length >= 4"
                    :href="master.offerBanners[4].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[4]?.offer_banner"
                        alt=""
                        class="rounded-lg lg:rounded-2xl w-full h-full"
                    />
                </a>
                <a
                    v-if="master.offerBanners.length >= 5"
                    :href="master.offerBanners[5].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[5]?.offer_banner"
                        alt=""
                        class="rounded-lg lg:rounded-2xl w-full h-full"
                    />
                </a>
            </div>

            <div>
                <CounterContainer />
            </div>
        </section>
    </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { ArrowRightIcon } from "@heroicons/vue/24/outline";
import SkeletonLoader from "../SkeletonLoader.vue";
import SectionContainer from "../SectionContainer.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

import { ref } from "vue";
import { useMaster } from "../../stores/MasterStore";
import CounterContainer from "../UltraMart/CounterContainer.vue";
import ShopCardTop from "../UltraMart/ShopCardTop.vue";

// store
const master = useMaster();

// slider module
const modules = [Navigation, Pagination, A11y, Autoplay];

const { shops, isLoading } = defineProps(["shops", "isLoading"]);

const swiperInstance = ref();

function onSwiper(swiper) {
    swiperInstance.value = swiper;
}

const swiperNextSlide = () => {
    swiperInstance.value.slideNext();
};
const swiperPrevSlide = () => {
    swiperInstance.value.slidePrev();
};

const breakpoints = {
    320: {
        slidesPerView: 1.2,
        spaceBetween: 10,
    },
    540: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 1.5,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 2,
        spaceBetween: 24,
    },
    1440: {
        slidesPerView: 2.2,
        spaceBetween: 24,
    },
};
</script>

<style>
.shops_swiper_ultramart {
    width: 100%;
}
.shops_swiper_ultramart .swiper-wrapper {
}
.shops_swiper_ultramart .swiper-button-prev,
.shops_swiper_ultramart .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.shops_swiper_ultramart .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.shops_swiper_ultramart .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

.shops_swiper_ultramart .swiper-button-prev:after,
.shops_swiper_ultramart .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.shops_swiper_ultramart .swiper-pagination-bullet {
    background: white;
    opacity: 1;
    @apply md:hidden;
}

.shops_swiper_ultramart .swiper-pagination-bullet-active {
    @apply bg-primary w-6 h-2 rounded-lg transition-all duration-200;
}
</style>
