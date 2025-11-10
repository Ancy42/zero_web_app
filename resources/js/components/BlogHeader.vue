<template>
    <div class="main-container py-4 sm:py-6 bg-slate-100 mt-[25px]">
        <div class="w-full p-3 sm:p-6 bg-white rounded-md md:rounded-2xl">
            <div v-if="!isLoading">
                <div class="text-slate-800 text-lg sm:text-xl md:text-3xl font-bold">
                    {{ $t('Ready Blogs') }}
                </div>

                <div class="grow overflow-x-auto mt-2 sm:mt-3 md:mt-4">
                    <swiper :slidesPerView="'auto'" :spaceBetween="16" class="categorySwiper">
                        <swiper-slide>
                            <div class="px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300 bg-slate-100"
                                :class="!categoryId ? 'text-primary border-primary' : 'border-slate-100 text-slate-600'"
                                @click="selectCategory('')">
                                {{ $t("All Blogs") }}
                            </div>
                        </swiper-slide>

                        <swiper-slide v-for="category in categories" :key="category.id">
                            <div class="px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300 bg-slate-100"
                                :class="(category.id == categoryId) ? 'text-primary border-primary' : 'border-slate-100 text-slate-600'"
                                @click="selectCategory(category.id)">
                                {{ category.name }}
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
            </div>
            <!-- loading -->
            <div v-else>
                <SkeletonLoader class="w-10/12 h-10 rounded-lg" />
                <div class="mt-3 flex gap-3 flex-wrap">
                    <SkeletonLoader v-for="i in 8" :key="i" class="w-40 h-12 rounded-lg grow" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import SkeletonLoader from './SkeletonLoader.vue';
import 'swiper/css';

const emit = defineEmits(['changeCategory']);

const selectCategory = (id) => {
    emit('changeCategory', id);
}

const props = defineProps({
    categories: Array,
    categoryId: Number,
    isLoading: {
        type: Boolean,
        default: true
    }
});

</script>

<style>
.categorySwiper .swiper-slide {
    width: auto !important;
}
</style>
