<template>
    <section class="bg-primary-50">
        <SectionContainer :title="'Popular Products'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <ViewAllBtn link="/most-popular" />
                </div>
            </template>

            <div>
                <section class="hidden lg:grid grid-cols-4 gap-8 mt-[32px]">
                    <!-- Products -->
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
                            <ProductCard :product="product" />
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
        </SectionContainer>
    </section>
</template>

<script setup>
import { ArrowRightIcon } from "@heroicons/vue/24/outline";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import SectionContainer from "../SectionContainer.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import { useAuth } from "../../stores/AuthStore";
import { useMaster } from "../../stores/MasterStore";
import ViewAllBtn from "../ViewAllBtn.vue";

import "swiper/css";
import ProductCard from "../UltraMart/ProductCard.vue";

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
        spaceBetween: 12,
    },
    530: {
        slidesPerView: 2.3,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 6,
        spaceBetween: 20,
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

.tab_links {
    position: relative;
    color: #3d434f;
    padding: 0;
    background: transparent;
    border: none;
    text-wrap: nowrap;
    @apply text-gray-700 text-sm lg:text-lg font-normal font-['Lato'] leading-relaxed lg:leading-snug;
}

.tab_links::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--primary-200);

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
    color: var(--primary-200);
}
</style>
