<template>
    <div class="">
        <div class="container-3">
            <div
                class="w-full h-auto bg-zinc-900 rounded-xl flex flex-col sm:flex-row justify-between items-start md:items-center gap-3 z-[4] p-6"
            >
                <p
                    class="text-white text-xl md:text-3xl font-semibold font-['Lato'] leading-7 md:leading-10 flex justify-start items-center gap-1"
                >
                    {{ $t("Incoming Flash Sale") }}
                    <img
                        src="../../../../public/assets/icons/fire.svg"
                        alt=""
                        class="w-8 h-8"
                    />
                </p>
                <div class="w-full lg:w-auto">
                    <TimeCounter :end_date="flashSale?.end_date" />
                </div>
            </div>

            <div class="mt-6" :dir="master.langDirection || 'ltr'">
                <!-- Flash Sale Products -->

                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    class="incoming_flash_sale_swiper green_bullet"
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
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
import TimeCounter from "../TimeCounter.vue";
import { useMaster } from "../../stores/MasterStore";
import FlashSaleProductCard from "../NovaStore/FlashSaleProductCard.vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

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

// slider module
const modules = [Navigation, Pagination, A11y, Autoplay];

const swiperInstance = ref();

function onSwiper(swiper) {
    swiperInstance.value = swiper;
}

const swiperNextSlide = () => {
    swiperInstance.value.slideNext();
};

const breakpoints = {
    320: {
        slidesPerView: 1,
        spaceBetween: 8,
    },
    500: {
        slidesPerView: 1.5,
        spaceBetween: 12,
    },
    712: {
        slidesPerView: 2,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 2.2,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 3,
        spaceBetween: 20,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
};

// const flashSaleProducts = computed(() => {
//     if (!props.flashSale?.products?.length) {
//         return [];
//     }

//     if (viewportWidth.value <= 320) {
//         return props.flashSale.products.slice(0, 1);
//     }

//     if (viewportWidth.value <= 780) {
//         return props.flashSale.products.slice(0, 2);
//     }
//     if (viewportWidth.value <= 820) {
//         return props.flashSale.products.slice(0, 2);
//     }
//     if (viewportWidth.value <= 853) {
//         return props.flashSale.products.slice(0, 2);
//     }
//     if (viewportWidth.value <= 1024) {
//         return props.flashSale.products.slice(0, 3);
//     }
//     if (viewportWidth.value >= 1024) {
//         return props.flashSale.products.slice(0, 4);
//     }

//     return props.flashSale.products;
// });
</script>

<style scoped>
.incoming_flash_sale_swiper {
    @apply p-1;
}
.incoming_flash_sale_swiper .swiper-button-prev,
.incoming_flash_sale_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.incoming_flash_sale_swiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.incoming_flash_sale_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

[dir="ltr"] .incoming_flash_sale_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .incoming_flash_sale_swiper .swiper-button-next {
    left: auto;
    right: 20px;
}

.incoming_flash_sale_swiper .swiper-button-prev:after,
.incoming_flash_sale_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}
</style>
