<template>
    <div>
        <div class="container-3">
            <div class="w-full h-auto bg-zinc-900 rounded-xl mb-6">
                <div
                    class="flex flex-col lg:flex-row justify-between items-center w-full gap-3 z-[4] p-4 md:p-6"
                >
                    <div class="flex justify-between items-center w-full lg:w-auto">
                        <p
                            class="text-white text-xl md:text-3xl font-semibold font-['Lato'] leading-7 md:leading-10 flex justify-start items-center gap-1"
                        >
                            {{ flashSale?.name }}
                            <img
                                src="../../../../public/assets/icons/fire.svg"
                                alt=""
                                class="w-8 h-8"
                            />
                        </p>

                        <router-link
                            :to="'/flash-sale/' + flashSale?.id"
                            class="flex lg:hidden items-center gap-1"
                        >
                            <div
                                class="text-primary text-base font-normal font-['Lato'] leading-normal"
                            >
                                {{ $t("View All") }}
                            </div>
                        </router-link>
                    </div>

                    <div class="w-full lg:w-fit">
                        <TimeCounter :end_date="flashSale?.end_date" />
                    </div>

                    <div class="hidden lg:flex justify-end items-center gap-4">
                        <router-link
                            :to="'/flash-sale/' + flashSale?.id"
                            class="flex items-center gap-1"
                        >
                            <div
                                class="text-primary text-xl font-normal font-['Lato'] leading-7"
                            >
                                {{ $t("View All") }}
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="" :dir="master.langDirection || 'ltr'">
                <!-- Flash Sale Products -->

                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    class="running_flash_sale_swiper green_bullet"
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
import FlashSaleProductCard from "../NovaStore/FlashSaleProductCard.vue";
import { useMaster } from "../../stores/MasterStore";
import TimeCounter from "../TimeCounter.vue";
import { computed } from "vue";
import { useScroll } from "../../composables/useScroll";
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
        slidesPerView: 1.1,
        spaceBetween: 8,
    },
    500: {
        slidesPerView: 1.5,
        spaceBetween: 12,
    },
    712: {
        slidesPerView: 2.2,
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
//         return props.flashSale.products.slice(0, 1); // first 2 items
//     }

//     if (viewportWidth.value <= 780) {
//         return props.flashSale.products.slice(0, 2); // first 2 items
//     }
//     if (viewportWidth.value <= 820) {
//         return props.flashSale.products.slice(0, 2); // first 2 items
//     }
//     if (viewportWidth.value <= 853) {
//         return props.flashSale.products.slice(0, 2); // first 2 items
//     }
//     if (viewportWidth.value <= 1024) {
//         return props.flashSale.products.slice(0, 3); // first 3 items
//     }
//     if (viewportWidth.value >= 1024) {
//         return props.flashSale.products.slice(0, 4); // first 3 items
//     }

//     return props.flashSale.products; // return all for larger screens
// });
</script>



<style scoped>
.running_flash_sale_swiper {
    @apply p-1;
}
.running_flash_sale_swiper .swiper-button-prev,
.running_flash_sale_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
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

[dir="ltr"] .running_flash_sale_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .running_flash_sale_swiper .swiper-button-next {
    left: auto;
    right: 20px;
}

.running_flash_sale_swiper .swiper-button-prev:after,
.running_flash_sale_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}
</style>
