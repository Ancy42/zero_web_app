<template>
    <section class="py-4 lg:py-[60px]">
        <SectionContainerV6
            :title="'Popular Products'"
            :isLoading="isLoading"
            :isCenter="true"
        >
            <template #tabs>
                <div class="w-full">
                    <!-- <section
                        v-if="!isLoading"
                        class="flex justify-start md:justify-center gap-6 lg:gap-[37px] flex-nowrap overflow-x-auto max-w-[250px] md:max-w-3xl scrollbar-hide my-3 lg:my-6 rounded-lg"
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

                    <section
                        v-if="!isLoading"
                        class="overflow-hidden max-w-[250px] md:max-w-3xl my-3 lg:my-6"
                    >
                        <swiper
                            :breakpoints="tabBreakpoints"
                            :free-mode="true"
                            :mousewheel="true"
                            ref="swiperRef"
                            @swiper="onSwiper"
                            :modules="modules"
                            class=""
                        >
                            <swiper-slide :key="0" class="!w-auto">
                                <button
                                    class="capitalize tab_links"
                                    :class="
                                        currentTab == 'all'
                                            ? 'tab_link_active'
                                            : ''
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

                <section
                    v-if="products.length == 0"
                    :dir="master.langDirection || 'ltr'"
                    class=""
                >
                    <p class="text-center text-primary-500 text-base">
                        No Products to show
                    </p>
                </section>

                <section v-else :dir="master.langDirection || 'ltr'">
                    <swiper
                        :breakpoints="breakpoints"
                        :loop="true"
                        ref="swiperRef"
                        @swiper="onSwiper"
                        :modules="modules"
                        class="popular_swiper green_bullet_lg"
                        :autoplay="{
                            delay: 4000,
                            disableOnInteraction: false,
                        }"
                        :navigation="{
                            nextEl: '.custom-next',
                            prevEl: '.custom-prev',
                        }"
                        :pagination="{ clickable: true }"
                    >
                        <swiper-slide
                            v-if="!isCategoryProductLoading"
                            v-for="product in products"
                            :key="product.id"
                            class="mb-10 md:mb-16"
                        >
                            <ProductCard :product="product" />
                        </swiper-slide>

                        <!-- loading -->
                        <swiper-slide
                            v-else
                            v-for="i in 8"
                            :key="i"
                            class="mb-10 md:mb-16"
                        >
                            <SkeletonLoader
                                class="w-full h-[350px] rounded-lg"
                            />
                        </swiper-slide>

                        <!-- custom Navigation buttons -->
                        <button
                            @click="swiperPrevSlide"
                            v-if="!isCategoryProductLoading"
                            class="custom-prev absolute top-[50%] left-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-4 h-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>
                        <button
                            @click="swiperNextSlide"
                            v-if="!isCategoryProductLoading"
                            class="custom-next absolute top-[50%] right-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-4 h-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </swiper>
                </section>
            </div>

            <div class="flex justify-center items-center gap-4 mt-4">
                <ViewAllBtn3 link="/products" />
            </div>
        </SectionContainerV6>
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
import "swiper/css/navigation";
import "swiper/css/pagination";
import ProductCard from "../PrimeCart/ProductCard.vue";
import SectionContainerV6 from "../SectionContainerV6.vue";
import ViewAllBtn3 from "../ViewAllBtn3.vue";

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
        slidesPerView: 1.1,
        spaceBetween: 8,
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
        slidesPerView: 4,
        spaceBetween: 20,
    },
    1440: {
        slidesPerView: 5,
        spaceBetween: 24,
    },
};

const tabBreakpoints = {
    320: {
        slidesPerView: 5,
        spaceBetween: 12,
    },
    712: {
        slidesPerView: 5,
        spaceBetween: 24,
    },
    768: {
        slidesPerView: 5,
        spaceBetween: 24,
    },
    1024: {
        slidesPerView: 5,
        spaceBetween: 32,
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
.popular_swiper {
    @apply p-1;
}
.popular_swiper .swiper-button-prev,
.popular_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
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

[dir="ltr"] .popular_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .popular_swiper .swiper-button-next {
    left: auto;
    right: 20px;
}

.popular_swiper .swiper-button-prev:after,
.popular_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.tab_links {
    @apply text-center justify-start text-gray-700 text-sm lg:text-lg font-normal font-['Montserrat'] leading-relaxed transition-all duration-200   rounded-lg text-nowrap relative;
}

/* optional: change text color for active state */
.tab_link_active {
    @apply text-primary-500 before:absolute before:w-full before:h-0.5 before:transition-all before:duration-200 before:bottom-0 before:left-0 before:bg-primary-500  hover:text-primary-500;
}
</style>
