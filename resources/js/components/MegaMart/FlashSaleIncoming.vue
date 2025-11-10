<template>
    <div class="py-16 bg-neutral-100">
        <div class="container-2 px-4 2xl:px-0">
            <div class="w-full h-auto flex justify-start items-center gap-6">
                <p
                    class="text-black text-xl md:text-3xl font-semibold font-['Lato'] leading-7 md:leading-10 flex justify-start items-center gap-1"
                >
                    {{ $t("Incoming Flash Sale") }}
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
                    class="grid grid-cols-1 sm2:grid-cols-2 md:grid-cols-2 lg:md:grid-cols-4 gap-6"
                >
                    <FlashSaleProductCard
                        v-for="product in flashSale.products"
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
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
import { useMaster } from "../../stores/MasterStore";
import FlashSaleProductCard from "../MegaMart/FlashSaleProductCard.vue";
import ViewAllBtn from "../ViewAllBtn.vue";
import TimeCounterV4 from "../TimeCounterV4.vue";

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
