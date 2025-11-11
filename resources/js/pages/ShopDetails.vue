<template>
    <div class="">
        <div class="h-56 md:h-64 w-full">
            <img
                v-if="!isLoading"
                :src="shop?.banner"
                loading="lazy"
                class="w-full h-full object-cover"
            />

            <!-- loading -->
            <SkeletonLoader v-else class="w-full h-full" />
        </div>

        <div class="main-container relative bg-primary-50 py-10 pt-24">
            <!-- Shop Details -->
            <div
                class="-top-32 sm:-top-28 lg:-top-[108px] absolute left-0 right-0 main-container"
            >
                <div
                    v-if="!isLoading"
                    class="w-full p-6 bg-white rounded-2xl shadow flex-col gap-2 sm:gap-6 flex"
                >
                    <div
                        class="flex justify-between w-full gap-3 sm:gap-6 flex-wrap sm:flex-nowrap"
                    >
                        <div class="flex grow gap-6">
                            <div
                                class="w-16 h-16 md:w-[88px] md:h-[88px] rounded-full overflow-hidden shrink-0"
                            >
                                <img
                                    :src="shop?.logo"
                                    loading="lazy"
                                    class="w-full h-full object-cover"
                                />
                            </div>

                            <div class="flex flex-col items-start gap-2">
                                <div
                                    class="flex justify-start items-center w-full gap-2 flex-wrap lg:flex-nowrap"
                                >
                                    <!-- Shop Name -->
                                    <div
                                        class="text-slate-950 text-lg font-bold leading-normal tracking-tight text-nowrap"
                                    >
                                        {{ shop?.name }}
                                    </div>
                                    <!-- Status -->
                                    <div
                                        class="px-1 py-0.5 rounded-[10px] text-white text-xs font-normal leading-none"
                                        :class="
                                            shop?.shop_status === 'Online'
                                                ? 'bg-lime-600'
                                                : 'bg-slate-500'
                                        "
                                    >
                                        {{ shop?.shop_status }}
                                    </div>
                                </div>

                                <div
                                    class="flex justify-start items-center gap-4"
                                >
                                    <!-- Items -->
                                    <div
                                        class="text-slate-500 text-base font-normal leading-normal"
                                    >
                                        {{ shop?.total_products }}+
                                        {{ $t("Items") }}
                                    </div>
                                </div>

                                <!-- short description -->
                                <div
                                    class="text-slate-500 text-sm font-normal leading-tight"
                                >
                                    <div v-html="shop?.description"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="w-full flex justify-end gap-5 my-2">
                            <div
                                class="sm:border-l border-slate-200 w-full sm:w-[140px] md:w-[140px] flex flex-row-reverse sm:flex-col justify-end sm:justify-start items-end gap-2"
                            >
                                <div class="flex gap-1">
                                    <div
                                        class="text-slate-800 text-base font-bold leading-normal"
                                    >
                                        {{ shop?.rating?.toFixed(1) }}
                                    </div>
                                    <div
                                        class="text-slate-500 text-base font-normal leading-normal"
                                    >
                                        ({{ shop?.total_reviews }})
                                    </div>
                                </div>
                                <div class="flex">
                                    <StarIcon
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-4 h-4"
                                        :class="
                                            i <= shop?.rating
                                                ? 'text-amber-500'
                                                : 'text-gray-300'
                                        "
                                    />
                                </div>
                            </div>
                            <div
                                @click.stop="openChat(shop)"
                                class="w-12 h-12 flex justify-center items-center bg-red-50 rounded-lg cursor-pointer"
                            >
                                <img
                                    src="/public/assets/icons/shop-chat/chat.svg"
                                    alt="chat"
                                    class="w-8 h-8"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- chat sidebar start -->
                    <RightChatSidebar
                        v-if="activeChatShop"
                        :show="showSidebar"
                        @close="showSidebar = false"
                        :shop="activeChatShop"
                    />
                    <!-- chat sidebar end -->

                    <!-- Buttons and search -->
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <button
                                class="px-4 py-3 rounded-[10px] text-base font-normal leading-normal"
                                :class="
                                    productTab
                                        ? 'text-white bg-primary'
                                        : 'text-slate-600'
                                "
                                @click="
                                    productTab = true;
                                    reviewTab = false;
                                "
                            >
                                {{ $t("All Products") }}
                            </button>

                            <button
                                class="px-4 py-3 rounded-[10px] text-base font-normal leading-normal"
                                :class="
                                    reviewTab
                                        ? 'text-white bg-primary'
                                        : 'text-slate-600'
                                "
                                @click="showReviewTab"
                            >
                                {{ $t("Reviews") }}
                            </button>
                        </div>
                        <!-- Search input -->
                        <div class="lg:w-[448px] relative hidden sm:block">
                            <input
                                type="text"
                                :placeholder="$t('Search product')"
                                class="p-3 pr-10 bg-slate-100 rounded-lg border border-slate-200 w-full outline-none focus:border-primary transition duration-300"
                                v-model="search"
                                @input="searchProducts()"
                            />
                            <MagnifyingGlassIcon
                                class="w-6 h-6 absolute top-1/2 right-3 -translate-y-1/2 text-slate-400"
                            />
                        </div>
                    </div>
                </div>

                <!-- loading -->
                <SkeletonLoader v-else class="w-full h-48 p-6 rounded-2xl" />
            </div>

            <!-- banners -->
            <div class="pt-10">
                <swiper
                    :breakpoints="breakpoints"
                    :spaceBetween="30"
                    :freeMode="true"
                    :modules="modules"
                >
                    <swiper-slide
                        v-if="!isLoading"
                        v-for="banner in shop?.banners"
                        :key="banner.id"
                    >
                        <img
                            :src="banner.thumbnail"
                            alt="banner"
                            loading="lazy"
                            class="aspect-[6/2] rounded-xl object-cover"
                        />
                    </swiper-slide>

                    <!-- loading -->
                    <swiper-slide v-else v-for="i in 3" :key="i">
                        <SkeletonLoader class="w-full h-32 rounded-xl" />
                    </swiper-slide>
                </swiper>
            </div>
        </div>

        <div class="main-container py-10 pt-24">
            <!-- Products and Reviews -->
            <div class="mt-12">
                <!-- Products -->
                <div v-if="productTab" class="w-full">
                    <div
                        class="grid grid-cols-1 items-start"
                        :class="
                            masterStore.themeName == 'UltraMart'
                                ? 'xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-3 '
                                : 'xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6 '
                        "
                    >
                        <div
                            v-if="!isLoadingProducts"
                            v-for="product in products"
                            :key="product.id"
                            class="w-full"
                        >
                            <ProductCardToggler :productData="product" />
                        </div>

                        <div v-else v-for="i in 12" :key="i" class="w-full">
                            <SkeletonLoader
                                class="w-full h-[220px] sm:h-[330px] rounded-lg"
                            />
                        </div>
                    </div>

                    <div
                        v-if="products.length == 0 && !isLoadingProducts"
                        class="text-slate-950 text-xl font-medium leading-7"
                    >
                        {{ $t("No Products Found") }}
                    </div>

                    <div
                        v-if="products.length > 0 && !isLoadingProducts"
                        class="flex justify-between items-center w-full mt-8 gap-4 flex-wrap"
                    >
                        <div
                            class="text-slate-800 text-base font-normal leading-normal"
                        >
                            {{ $t("Showing") }}
                            {{ perPage * (currentPage - 1) + 1 }}
                            {{ $t("to") }}
                            {{ perPage * (currentPage - 1) + products.length }}
                            {{ $t("of") }} {{ totalProducts }}
                            {{ $t("results") }}
                        </div>
                        <div>
                            <vue-awesome-paginate
                                :total-items="totalProducts"
                                :items-per-page="perPage"
                                type="button"
                                :max-pages-shown="5"
                                v-model="currentPage"
                                :hide-prev-next-when-ends="true"
                                @click="onClickHandler"
                            />
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div v-if="reviewTab" class="w-full">
                    <div
                        class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start"
                    >
                        <!-- Rating and Review -->
                        <div>
                            <div
                                class="text-slate-950 text-xl font-medium leading-7"
                            >
                                {{ $t("Rating and Review") }}
                            </div>
                            <ReviewRatings
                                :reviewRatings="averageRatings?.percentages"
                                :averageRating="averageRatings?.rating"
                                :totalReview="totalReviews"
                            />
                        </div>

                        <!-- Reviews -->
                        <div>
                            <div
                                class="text-slate-950 text-xl font-medium leading-7"
                            >
                                {{ $t("Reviews") }}
                            </div>

                            <div class="mt-4">
                                <div
                                    v-if="!isLoadingReviews"
                                    v-for="review in reviews"
                                    :key="review.id"
                                    class="mb-4"
                                >
                                    <Review :review="review" />
                                </div>

                                <!-- loading -->
                                <div
                                    v-else
                                    v-for="i in 6"
                                    :key="i"
                                    class="mb-4"
                                >
                                    <div class="w-full flex gap-3 items-center">
                                        <SkeletonLoader
                                            class="w-16 h-16 rounded-full shrink-0"
                                        />
                                        <div class="w-full space-y-2">
                                            <SkeletonLoader
                                                class="w-full h-2.5 rounded-lg"
                                            />
                                            <SkeletonLoader
                                                class="w-11/12 h-2.5 rounded-lg"
                                            />
                                            <SkeletonLoader
                                                class="w-10/12 h-2.5 rounded-lg"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- pagination's -->
                                <div
                                    v-if="
                                        !isLoadingReviews && reviews.length > 0
                                    "
                                    class="flex justify-between items-center w-full mt-8 gap-4 flex-wrap"
                                >
                                    <div
                                        class="text-slate-800 text-base font-normal leading-normal"
                                    >
                                        {{ $t("Showing") }}
                                        {{
                                            reviewPerPage * (reviewPage - 1) + 1
                                        }}
                                        {{ $t("to") }}
                                        {{
                                            reviewPerPage * (reviewPage - 1) +
                                            reviews.length
                                        }}
                                        {{ $t("of") }} {{ totalReviews }}
                                        {{ $t("results") }}
                                    </div>
                                    <div>
                                        <vue-awesome-paginate
                                            :total-items="totalReviews"
                                            :items-per-page="reviewPerPage"
                                            type="button"
                                            :max-pages-shown="3"
                                            v-model="reviewPage"
                                            :hide-prev-next-when-ends="true"
                                            @click="reviewPagination"
                                        />
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        reviews.length == 0 && !isLoadingReviews
                                    "
                                >
                                    <div
                                        class="text-slate-700 text-xl font-medium leading-7 italic"
                                    >
                                        {{ $t("No Reviews Found") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { StarIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { FreeMode } from "swiper/modules";
import ReviewRatings from "../components/ReviewRatings.vue";
import Review from "../components/Review.vue";
import SkeletonLoader from "../components/SkeletonLoader.vue";
import RightChatSidebar from "../components/RightChatSidebar.vue";

import { useMaster } from "../stores/MasterStore";
import { useAuth } from "../stores/AuthStore";

const authStore = useAuth();
const masterStore = useMaster();

const router = new useRouter();
const route = useRoute();

import "swiper/css";
import "swiper/css/free-mode";
import ProductCardToggler from "../components/ProductCardToggler.vue";

const modules = [FreeMode];

const isLoading = ref(true);
const isLoadingProducts = ref(true);
const isLoadingReviews = ref(false);

const productTab = ref(true);
const reviewTab = ref(false);

const currentPage = ref(1);
const perPage = ref(12);

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchProducts();
};

const reviewPerPage = ref(5);
const reviewPage = ref(1);

const reviewPagination = (page) => {
    reviewPage.value = page;
    fetchReviews();
};

const shop = ref({});

const products = ref([]);
const totalProducts = ref(0);

const averageRatings = ref({});
const totalReviews = ref(0);
const reviews = ref([]);

const search = ref("");
const showSidebar = ref(false);
const activeChatShop = ref(null);

let searchTimer = null;
const searchProducts = () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentPage.value = 1;
        productTab.value = true;
        reviewTab.value = false;
        fetchProducts();
    }, 1000);
};

