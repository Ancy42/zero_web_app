<template>
    <div class="py-10 bg-neutral-100">
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
                    <ViewAllBtn :link="'/flash-sale/' + flashSale?.id" />
                </div>
            </div>

            <div class="w-full block lg:hidden my-4">
                <TimeCounterV4 :end_date="flashSale?.end_date" />
            </div>

            <div class="mt-6" :dir="master.langDirection || 'ltr'">
                <!-- Flash Sale Products -->
                <!-- <div
                    :dir="master.langDirection || 'ltr'"
                    class="grid grid-cols-1 md:grid-cols-2 lg:md:grid-cols-4 gap-6"
                >
                    <FlashSaleProductCard
                        v-for="product in flashSaleProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div> -->


                <!-- slider  -->
                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    :autoplay="{
                        delay: 4000,
                        disableOnInteraction: false,
                    }"
                >
                    <swiper-slide
                        v-for="product in flashSale.products"
                        :key="product.id"
                        class="py-2"
                    >
                        <FlashSaleProductCard :product="product" />
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useMaster } from "../../stores/MasterStore";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
import TimeCounterV4 from "../TimeCounterV4.vue";
import FlashSaleProductCard from "../MegaMart/FlashSaleProductCard.vue"
import ViewAllBtn from "../ViewAllBtn.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";


const props = defineProps({
    flashSale: Object,
});

const master = useMaster();

// slider modules
const modules = [Navigation, Pagination, A11y, Autoplay];
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
        spaceBetween: 12,
    },
    530: {
        slidesPerView: 2.3,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 15,
    },
    1024: {
        slidesPerView: 3.5,
        spaceBetween: 15,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
};


</script>

<style>
.running_flash_sale_swiper .swiper-button-prev,
.running_flash_sale_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.running_flash_sale_swiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.running_flash_sale_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

.running_flash_sale_swiper .swiper-button-prev:after,
.running_flash_sale_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.running_flash_sale_swiper .swiper-pagination-bullet {
    margin-top: 28px;
    background: white;

    opacity: 1;
    @apply md:hidden border border-gray-200;
}

.running_flash_sale_swiper .swiper-pagination-bullet-active {
    @apply bg-primary w-6 h-2 rounded-lg transition-all duration-200 border-primary;
}
</style>
