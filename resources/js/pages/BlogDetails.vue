<template>
    <!-- Breadcrumbs -->
    <div class="main-container flex items-center gap-2 overflow-hidden py-4 bg-white">
        <router-link to="/" class="w-6 h-6">
            <HomeIcon class="w-5 h-5 text-slate-600" />
        </router-link>

        <div class="grow w-full overflow-hidden">
            <div class="space-x-1 text-slate-600 text-sm font-normal truncate">
                <router-link to="/">{{ $t("Home") }}</router-link>
                <span>/</span>
                <router-link to="/blogs">{{ $t("Blog") }}</router-link>
                <span>/</span>
                <span>{{ blog.title }}</span>
            </div>
        </div>
    </div>

    <div class="main-container bg-slate-50 h-full mb-12">
        <div v-if="!isLoading" class="grid grid-cols-1 xl:grid-cols-7">

            <div class="xl:col-span-5 col-span-1" :class="master.langDirection == 'rtl' ? 'lg:pl-6' : 'lg:pr-6'">

                <div class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <div class="flex items-center gap-2">
                        <div class="px-2 py-1 bg-primary-100 rounded text-primary text-sm">
                            {{ blog.category?.name }}
                        </div>
                    </div>

                    <div class="text-slate-900 text-lg md:text-3xl font-bold mt-2.5">
                        {{ blog.title }}
                    </div>

                    <div class="flex items-center justify-between mt-2.5">
                        <div class="flex items-center gap-2">
                            <img :src="blog.post_by?.profile_photo" alt="author" class="w-10 h-10 rounded-full">
                            <div>
                                <div class="text-gray-900 text-sm font-medium">
                                    {{ blog.post_by?.name }}
                                </div>
                                <div class="text-gray-400 text-xs font-normal">
                                    {{ blog.created_at }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-slate-800">
                            <EyeIcon class="w-5 h-5 text-slate-600" />
                            <span class="text-sm">
                                {{ blog?.total_views }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 max-w-5xl">
                        <img :src="blog.thumbnail" class="w-full" alt="thumbnail" loading="lazy" />
                    </div>

                    <div class="mt-6 max-w-5xl">
                        <div class="prose w-full max-w-none" v-html="sanitize(blog.description)"></div>
                    </div>
                </div>

                <div v-if="blog.tags?.length > 0" class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <div class="text-slate-800 text-xl font-bold">
                        {{ $t("Related keywords") }}
                    </div>

                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        <div v-for="tag in blog.tags" class="px-2 py-1 bg-gray-100 text-gray-900 rounded-2xl">
                            #{{ tag.name }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <div class="text-slate-800 text-xl font-bold">
                        {{ $t("Share with your network") }}
                    </div>

                    <div v-if="blog.id" class="flex items-center gap-4 mt-3 flex-wrap">
                        <!-- facebook -->
                        <div class="w-[70px] h-12 py-1 bg-[#0d68f1] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('facebook')">
                            <FontAwesomeIcon :icon="faFacebookF" class="w-6 h-6 text-white" />
                        </div>
                        <!-- linkedin -->
                        <div class="w-[70px] h-12 py-1 bg-[#1275b1] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('linkedin')">
                            <FontAwesomeIcon :icon="faLinkedin" class="w-6 h-6 text-white" />
                        </div>
                        <!-- twitter -->
                        <div class="w-[70px] h-12 py-1 bg-[#47acdf] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('twitter')">
                            <FontAwesomeIcon :icon="faTwitter" class="w-6 h-6 text-white" />
                        </div>
                        <!-- pinterest -->
                        <div class="w-[70px] h-12 py-1 bg-[#bb0f23] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('pinterest')">
                            <FontAwesomeIcon :icon="faPinterest" class="w-6 h-6 text-white" />
                        </div>
                        <!-- reddit -->
                        <div class="w-[70px] h-12 py-1 bg-[#fc471e] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('reddit')">
                            <FontAwesomeIcon :icon="faRedditAlien" class="w-6 h-6 text-white" />
                        </div>
                        <!-- whatsapp -->
                        <div class="w-[70px] h-12 py-1 bg-[#25d366] rounded-xl justify-center items-center gap-2 inline-flex cursor-pointer"
                            @click="share('whatsapp')">
                            <FontAwesomeIcon :icon="faWhatsapp" class="w-6 h-6 text-white" />
                        </div>
                    </div>
                </div>

            </div>

            <div class="xl:col-span-2 col-span-1">
                <div class="mt-12">
                    <div class="text-slate-900 text-xl sm:text-2xl font-bold">
                        {{ $t('Related Products') }}
                    </div>

                    <div
                        class="mt-4 p-4 bg-slate-200 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-4 items-center">
                        <ProductCardHorizontal v-for="relatedProduct in relatedProducts" :key="relatedProduct.id"
                            :product="relatedProduct" />

                        <RouterLink to="/products"
                            class="p-3.5 bg-white rounded-[10px] border border-primary justify-center items-center gap-2 inline-flex text-primary">
                            {{ $t('View All') }}
                            <FontAwesomeIcon :icon="faArrowRightLong" class="w-4 h-4 text-primary" />
                        </RouterLink>
                    </div>

                    <div class="mt-12">
                        <div class="text-slate-900 text-xl md:text-2xl font-bold">
                            {{ $t('Popular Blogs') }}
                        </div>

                        <div class="p-4 mt-4 bg-white rounded-2xl grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-1 gap-4">
                            <BlogCardHorizontal v-for="popularBlog in popularBlogs" :key="popularBlog.id"
                                :blog="popularBlog" />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Related Blogs -->
        <div v-if="relatedBlogs.length > 0 && !isLoading" class="w-full mt-10">
            <div class="text-slate-900 text-2xl md:text-3xl font-bold">
                {{ $t('Related Blogs') }}
            </div>

            <div class="overflow-x-auto mt-4" :dir="master.langDirection">
                <swiper :slidesPerView="'auto'" :spaceBetween="20" class="categorySwiper">
                    <swiper-slide v-for="relatedBlog in relatedBlogs" :key="relatedBlog.id">
                        <div class=" max-w-[300px] md:max-w-[460px]">
                            <BlogCard :blog="relatedBlog" />
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </div>

        <!-- page loader -->
        <div v-if="isLoading" class="grid grid-cols-1 xl:grid-cols-7">
            <div class="xl:col-span-5 col-span-1 lg:pr-6">
                <div class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <SkeletonLoader class="w-20 h-8" />
                    <SkeletonLoader class="w-full h-5 mt-3 rounded-md" />
                    <div class="flex gap-3 mt-6 items-center">
                        <SkeletonLoader class="w-16 h-16 rounded-full" />
                        <div class="grow">
                            <SkeletonLoader class="w-11/12 h-4 rounded-full" />
                            <SkeletonLoader class="w-10/12 h-4 rounded-full mt-2" />
                        </div>
                    </div>
                    <SkeletonLoader class="w-ful h-72 mt-6" />
                </div>
                <div class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <SkeletonLoader class="w-9/12 h-6" />
                    <div class="mt-3 flex gap-3 flex-wrap">
                        <SkeletonLoader v-for="i in 8" :key="i" class="w-28 h-10 grow" />
                    </div>
                </div>
                <div class="mt-6 bg-white p-4 md:p-6 rounded-lg md:rounded-2xl">
                    <SkeletonLoader class="w-9/12 h-6" />
                    <div class="mt-3 flex gap-3 flex-wrap">
                        <SkeletonLoader v-for="i in 8" :key="i" class="w-28 h-10 grow" />
                    </div>
                </div>
            </div>

            <div class="xl:col-span-2 col-span-1 md:pt-6">
                <div class="flex gap-3 flex-wrap">
                    <SkeletonLoader v-for="i in 6" :key="i" class="w-full h-36 rounded-lg" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { HomeIcon } from '@heroicons/vue/24/solid';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref, watch } from 'vue';
import { useMaster } from '../stores/MasterStore';
import DOMPurify from 'dompurify';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faFacebookF, faLinkedin, faTwitter, faPinterest, faRedditAlien, faWhatsapp } from '@fortawesome/free-brands-svg-icons';
import { faArrowRightLong } from '@fortawesome/free-solid-svg-icons';
import SkeletonLoader from '../components/SkeletonLoader.vue';
import BlogCard from '../components/BlogCard.vue';
import ProductCardHorizontal from '../components/ProductCardHorizontal.vue';
import BlogCardHorizontal from '../components/BlogCardHorizontal.vue';

