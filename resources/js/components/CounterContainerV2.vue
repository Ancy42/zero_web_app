<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 bg-white/10 rounded-lg">
        <div class="p-3 space-y-3 bg-white/10 rounded">
            <div class="flex justify-start items-end gap-2">
                <PaymentIcon colorClass="fill-white" width="40" height="40" />
                <p
                    class="text-white text-2xl font-bold font-['Inter'] leading-loose flex justify-start items-center"
                >
                    <count-up :end-val="48"></count-up>
                    <span>+</span>
                </p>
            </div>
            <p
                class="text-start text-white/50 text-sm font-normal font-['Inter'] leading-snug"
            >
                {{ $t("Secure Payment Gateways") }}
            </p>
        </div>

        
        <div class="p-3 space-y-3 bg-white/10 rounded">
            <div class="flex justify-start items-end gap-2">
                <CertifiedIcon colorClass="fill-white" width="40" height="40" />
                <p
                    class="text-white text-2xl font-bold font-['Inter'] leading-loose flex justify-start items-center"
                >
                    <count-up :end-val="200"></count-up>
                    <span>+</span>
                </p>
            </div>
            <p
                class="text-start text-white/50 text-sm font-normal font-['Inter'] leading-snug"
            >
                {{ $t("Verified & Trusted Vendors") }}
            </p>
        </div>

        
        <div class="p-3 space-y-3 bg-white/10 rounded">
            <div class="flex justify-start items-end gap-2">
                <LikeIcon colorClass="fill-white" width="40" height="40" />
                <p
                    class="text-white text-2xl font-bold font-['Inter'] leading-loose flex justify-start items-center"
                >
                    <count-up :end-val="90"></count-up>
                    <span>%</span>
                </p>
            </div>
            <p
                class="text-start text-white/50 text-sm font-normal font-['Inter'] leading-snug"
            >
                {{ $t("User Positive Feedback") }}
            </p>
        </div>

        
        <div class="p-3 space-y-3 bg-white/10 rounded">
            <div class="flex justify-start items-end gap-2">
                <SupportIcon colorClass="fill-white" width="40" height="40" />
                <p
                    class="text-white text-2xl font-bold font-['Inter'] leading-loose flex justify-start items-center"
                >
                    <count-up :end-val="48"></count-up>
                    <span>+</span>
                </p>
            </div>
            <p
                class="text-start text-white/50 text-sm font-normal font-['Inter'] leading-snug"
            >
                {{ $t("Online Customer Support") }}
            </p>
        </div>
    </div>

    <!-- <div class="counter__wrapper">
        <div :dir="master.langDirection || 'ltr'">
            <swiper
                :breakpoints="breakpoints"
                :loop="true"
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
                        class="w-full p-4 bg-primary-50 rounded-xl inline-flex flex-col justify-center items-start gap-3"
                    >
                        <div
                            class="inline-flex justify-start items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 relative overflow-hidden"
                            >
                                <img
                                    src="../../../public/assets/images/counter-icon/payment-gate.svg"
                                    alt="icon"
                                    class="w-full h-full"
                                />
                            </div>
                            <p
                                class="text-center justify-start text-primary-800 text-xl md:text-3xl font-bold leading-7 md:leading-10"
                            >
                                48+
                            </p>
                        </div>
                        <p
                            class="text-center justify-start text-zinc-900 text-xs md:text-base font-normal leading-tight md:leading-normal"
                        >
                            {{ $t("Secure Payment Gateways") }}
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full p-4 bg-primary-50 rounded-xl inline-flex flex-col justify-center items-start gap-3"
                    >
                        <div
                            class="inline-flex justify-start items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 relative overflow-hidden"
                            >
                                <img
                                    src="../../../public/assets/images/counter-icon/return.svg"
                                    alt="icon"
                                    class="w-full h-full"
                                />
                            </div>
                            <p
                                class="text-center justify-start text-primary-800 text-xl md:text-3xl font-bold leading-7 md:leading-10"
                            >
                                30 Days
                            </p>
                        </div>
                        <p
                            class="text-center justify-start text-zinc-900 text-xs md:text-base font-normal leading-tight md:leading-normal"
                        >
                            {{ $t("For Free Return") }}
                            
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full p-4 bg-primary-50 rounded-xl inline-flex flex-col justify-center items-start gap-3"
                    >
                        <div
                            class="inline-flex justify-start items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 relative overflow-hidden"
                            >
                                <img
                                    src="../../../public/assets/images/counter-icon/varified.svg"
                                    alt="icon"
                                    class="w-full h-full"
                                />
                            </div>
                            <p
                                class="text-center justify-start text-primary-800 text-xl md:text-3xl font-bold leading-7 md:leading-10"
                            >
                                200+
                            </p>
                        </div>
                        <p
                            class="text-center justify-start text-zinc-900 text-xs md:text-base font-normal leading-tight md:leading-normal"
                        >
                            {{ $t("Verified & Trusted Vendors") }}
                            
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full p-4 bg-primary-50 rounded-xl inline-flex flex-col justify-center items-start gap-3"
                    >
                        <div
                            class="inline-flex justify-start items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 relative overflow-hidden"
                            >
                                <img
                                    src="../../../public/assets/images/counter-icon/response.svg"
                                    alt="icon"
                                    class="w-full h-full"
                                />
                            </div>
                            <p
                                class="text-center justify-start text-primary-800 text-xl md:text-3xl font-bold leading-7 md:leading-10"
                            >
                                90%
                            </p>
                        </div>
                        <p
                            class="text-center justify-start text-zinc-900 text-xs md:text-base font-normal leading-tight md:leading-normal"
                        >
                            {{ $t("User Positive Feedback") }}
                            
                        </p>
                    </div>
                </swiper-slide>

                <swiper-slide>
                    <div
                        class="w-full p-4 bg-primary-50 rounded-xl inline-flex flex-col justify-center items-start gap-3"
                    >
                        <div
                            class="inline-flex justify-start items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 relative overflow-hidden"
                            >
                                <img
                                    src="../../../public/assets/images/counter-icon/support.svg"
                                    alt="icon"
                                    class="w-full h-full"
                                />
                            </div>
                            <p
                                class="text-center justify-start text-primary-800 text-xl md:text-3xl font-bold leading-7 md:leading-10"
                            >
                                24/7
                            </p>
                        </div>
                        <p
                            class="text-center justify-start text-zinc-900 text-xs md:text-base font-normal leading-tight md:leading-normal"
                        >
                            {{ $t("Online Support") }}
                            
                        </p>
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div> -->
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { ref } from "vue";
import { useMaster } from "../stores/MasterStore";
import CountUp from "vue-countup-v3";
import PaymentIcon from "../icons/PaymentIcon.vue";
import CertifiedIcon from "../icons/CertifiedIcon.vue";
import LikeIcon from "../icons/Like.vue";
import SupportIcon from "../icons/Support.vue";

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
        slidesPerView: 1.3,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 3,
        spaceBetween: 20,
    },
    1440: {
        slidesPerView: 5,
        spaceBetween: 20,
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
