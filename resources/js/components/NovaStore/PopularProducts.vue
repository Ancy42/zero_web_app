<template>
    <SectionContainer :title="'Popular Products'" :isLoading="isLoading">
        <template #button>
            <div class="flex justify-center items-center gap-4">
                <ViewAllBtn link="/most-popular" />
            </div>
        </template>
        <template #tabs>
            <div class="pt-4">

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
            <section class="hidden lg:grid grid-cols-6 gap-6 mt-[32px]">
                <!-- banner  -->
                <a
                :href="master.offerBanners[3]?.link"
                    v-if="master?.offerBanners?.length >= 4"
                    class="hidden md:block col-span-6 md:col-span-2 lg:col-span-2"
                >
                    <img :src="master.offerBanners[3]?.offer_banner" alt="" class="sticky top-36" />
                    <SkeletonLoader
                        class="w-full h-full"
                        v-if="isCategoryProductLoading"
                    />
                </a>
                <!-- Products -->
                <div
                    class="col-span-6 md:col-span-4 lg:col-span-4 grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-6"
                >
                    <p
                        v-if="!isCategoryProductLoading && products.length == 0"
                        class="col-span-full text-center text-primary"
                    >
                        No products to show
                    </p>
                    <div
                        v-if="!isCategoryProductLoading"
                        v-for="product in products"
                        :key="product.id"
                        class="w-full"
                    >
                        <ProductCard :product="product" />
                    </div>

                    <!-- loading -->
                    <div v-else v-for="i in 6" :key="i">
                        <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
                    </div>
                </div>
            </section>

            <!-- slider  -->
            <section
                :dir="master.langDirection || 'ltr'"
                class="block lg:hidden"
            >
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
                        <div class="mb-8">
                            <ProductCard :product="product" />
                        </div>
                    </swiper-slide>

                    <!-- loading -->
                    <swiper-slide v-else v-for="i in 8" :key="i">
                        <SkeletonLoader class="w-full h-[250px] rounded-lg" />
                    </swiper-slide>
                </swiper>
            </section>
        </div>
    </SectionContainer>
</template>

<script setup>
import ProductCard from "../NovaStore/ProductCard.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import {
    Navigation,
    Pagination,
    A11y,
    Autoplay,
    FreeMode,
} from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import SectionContainer from "../SectionContainer.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import { useAuth } from "../../stores/AuthStore";
import { useMaster } from "../../stores/MasterStore";
import ViewAllBtn from "../ViewAllBtn.vue";

import "swiper/css";
import "swiper/css/free-mode";

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
const modules = [Navigation, Pagination, A11y, Autoplay, FreeMode];

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
        spaceBetween: 8,
    },
    540: {
        slidesPerView: 2.2,
        spaceBetween: 12,
    },
    712: {
        slidesPerView: 2.5,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 2.5,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 6,
        spaceBetween: 20,
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
/* markup assumption:
   <button class="tab_links">Tab</button>
   <button class="tab_links tab_link_active">Active tab</button>
*/

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
    color: #3d434f;
    padding: 0;
    background: transparent;
    border: none;
    text-wrap: nowrap;
    @apply text-sm lg:text-lg font-normal font-['Lato'] leading-relaxed lg:leading-snug;
}

.tab_links::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--primary-500);

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
    color: var(--primary-500);
}
</style>
