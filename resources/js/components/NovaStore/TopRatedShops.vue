
<template>
    <div>
        <section class="bg-primary-50">
            <SectionContainer :title="'Top Rated Shops'" :isLoading="isLoading">
                <template #button>
                    <div class="flex justify-center items-center gap-4">
                        <router-link
                            to="/shops"
                            class="text-primary text-base md:text-lg font-normal leading-normal"
                        >
                            {{ $t("View All") }}
                        </router-link>

                        <div
                            class="hidden gap-3"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'md:flex justify-center items-center flex-row-reverse'
                                    : 'md:flex justify-center items-center'
                            "
                        >
                            <button
                                class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-white transition-all duration-200 hover:bg-primary group"
                                @click="swiperPrevSlide"
                            >
                                <ChevronLeftIcon
                                    class="w-6 h-6 text-slate-600 group-hover:text-white"
                                />
                            </button>
                            <button
                                class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-white transition-all duration-200 hover:bg-primary group"
                                @click="swiperNextSlide"
                            >
                                <ChevronRightIcon
                                    class="w-6 h-6 text-slate-600 group-hover:text-white"
                                />
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Shops -->
                <div :dir="master.langDirection || 'ltr'">
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        :pagination="{ clickable: true }"
                        class="shops_swiper"
                        :autoplay="{
                            delay: 2000,
                            disableOnInteraction: false,
                        }"
                    >
                        <swiper-slide
                            v-if="!isLoading"
                            v-for="shop in shops"
                            :key="shop.id"
                            class="mb-11 md:mb-0"
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
                </div>

                <div class="hidden lg:block mt-[35px]">
                    <CounterContainer />
                </div>
            </SectionContainer>
            
                <div class="block lg:hidden pb-[18px] lg:pb-[35px] bg-white">
                    <CounterContainer />
                </div>
        </section>
    </div>
</template>



<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import ShopCardTop from "../NovaStore/ShopCardTop.vue";
import SkeletonLoader from "../SkeletonLoader.vue";
import SectionContainer from "../SectionContainer.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

import { ref } from "vue";
import { useMaster } from "../../stores/MasterStore";
import CounterContainer from "../NovaStore/CounterContainer.vue";

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
        slidesPerView: 1,
        spaceBetween: 10,
    },
    540: {
        slidesPerView: 1.5,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 1.5,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 3,
        spaceBetween: 24,
    },
};
</script>



<style>
.shops_swiper .swiper-button-prev,
.shops_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.shops_swiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.shops_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

.shops_swiper .swiper-button-prev:after,
.shops_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.shops_swiper .swiper-pagination-bullet {
    background: white;
    opacity: 1;
    @apply md:hidden;
}

.shops_swiper .swiper-pagination-bullet-active {
    @apply bg-primary w-6 h-2 rounded-lg transition-all duration-200;
}
</style>
