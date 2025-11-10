<template>
    <div class="bg-neutral-100">
        <SectionContainerV4
            :title="'Popular Products'"
            :isLoading="isLoading"
            :isCenter="true"
        >
            <template #tabs>
                <div class="max-w-3xl w-full">
                    <section v-if="!isLoading" class="p-4 bg-white rounded-lg">
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
                <section
                    class="hidden lg:grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6"
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
                            <SkeletonLoader
                                class="w-full h-[300px] rounded-lg mb-8"
                            />
                        </swiper-slide>
                    </swiper>

                    <p
                        v-if="!isCategoryProductLoading && products.length == 0"
                        class="col-span-full text-center text-primary"
                    >
                        No products to show
                    </p>
                </section>

                <div class="flex justify-center mt-6">
                    <router-link
                        to="/most-popular"
                        class="px-6 py-2.5 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500 inline-flex justify-center items-center gap-2.5"
                    >
                        <p
                            class="justify-start text-primary-500 text-xl font-normal font-['Lato'] leading-7"
                        >
                            {{ $t("View All") }}
                        </p>
                    </router-link>
                </div>
            </div>
        </SectionContainerV4>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import { useAuth } from "../../stores/AuthStore";
import { useMaster } from "../../stores/MasterStore";
import ViewAllBtn from "../ViewAllBtn.vue";

import "swiper/css";
import SectionContainerV4 from "../SectionContainerV4.vue";
import ProductCard from "../MegaMart/ProductCard.vue";

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
    520: {
        slidesPerView: 2,
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
        spaceBetween: 8,
    },
    712: {
        slidesPerView: 5,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 5,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 5,
        spaceBetween: 37,
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
    @apply text-center justify-start text-gray-700 text-sm lg:text-lg font-normal font-['Lato'] leading-relaxed transition-all duration-200 px-4  p-2 rounded-lg text-nowrap;
}

/* optional: change text color for active state */
.tab_link_active {
    @apply text-white bg-primary-500;
}
</style>
