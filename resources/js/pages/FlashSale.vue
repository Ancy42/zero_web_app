<template>
    <div>
        <div class="main-container py-4 bg-slate-100">

            <!-- Flash Sale Banner -->
            <div class="p-3 xl:p-6 flex-col sm:flex-row justify-center items-center gap-1 sm:gap-8 flex rounded-xl mb-3"
                style="background: linear-gradient(90deg, #8B5CF6 0%, #C622FF 36.81%, #5C87F6 75.35%, #8B5CF6 100%);">

                <div class="text-center text-white sm:text-3xl font-bold sm:leading-9">
                    {{ $t("Don't Delay, Buy Now") }}.
                </div>

                <div class="flex justify-center items-center gap-2 xl:gap-4">
                    <div class="text-center text-white text-lg font-normal leading-7 tracking-tight">
                        {{ $t('Ending in') }}
                    </div>

                    <div class="flex justify-center items-center gap-2 text-white">
                        <div v-if="endDay > 0"
                            class="w-10 h-10 sm:w-11 sm:h-11 p-[5.18px] bg-white rounded-md shadow-inner flex-col justify-center items-center flex">
                            <div class="text-center text-primary text-lg font-bold leading-tight">
                                {{ endDay }}
                            </div>
                            <div class="text-center text-[#687387] text-[9px] font-normal leading-none">
                                {{ $t("Days") }}
                            </div>
                        </div>

                        <span v-if="endDay > 0" class="text-white text-2xl font-bold">:</span>
                        <div
                            class="w-10 h-10 sm:w-11 sm:h-11 p-[5.18px] bg-white rounded-md shadow-inner flex-col justify-center items-center flex">
                            <div class="text-center text-primary text-lg font-bold leading-tight">
                                {{ endHour }}
                            </div>
                            <div class="text-center text-[#687387] text-[9px] font-normal leading-none">
                                {{ $t("Hours") }}
                            </div>
                        </div>

                        <span class="text-white text-2xl font-bold">:</span>
                        <div
                            class="w-10 h-10 sm:w-11 sm:h-11 p-[5.18px] bg-white rounded-md shadow-inner flex-col justify-center items-center flex">
                            <div class="text-center text-primary text-lg font-bold leading-tight">
                                {{ endMinute }}
                            </div>
                            <div class="text-center text-[#687387] text-[9px] font-normal leading-none">
                                {{ $t("Minutes") }}
                            </div>
                        </div>

                        <span v-if="endDay <= 0" class="text-white text-2xl font-bold">:</span>
                        <div v-if="endDay <= 0"
                            class="w-10 h-10 sm:w-11 sm:h-11 p-[5.18px] bg-white rounded-md shadow-inner flex-col justify-center items-center flex">
                            <div class="text-center text-primary text-lg font-bold leading-tight">
                                {{ endSecond }}
                            </div>
                            <div class="text-center text-[#687387] text-[9px] font-normal leading-none">
                                {{ $t("Seconds") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="w-full p-2 sm:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between">
                <!-- Back -->
                <router-link to="/" class="py-2 flex gap-1 sm:gap-2 items-center justify-center">
                    <ArrowLeftIcon class="w-4 h-4 sm:w-6 sm:h-6 text-slate-600" />
                    <div class="text-slate-600 text-sm sm:text-base font-normal leading-normal">
                        {{ $t("Back") }}
                    </div>
                </router-link>

                <!-- Categories slider -->
                <div class="grow overflow-x-auto">
                    <swiper :slidesPerView="'auto'" :spaceBetween="16" class="categorySwiper">

                        <swiper-slide>
                            <div class="p-2 sm:px-4 sm:py-3 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300"
                                :class="!categoryId ? 'text-primary border-primary' : 'border-slate-200 text-slate-600'"
                                @click="selectCategory('')">
                                All
                            </div>
                        </swiper-slide>

                        <swiper-slide v-for="category in masterStore.categories" :key="category.id">
                            <div class="p-2 sm:px-4 sm:py-3 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300"
                                :class="(category.id == categoryId) ? 'text-primary border-primary' : 'border-slate-200 text-slate-600'"
                                @click="selectCategory(category.id)">
                                {{ category.name }}
                            </div>
                        </swiper-slide>

                    </swiper>
                </div>
            </div>
        </div>

        <div class="main-container py-12">
            <div
                class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6 items-start">

                <div v-for="product in products" :key="product.id" class="w-full">
                    <ProductCardToggler :productData="product" />
                </div>

            </div>
            <div v-if="products.length == 0" class="flex justify-center items-center w-full mt-8">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    {{ $t('No products found') }}
                </div>

            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    {{ $t('Showing') }} {{ perPage * (currentPage - 1) + 1 }} to {{ perPage * (currentPage - 1) + products.length }}
                    {{ $t('of') }} {{ totalProducts }} {{ $t('results') }}
                </div>
                <div>
                    <vue-awesome-paginate :total-items="totalProducts" :items-per-page="perPage" type="button"
                        :max-pages-shown="5" v-model="currentPage"
                        :hide-prev-next-when-ends="true"
                        @click="onClickHandler" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import ProductCardToggler from '../components/ProductCardToggler.vue';
// Import Swiper styles
import 'swiper/css';

import { useMaster } from '../stores/MasterStore';
const masterStore = useMaster();

const route = useRoute();

const { id } = useRoute().params;

const categoryId = ref(null);

onMounted(() => {
    fetchFlashSaleProducts();
    window.scrollTo(0, 0);
});

const currentPage = ref(1);
const perPage = 12;

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchFlashSaleProducts();
};
const flashSale = ref({});
const products = ref([]);
const totalProducts = ref(0);

const fetchFlashSaleProducts = async () => {
    axios.get('/flash-sale/' + id + '/details', {
        params: {
            category_id: categoryId.value,
            page: currentPage.value,
            per_page: perPage,
        }
    }).then((response) => {
        flashSale.value = response.data.data.flash_sale;
        totalProducts.value = response.data.data.total_products;
        products.value = response.data.data.products;

        startCountdown();
    });
};

watch(() => categoryId.value, () => {
    currentPage.value = 1;
    fetchFlashSaleProducts();
});

const selectCategory = (id) => {
    categoryId.value = id;
}

const endDay = ref('');
const endHour = ref('');
const endMinute = ref('');
const endSecond = ref('');
let countdownInterval = null;

const startCountdown = () => {
    const endDate = new Date(flashSale.value.end_date).getTime();

    countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const timeLeft = endDate - now;

        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            endDay.value = '00';
            endHour.value = '00';
            endMinute.value = '00';
            endSecond.value = '00';
        } else {
            endDay.value = String(Math.floor(timeLeft / (1000 * 60 * 60 * 24))).padStart(2, '0');
            endHour.value = String(Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            endMinute.value = String(Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            endSecond.value = String(Math.floor((timeLeft % (1000 * 60)) / 1000)).padStart(2, '0');
        }
    }, 1000);
};

onUnmounted(() => {
    clearInterval(countdownInterval);
});

</script>

<style>
.categorySwiper .swiper-slide {
    width: auto !important;
}
</style>
