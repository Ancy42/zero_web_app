<template>
    <HomeContainerNovaStore
        v-if="master.themeName == 'NovaStore'"
        :banners="banner"
        :categories="categories"
        :incomingFlashSale="incomingFlashSale"
        :runningFlashSale="runningFlashSale"
        :popularProducts="popularProducts"
        :topRatedShops="topRatedShops"
        :justForYou="justForYou"
        recentlyViewProducts=""
        :ads="ads"
        :isLoading="isLoading"
        :isLoadingCategory="isLoadingCategory"
    />
    <HomeContainerPrimeCart
        v-if="master.themeName == 'PrimeCart'"
        :banners="banner"
        :categories="categories"
        :incomingFlashSale="incomingFlashSale"
        :runningFlashSale="runningFlashSale"
        :popularProducts="popularProducts"
        :topRatedShops="topRatedShops"
        :justForYou="justForYou"
        recentlyViewProducts=""
        :ads="ads"
        :isLoading="isLoading"
        :isLoadingCategory="isLoadingCategory"
    />

    <HomeContainerNextMart
        v-if="master.themeName == 'NextMart'"
        :banners="banner"
        :categories="categories"
        :incomingFlashSale="incomingFlashSale"
        :runningFlashSale="runningFlashSale"
        :popularProducts="popularProducts"
        :topRatedShops="topRatedShops"
        :justForYou="justForYou"
        recentlyViewProducts=""
        :ads="ads"
        :isLoading="isLoading"
        :isLoadingCategory="isLoadingCategory"
    />
    <HomeContainerMegaMart
        v-if="master.themeName == 'MegaMart'"
        :banners="banner"
        :categories="categories"
        :incomingFlashSale="incomingFlashSale"
        :runningFlashSale="runningFlashSale"
        :popularProducts="popularProducts"
        :topRatedShops="topRatedShops"
        :justForYou="justForYou"
        recentlyViewProducts=""
        :ads="ads"
        :isLoading="isLoading"
        :isLoadingCategory="isLoadingCategory"
    />

    <HomeContainerUltraMart
        v-if="master.themeName == 'UltraMart'"
        :banners="banner"
        :categories="categories"
        :incomingFlashSale="incomingFlashSale"
        :runningFlashSale="runningFlashSale"
        :popularProducts="popularProducts"
        :topRatedShops="topRatedShops"
        :justForYou="justForYou"
        recentlyViewProducts=""
        :ads="ads"
        :isLoading="isLoading"
        :isLoadingCategory="isLoadingCategory"
    />
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useBasketStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";

import axios from "axios";
import { useAuth } from "../stores/AuthStore";
import HomeContainerNovaStore from "../components/NovaStore/HomeContainer.vue";
import HomeContainerPrimeCart from "../components/PrimeCart/HomeContainer.vue";
import HomeContainerNextMart from "../components/NextMart/HomeContainer.vue";
import HomeContainerMegaMart from "../components/MegaMart/HomeContainer.vue";
import HomeContainerUltraMart from "../components/UltraMart/HomeContainer.vue";

const master = useMaster();
const basketStore = useBasketStore();

const authStore = useAuth();
const isLoading = ref(true);
const isLoadingCategory = ref(true);
const isLoginRecentlyView = ref(false);

const homePageVarients = ref("home-varient-1");

onMounted(() => {
    getData();
    basketStore.fetchCart();
    fetchViewProducts();
    master.basketCanvas = false;
    authStore.loginModal = false;
    authStore.registerModal = false;
    authStore.showAddressModal = false;
    authStore.showChangeAddressModal = false;
});

const banner = ref([]);
const categories = ref([]);
const incomingFlashSale = ref([]);
const runningFlashSale = ref([]);
const popularProducts = ref([]);
const topRatedShops = ref([]);
const justForYou = ref([]);
const recentlyViewProducts = ref([]);
const ads = ref([]);

const getData = () => {
    isLoading.value = true;
    axios
        .get("/home?page=1&per_page=12", {
            headers: {
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            ads.value = response.data.data.ads;
            banner.value = response.data.data.banners;
            categories.value = response.data.data.categories;
            justForYou.value = response.data.data.just_for_you;
            popularProducts.value = response.data.data.categories;
            topRatedShops.value = response.data.data.shops
            incomingFlashSale.value = response.data.data.incoming_flash_sale;
            runningFlashSale.value = response.data.data.running_flash_sale;
            isLoading.value = false;
        })
        .catch((error) => {
            isLoading.value = false;
        });

    // fetch categories
    isLoadingCategory.value = true;
    axios
        .get("/categories")
        .then((response) => {
            master.categories = response.data.data.categories;
            setTimeout(() => {
                isLoadingCategory.value = false;
            }, 500);
        })
        .catch(() => {
            isLoadingCategory.value = false;
        });
};

const fetchViewProducts = () => {
    if (authStore.token) {
        isLoginRecentlyView.value = true;
        axios
            .get("/recently-views", {
                headers: {
                    Authorization: authStore.token,
                },
            })
            .then((response) => {
                recentlyViewProducts.value = response.data.data.products;
                isLoginRecentlyView.value = false;
            })
            .catch((error) => {
                isLoginRecentlyView.value = false;
                if (error.response.status === 401) {
                    authStore.token = null;
                    authStore.user = null;
                    authStore.addresses = [];
                }
            });
    }
};
</script>

<style scoped></style>
