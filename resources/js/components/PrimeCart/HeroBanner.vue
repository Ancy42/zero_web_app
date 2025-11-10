<template>
    <div class="bg-zinc-900 relative overflow-hidden">
        <section
            v-if="!isLoading"
            class="container-4 grid grid-cols-3 gap-3 md:gap-6 relative z-10 py-8"
        >
            <div class="col-span-3 xl:col-span-2">
                <!-- Main Banner Slider -->
                <swiper
                    :pagination="{ clickable: true }"
                    :slides-per-view="1"
                    :space-between="20"
                    :modules="modules"
                    class="mySwiperV2 rounded-lg"
                    effect="fade"
                    :loop="false"
                    :autoplay="{
                        delay: 4000,
                        disableOnInteraction: false,
                    }"
                    @swiper="appendingChildsToPaginationBullets"
                >
                    <swiper-slide
                        v-for="banner in banners"
                        :key="banner.id"
                        class="mb-2 lg:mb-0"
                    >
                        <img
                            :src="banner.thumbnail"
                            loading="lazy"
                            class="w-full rounded-xl object-cover aspect-[10/4.5]"
                        />
                    </swiper-slide>
                </swiper>
            </div>

            <a :href="master.offerBanners[0].link" target="_blank">
                <img
                    :src="master.offerBanners[0].offer_banner"
                    alt=""
                    class="w-full h-full  rounded-xl"
                />
            </a>
            <a :href="master.offerBanners[1].link" target="_blank">
                <img
                    :src="master.offerBanners[1].offer_banner"
                    alt=""
                    class="w-full h-full  rounded-xl"
                />
            </a>
            <a :href="master.offerBanners[2].link" target="_blank">
                <img
                    :src="master.offerBanners[2].offer_banner"
                    alt=""
                    class="w-full h-full  rounded-xl"
                />
            </a>
            <div class="col-span-3  xl:col-span-1">
                <CounterContainerV2 />
            </div>
        </section>

        <section
            v-else
            class="container-4 grid grid-cols-3 gap-3 md:gap-6 relative z-10 py-8"
        >
            <div class="col-span-3 lg:col-span-2">
                <SkeletonLoader
                    class="w-full h-full object-cover rounded-lg aspect-[10/4.5]"
                />
            </div>

            <div>
                <SkeletonLoader
                    class="w-full h-full object-cover rounded-lg aspect-[10/2]"
                />
            </div>
            <div>
                <SkeletonLoader
                    class="w-full h-full object-cover rounded-lg aspect-[10/2]"
                />
            </div>
            <div>
                <SkeletonLoader
                    class="w-full h-full object-cover rounded-lg aspect-[10/2]"
                />
            </div>

            <div class="col-span-3 lg:col-span-1">
                <SkeletonLoader
                    class="w-full h-full object-cover rounded-lg aspect-[10/2]"
                />
            </div>
        </section>

        <!-- glowing -->
        <div
            class="w-[200px] h-[2200px] left-0 top-0 absolute rotate-[-65deg] bg-primary-500 origin-top-left blur-[200px] z-0"
        ></div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import {
    Navigation,
    Pagination,
    A11y,
    Autoplay,
    EffectFade,
} from "swiper/modules";
import { useMaster } from "../../stores/MasterStore";
import SkeletonLoader from "../SkeletonLoader.vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/effect-fade";
import "swiper/css/navigation";
import "swiper/css/pagination";
import CounterContainerV2 from "../CounterContainerV2.vue";
import { onMounted } from "vue";
import { ArrowLeftIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    banners: Array,
    ads: Array,
    isLoading: {
        type: Boolean,
        default: true,
    },
});

const modules = [Navigation, Pagination, A11y, Autoplay, EffectFade];

const master = useMaster();

function appendingChildsToPaginationBullets(swiper) {
    const pagination = swiper.pagination.el;
    console.log("Pagination container:", pagination);

    // bullets manupulation
    // Add Tailwind classes
    pagination.classList.add("flex", "justify-end", "items-center");

    // Create Prev button
    const prevBtn = document.createElement("div");
    prevBtn.className = "swiper-button-prev-2";
    prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 fill-black">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        `;
    prevBtn.addEventListener("click", () => swiper.slidePrev());

    // Create Next button
    const nextBtn = document.createElement("div");
    nextBtn.className = "swiper-button-next-2";
    nextBtn.innerText = "Next"; // optional label or icon
    nextBtn.addEventListener("click", () => swiper.slideNext());

    nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                            `;

    // Insert into pagination container
    pagination.prepend(prevBtn); // as first child
    pagination.append(nextBtn); // as last child
}
</script>

<style>
.swiper-button-prev-2 {
    width: 32px;
    height: 32px;
    background-color: #fff;
    color: black;
    border-radius: 8px !important;
    margin-right: 24px;
    @apply hidden md:flex justify-center items-center cursor-pointer;
}

.swiper-button-next-2 {
    width: 32px;
    height: 32px;
    background-color: #fff;
    color: black;
    border-radius: 8px !important;
    margin-left: 24px;
    @apply hidden md:flex justify-center items-center cursor-pointer;
}

/* .mySwiperV2 .swiper-button-prev,
.mySwiperV2 .swiper-button-next {
    position: absolute;
    width: 32px;
    height: 32px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
    @apply hidden md:flex;
}

.mySwiperV2 .swiper-button-next {
    left: auto;
    right: 10px; 
    bottom: 20px;
}

.mySwiperV2 .swiper-button-prev {
    left: auto;
    right: 110px; 
    bottom: 20px;
}

[dir="ltr"] .mySwiperV2 .swiper-button-prev {
    left: auto;
    right: 110px;
}

[dir="ltr"] .mySwiperV2 .swiper-button-next {
    left: auto;
    right: 10px;
}

[dir="rtl"] .mySwiperV2 .swiper-button-prev {
    right: auto;
    left: 110px;
}

[dir="rtl"] .mySwiperV2 .swiper-button-next {
    right: auto;
    left: 10px;
}

.mySwiperV2 .swiper-button-prev:after,
.mySwiperV2 .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
} */

.mySwiperV2 .swiper-pagination {
    @apply bottom-2  md:bottom-[25px]  text-center md:text-right pb-2 pr-[10px] md:pr-[60px];
}

[dir="rtl"] .mySwiperV2 .swiper-pagination {
    text-align: left;
    padding-right: 0;
    padding-left: 60px;
}

.mySwiperV2 .swiper-pagination-bullet {
    opacity: 1;
    @apply w-3 h-3 bg-white border border-primary rounded-lg transition-all duration-200;
}

.mySwiperV2 .swiper-pagination-bullet-active {
    @apply bg-green-500 w-3 h-3 rounded-lg transition-all duration-200;
}
</style>
