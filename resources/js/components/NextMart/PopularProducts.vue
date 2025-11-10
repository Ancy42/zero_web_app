<template>
    <section class="bg-neutral-100 px-4 2xl:px-0">
        <SectionContainerV5 :title="'Popular Products'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <ViewAllBtn2 link="/most-popular" />

                    <div
                        class="hidden gap-3"
                        :class="
                            master.langDirection == 'rtl'
                                ? 'md:flex justify-center items-center flex-row-reverse'
                                : 'md:flex justify-center items-center'
                        "
                    >
                        <button
                            class="w-8 h-8 p-2 bg-white rounded-lg justify-center items-center gap-2 flex transition-all duration-200 hover:bg-primary group"
                            @click="swiperPrevSlide"
                        >
                            <ChevronLeftIcon
                                class="w-4 h-4 text-slate-600 group-hover:text-white"
                            />
                        </button>
                        <button
                            class="w-8 h-8 p-2 bg-white rounded-lg justify-center items-center gap-2 flex transition-all duration-200 hover:bg-primary group"
                            @click="swiperNextSlide"
                        >
                            <ChevronRightIcon
                                class="w-4 h-4 text-slate-600 group-hover:text-white"
                            />
                        </button>
                    </div>
                </div>
            </template>
            <template #tabs>
                <div class="pt-4">
                    <!-- <section
                        v-if="!isLoading"
                        class="flex justify-start gap-6 flex-nowrap overflow-x-auto scrollbar-hide"
                    >
                        <button
                            class="capitalize tab_links"
                            :class="
                                currentTab == 'all' ? 'tab_link_active' : ''
                            "
                            @click="getProductsByCategory()"
                        >
                            all
                        </button>
                        <button
                            class="capitalize tab_links"
                            :class="
                                tab.id == currentTab ? 'tab_link_active' : ''
                            "
                            @click="getProductsByCategory(tab.id)"
                            v-for="tab in tabs"
                            :key="tab.id"
                        >
                            {{ tab.name }}
                        </button>
                    </section> -->


                <section v-if="!isLoading">
                    <swiper
                        :slides-per-view="'auto'"
                        :space-between="12"
                        :free-mode="true"
                        :mousewheel="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        :breakpoints="tabBreakpoints"
                        class=""
                    >
                        <swiper-slide :key="0" class="!w-auto">
                            <button
                                class="capitalize tab_links"
                                :class="
                                    currentTab == 'all' ? 'tab_link_active' : ''
                                "
                                @click="getProductsByCategory()"
                            >
                                all
                            </button>
                        </swiper-slide>

                        <swiper-slide
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="!w-auto"
                        >
                            <button
                                class="capitalize tab_links"
                                :class="
                                    tab.id == currentTab
                                        ? 'tab_link_active'
                                        : ''
                                "
                                @click="getProductsByCategory(tab.id)"
                            >
                                {{ tab.name }}
                            </button>
                        </swiper-slide>
                    </swiper>
                </section>
                    <section
                        v-else
                        class="flex justify-start gap-6 flex-nowrap overflow-x-auto scrollbar-hide"
                    >
                        <div v-for="i in 6" :key="i">
                            <SkeletonLoader class="w-[100px] h-[50px]" />
                        </div>
                    </section>
                </div>
            </template>

            <div>
                <!-- slider  -->
                <section :dir="master.langDirection || 'ltr'" class="">
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        :pagination="{ clickable: true }"
                        class="popular_swiper green_bullet"
                        :autoplay="{
                            delay: 4000,
                            disableOnInteraction: false,
                        }"
                    >
                        <swiper-slide
                            v-if="!isCategoryProductLoading"
                            v-for="product in products"
                            :key="product.id"
                        >
                            <div class="mb-8 mt-1 w-full">
                                <ProductCard :product="product" />
                            </div>
                        </swiper-slide>

                        <!-- loading -->
                        <swiper-slide v-else v-for="i in 8" :key="i">
                            <SkeletonLoader
                                class="w-full h-[250px] rounded-lg"
                            />
                        </swiper-slide>
                    </swiper>
                </section>
            </div>
        </SectionContainerV5>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import { useAuth } from "../../stores/AuthStore";
import { useMaster } from "../../stores/MasterStore";

import "swiper/css";
import SectionContainerV5 from "../SectionContainerV5.vue";
import ProductCard from "./ProductCard.vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/solid";
import ViewAllBtn2 from "../ViewAllBtn2.vue";

const props = defineProps({
    tabs: {
        type: Array,
        default: [],
    },
});

// apis
const popularProductsApi = "/popular-products";

// store
const authStore = useAuth();
const master = useMaster();

// variables
const products = ref([]);
const currentTab = ref("");

const isLoading = ref(true);
const isCategoryProductLoading = ref(true);

// slider module
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
        spaceBetween: 8,
    },
    400: {
        slidesPerView: 1.5,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 2.5,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2.5,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
};


const tabBreakpoints = {
    320: {
        spaceBetween: 24,
    },
    768: {
        spaceBetween: 24,
    },
    1024: {
        spaceBetween: 24,
    },
};

// functions
const getProductsByCategory = (categoryId = "") => {
    isCategoryProductLoading.value = true;
    if (categoryId == "") {
        currentTab.value = "all";
    } else {
        currentTab.value = categoryId;
    }
    axios
        .get(popularProductsApi + "?category_id=" + categoryId, {
            headers: {
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            console.log(response.data.data.popular_products);
            products.value = response.data.data.popular_products;
            isCategoryProductLoading.value = false;
        })
        .catch((error) => {
            isCategoryProductLoading.value = false;
        });
};

const getAllProducts = () => {
    isLoading.value = true;
    isCategoryProductLoading.value = true;
    currentTab.value = "all";
    axios
        .get(popularProductsApi, {
            headers: {
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            console.log(response.data.data.popular_products);
            products.value = response.data.data.popular_products;
            isLoading.value = false;
            isCategoryProductLoading.value = false;
        })
        .catch((error) => {
            isLoading.value = false;
            isCategoryProductLoading.value = false;
        });
};

// onMounted
onMounted(() => {
    getAllProducts();
});
</script>

<style scoped>
.popular_swiper .swiper-button-prev,
.popular_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.popular_swiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.popular_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

.popular_swiper .swiper-button-prev:after,
.popular_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.tab_links {
    position: relative;
    padding: 0;
    background: transparent;
    border: none;
    text-wrap: nowrap;
    @apply text-gray-500 text-sm lg:text-lg font-normal font-['Lexend'] leading-relaxed lg:leading-snug;
}

.tab_links::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--primary);

    transform: scaleX(0); /* start hidden */
    transform-origin: middle; /* animate from left -> right */
    transition: transform 300ms ease-in-out; /* animate transform */
    will-change: transform;
    pointer-events: none;
}

/* when the active class is added, scale to full width */
.tab_links.tab_link_active::after {
    transform: scaleX(1);
}

/* optional: change text color for active state */
.tab_link_active {
    color: var(--primary);
}
</style>
