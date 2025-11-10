<template>
    <div class="">
        <div class="container-2 px-4 2xl:px-0">
            <div class="w-full h-auto flex justify-start items-center gap-6">
                <p
                    class="text-black text-xl md:text-3xl font-semibold font-['Lato'] leading-7 md:leading-10 flex justify-start items-center gap-1"
                >
                    {{ flashSale?.name }}
                    <img
                        src="../../../../public/assets/icons/fire.svg"
                        alt=""
                        class="w-8 h-8 hidden md:block"
                    />
                </p>

                <div class="w-auto hidden lg:block">
                    <TimeCounterV4 :end_date="flashSale?.end_date" />
                </div>

                <div class="ml-auto">
                    <ViewAllBtn2 :link="'/flash-sale/' + flashSale?.id" />
                </div>
            </div>

            <div class="w-full block lg:hidden my-4">
                <TimeCounterV4 :end_date="flashSale?.end_date" />
            </div>

            <div class="mt-6">
                <!-- Flash Sale Products -->
                <div
                    :dir="master.langDirection || 'ltr'"
                    class="hidden xl:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <FlashSaleProductCard
                        v-for="product in flashSale.products"
                        :key="product.id"
                        :product="product"
                    />
                </div>

                <div
                    :dir="master.langDirection || 'ltr'"
                    class="block xl:hidden"
                >
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        class="flash_sale_swiper_next_mart common_bullet"
                        :pagination="{ clickable: true }"
                    >
                        <swiper-slide
                            v-for="product in flashSale.products"
                            :key="product.id"
                            class="mb-8 md:mb-10"
                        >
                            <FlashSaleProductCard :product="product" />
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useMaster } from "../../stores/MasterStore";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
import TimeCounterV4 from "../TimeCounterV4.vue";
import ViewAllBtn2 from "../ViewAllBtn2.vue";
import FlashSaleProductCard from "../NextMart/FlashSaleProductCard.vue";

const props = defineProps({
    flashSale: Object,
});

const master = useMaster();

// variables
const swiperInstance = ref();

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
        slidesPerView: 1,
        spaceBetween: 12,
    },
    540: {
        slidesPerView: 1.3,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 1.5,
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
.flash_sale_swiper_next_mart {
    @apply p-1;
}

.flash_sale_swiper_next_mart .swiper-button-prev,
.flash_sale_swiper_next_mart .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.flash_sale_swiper_next_mart .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.flash_sale_swiper_next_mart .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

[dir="ltr"] .flash_sale_swiper_next_mart .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .flash_sale_swiper_next_mart .swiper-button-next {
    left: auto;
    right: 20px;
}

.flash_sale_swiper_next_mart .swiper-button-prev:after,
.flash_sale_swiper_next_mart .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}
</style>