onMounted(() => {
    if (!masterStore.multiVendor) {
        router.push("/");
        return;
    }
    fetchDetails();
    window.scrollTo(0, 0);
    fetchProducts();
});

const showReviewTab = () => {
    productTab.value = false;
    reviewTab.value = true;
    fetchReviews();
};

const fetchDetails = async () => {
    isLoading.value = true;
    axios
        .get("/shops/" + route.params.id)
        .then((response) => {
            shop.value = response.data.data.shop;
            setTimeout(() => {
                isLoading.value = false;
            }, 200);
        })
        .catch((error) => {
            isLoading.value = false;
        });
};

const fetchProducts = async () => {
    isLoadingProducts.value = true;
    axios
        .get("/products", {
            params: {
                shop_id: route.params.id,
                page: currentPage.value,
                per_page: perPage.value,
                search: search.value,
            },
            headers: {
                "Accept-Language": masterStore.locale || "en",
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            totalProducts.value = response.data.data.total;
            products.value = response.data.data.products;
            isLoadingProducts.value = false;
        })
        .catch((error) => {
            isLoadingProducts.value = false;
        });
};

const fetchReviews = async () => {
    isLoadingReviews.value = true;
    axios
        .get("/reviews", {
            params: {
                shop_id: route.params.id,
                page: reviewPage.value,
                per_page: reviewPerPage.value,
            },
        })
        .then((response) => {
            totalReviews.value = response.data.data.total;
            reviews.value = response.data.data.reviews;
            averageRatings.value = response.data.data.average_rating_percentage;
            isLoadingReviews.value = false;
        })
        .catch((error) => {
            isLoadingReviews.value = false;
        });
};

const breakpoints = {
    320: {
        slidesPerView: 1,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 2,
        spaceBetween: 30,
    },

    1280: {
        slidesPerView: 3,
        spaceBetween: 30,
    },
};

const openChat = (shop) => {
    if (authStore.token === null) {
        return (authStore.loginModal = true);
    }
    activeChatShop.value = shop;
    showChat();
};

const showChat = async () => {
    const response = await axios.post(
        "/store-message",
        {
            shop_id: activeChatShop.value.id,
            user_id: authStore?.user?.id,
            type: "user",
        },
        {
            headers: {
                Authorization: authStore.token,
            },
        }
    );

    showSidebar.value = true;
};
</script>