import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

import { useShareLink } from "vue3-social-sharing";
const { shareLink } = useShareLink();

const master = useMaster();
const route = useRoute();
const router = useRouter();

const slug = ref(route.params.slug);
const isLoading = ref(true);
const blog = ref({});
const relatedBlogs = ref([]);
const popularBlogs = ref([]);
const relatedProducts = ref([]);

const currentURL = window.location.href;

onMounted(() => {
    fetchBlog();
    window.scrollTo(0, 0);
});

watch(route, () => {
    slug.value = route.params.slug;
    fetchBlog();
});

const share = (network) => {
    const description = blog.value.description.replace(/<[^>]*>/g, "");
    const maxLength = 500;
    const trimmedString = description.substring(0, maxLength);
    const result = trimmedString.substring(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" ")));
    const tagsString = blog.value.tags.map(tag => tag.name).join(', ');

    shareLink({
        network: network,
        url: currentURL,
        title: blog.value.title,
        description: result,
        media: blog.value.thumbnail,
        quote: blog.value.title,
        hashtags: 'blog, ' + tagsString,
        twitterUser: blog.value.post_by?.name
    })
}

const sanitize = (html) => {
    return DOMPurify.sanitize(html);
}

const fetchBlog = () => {
    window.scrollTo(0, 0);
    isLoading.value = true;
    axios.get('/blog/' + slug.value + '/details', {
        headers: {
            'Accept-Language': master.locale || 'en',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then((response) => {
        blog.value = response.data.data.blog;
        relatedBlogs.value = response.data.data.related_blogs;
        popularBlogs.value = response.data.data.popular_blogs;
        relatedProducts.value = response.data.data.related_products;
        isLoading.value = false;
        scrollToTop();
    }).catch((error) => {
        isLoading.value = false;
        if (error.response.status === 400) {
            router.push({ name: 'not-found' });
        }
    });
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}
</script>

<style>
.categorySwiper .swiper-slide {
    width: auto !important;
}
</style>
