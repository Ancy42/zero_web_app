<template>
    <SectionContainer2 :title="'Incoming Flash Sale'" :isLoading="false">
        <template #counter>
            <div
                class="w-full h-auto md:h-[284px] rounded-2xl overflow-hidden bg-no-repeat bg-cover bg-bottom flex flex-col items-center justify-center gap-2 md:gap-[10px] p-3 md:p-6"
                style="
                    background-image: url('/assets/images/homepage/flash-sale.png');
                "
            >
                <div class="w-36 h-14 md:w-52 md:h-20 lg:w-80 lg:h-32">
                    <img
                        src="../../../../public/assets/icons/flash-sale-logo.svg"
                        alt=""
                        class="w-full h-full object-contain"
                    />
                </div>

                <div class="w-full flex justify-center items-center">
                    <TimeCounter :end_date="flashSale?.end_date" />
                </div>
            </div>
        </template>
        <template #button>
            <div class="flex justify-center items-center gap-4">
                <router-link
                    :to="'/flash-sale/' + flashSale?.id" 
                    class="text-primary text-base md:text-lg font-normal leading-normal"
                >
                    {{ $t("View All") }}
                </router-link>
            </div>
        </template>

        <section :dir="master.langDirection || 'ltr'">
            <!-- Flash Sale Products -->
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
                >
                    <ProductCard :product="product" />
                </swiper-slide>
            </swiper>
        </section>
    </SectionContainer2>
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
import ProductCard from "../UltraMart/ProductCard.vue";
import SectionContainer2 from "../SectionContainer2.vue";
import TimeCounter from "../UltraMart/TimeCounter.vue";

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
        slidesPerView: 1.6,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 2.5,
        spaceBetween: 15,
    },
    1024: {
        slidesPerView: 3.5,
        spaceBetween: 15,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 32,
    },
};
</script>
