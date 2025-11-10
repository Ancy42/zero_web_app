<template>
    <div class="">
        <div
            class="container-2 px-4 2xl:px-0 grid grid-cols-12 gap-6 xl:grid-rows-[564px]"
        >
            <div class="hidden xl:block xl:col-span-4">
                <img
                    :src="flashSale?.thumbnail"
                    class="h-full w-full object-cover overflow-hidden rounded-xl"
                    :alt="flashSale?.name"
                />
            </div>

            <div class="col-span-12 xl:col-span-8 flex flex-col justify-center">
                <div
                    class="text-zinc-900 text-xl md:text-3xl font-semibold font-['Montserrat'] leading-7 md:leading-10 flex justify-start items-center gap-1"
                >
                    {{ flashSale?.name }}

                    <img
                        src="../../../../public/assets/icons/fire.svg"
                        alt=""
                        class="w-8 h-8 hidden md:block"
                    />

                    <div class="ml-auto block md:hidden">
                        <ViewAllBtn2
                            :link="'/flash-sale/' + flashSale?.id"
                            :textColor="'text-black'"
                        />
                    </div>
                </div>

                <div class="w-full flex justify-center items-center my-6">
                    <div class="md:min-w-[450px]">
                        <TimeCounterV2 :end_date="flashSale?.end_date" />
                    </div>

                    <div class="ml-auto hidden md:block">
                        <ViewAllBtn2
                            :link="'/flash-sale/' + flashSale?.id"
                            :textColor="'text-black'"
                        />
                    </div>
                </div>

                <div :dir="master.langDirection || 'ltr'">
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        class="running_flash_sale_swiper_V2 green_bullet_lg"
                        :autoplay="{
                            delay: 4000,
                            disableOnInteraction: false,
                        }"
                        :navigation="{
                            nextEl: '.custom-next',
                            prevEl: '.custom-prev',
                        }"
                        :pagination="{ clickable: true }"
                    >
                        <swiper-slide
                            v-for="product in flashSale.products"
                            :key="product.id"
                            class="mb-8 md:mb-10"
                        >
                            <ProductCard :product="product" />
                        </swiper-slide>

                        <!-- custom Navigation buttons -->
                        <div
                            class="custom-prev absolute top-[50%] left-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-4 h-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </div>
                        <div
                            class="custom-next absolute top-[50%] right-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-4 h-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </div>
                    </swiper>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

import { ArrowRightIcon } from "@heroicons/vue/24/outline";
import { useMaster } from "../../stores/MasterStore";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
import TimeCounterV2 from "./TimeCounter.vue";
import ViewAllBtn2 from "../ViewAllBtn2.vue";
import ProductCard from "../PrimeCart/ProductCard.vue";

const props = defineProps({
    flashSale: Object,
});

const master = useMaster();
const {
    scrollY,
    viewportHeight,
    documentHeight,
    scrollPercentage,
    viewportWidth,
} = useScroll();

// variables
const swiperInstance = ref();
const flashSaleProducts = computed(() => {
    return props.flashSale.products; // return all for larger screens
});

// slider modules
const modules = [Navigation, Pagination, A11y, Autoplay];

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
        slidesPerView: 1.1,
        spaceBetween: 12,
    },
    540: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 2.3,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2.2,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 2.5,
        spaceBetween: 15,
    },
    1440: {
        slidesPerView: 3,
        spaceBetween: 24,
    },
};
</script>

<style>
/* running_flash_sale_swiper_V2 */

.running_flash_sale_swiper_V2 {
    @apply p-1;
}

.running_flash_sale_swiper_V2 .swiper-button-prev,
.running_flash_sale_swiper_V2 .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.running_flash_sale_swiper_V2 .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.running_flash_sale_swiper_V2 .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

[dir="ltr"] .running_flash_sale_swiper_V2 .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .running_flash_sale_swiper_V2 .swiper-button-next {
    left: auto;
    right: 20px;
}

.running_flash_sale_swiper_V2 .swiper-button-prev:after,
.running_flash_sale_swiper_V2 .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}
</style>
