<template>
    <div class="counter__wrapper">
        <div :dir="master.langDirection || 'ltr'">
            <swiper
                :breakpoints="breakpoints"
                :loop="false"
                ref="swiperRef"
                @swiper="onSwiper"
                :modules="modules"
                class="shops_swiper"
                :autoplay="{
                    delay: 4000,
                    disableOnInteraction: false,
                }"
            >
                <swiper-slide>
                    <div
                        class="w-full h-full p-3 bg-white rounded-lg backdrop-blur-blur inline-flex flex-col justify-start items-start gap-1"
                    >
                        <p
                            class="text-center text-zinc-900 text-xl font-bold font-['Inter'] leading-7 flex justify-start items-center"
                        >
                            <count-up :end-val="48"></count-up>
                            <span class="plus">+</span>
                        </p>
                        <p
                            class="text-start text-slate-500 text-sm font-normal font-['Inter'] leading-snug text-nowrap"
                        >
                            {{ $t("Secure Payment") }}
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full h-full p-3 bg-white rounded-lg backdrop-blur-blur inline-flex flex-col justify-start items-start gap-1"
                    >
                        <p
                            class="text-center justify-start text-zinc-900 text-xl font-bold font-['Inter'] leading-7 flex justify-start items-center"
                        >
                            <count-up :end-val="200"></count-up>
                            <span class="plus">+</span>
                        </p>
                        <p
                            class="text-start text-slate-500 text-sm font-normal font-['Inter'] leading-snug text-nowrap"
                        >
                            {{ $t("Trusted Vendors") }}
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full h-full p-3 bg-white rounded-lg backdrop-blur-blur inline-flex flex-col justify-start items-start gap-1"
                    >
                        <p
                            class="text-center justify-start text-zinc-900 text-xl font-bold font-['Inter'] leading-7 flex justify-start items-center"
                        >
                            <count-up :end-val="90"></count-up>
                            <span class="plus">%</span>
                        </p>
                        <p
                            class="text-start text-slate-500 text-sm font-normal font-['Inter'] leading-snug text-nowrap"
                        >
                            {{ $t("Positive Feedback") }}
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full h-full p-3 bg-white rounded-lg backdrop-blur-blur inline-flex flex-col justify-start items-start gap-1"
                    >
                        <p
                            class="text-center justify-start text-zinc-900 text-xl font-bold font-['Inter'] leading-7 flex justify-start items-center"
                        >
                            <count-up :end-val="24"></count-up>
                            <span class="plus">/</span>
                            <count-up :end-val="7"></count-up>
                        </p>
                        <p
                            class="text-start text-slate-500 text-sm font-normal font-['Inter'] leading-snug text-nowrap"
                        >
                            {{ $t("Online Support") }}
                        </p>
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { ref } from "vue";
import { useMaster } from "../../stores/MasterStore";
import CountUp from "vue-countup-v3";
import Support from "../../icons/Support.vue";
import Like from "../../icons/Like.vue";
import CertifiedIcon from "../../icons/CertifiedIcon.vue";
import PaymentIcon from "../../icons/PaymentIcon.vue";

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
        slidesPerView: 1.5,
        spaceBetween: 8,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 8,
    },
    1024: {
        slidesPerView: 3.2,
        spaceBetween: 8,
    },
    1280: {
        slidesPerView: 3.2,
        spaceBetween: 8,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 8,
    },
};
</script>

<style scoped>
/*********************************
/*  Counter Section Start
*********************************/
.counter__wrapper {
    border-radius: var(--radius-16);
}

.counterItem {
    position: relative;
    border: 1px solid #c9e9cc;
    padding: 24px;
    border-radius: var(--radius-12);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 16px;
}
@media screen and (max-width: 767px) {
    .counterItem {
        padding: 16px;
    }
}
.counterItem .icon {
    width: 64px;
    min-width: 64px;
    aspect-ratio: 1/1;
}
@media screen and (max-width: 767px) {
    .counterItem .icon {
        width: 48px;
        min-width: 48px;
    }
}
.counterItem .icon img {
    width: 100%;
    height: auto;
    display: block;
}
.counterItem__content {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
}
.counterItem__content .title {
    font-family: var(--font-inter);
    font-weight: var(--font-bold);
    font-size: 32px;
    line-height: 40px;
    color: var(--color-heading);
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    position: relative;
    z-index: 1;
    margin-bottom: 8px;
}
@media screen and (max-width: 767px) {
    .counterItem__content .title {
        font-size: 24px;
        line-height: 32px;
        margin-bottom: 4px;
    }
}
.counterItem__content .subtitle {
    font-family: var(--font-inter);
    font-size: 16px;
    line-height: 24px;
    font-weight: var(--font-regular);
    color: var(--color-content);
    display: block;
    position: relative;
    z-index: 1;
    white-space: nowrap;
}
@media screen and (max-width: 767px) {
    .counterItem__content .subtitle {
        font-size: 14px;
        line-height: 22px;
    }
}

/*********************************
/*  Counter Section End
*********************************/
</style>
