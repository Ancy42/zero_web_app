<template>
    <div>
        <!-- Header -->
        <AuthPageHeader :title="'Wishlist (' + authStore.favoriteProducts + ')'" />

        <div class="p-3 md:p-4 xl:p-6">

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4  gap-6 items-start">
                    <ProductCardToggler v-for="product in products" :key="product.id" :productData="product" />
            </div>

            <div v-if="products.length == 0"
                class="p-3 md:p-4 xl:p-6 bg-white rounded-lg md:rounded-2xl border border-slate-100 italic">
                {{ $t('Wishlist is empty') }}
            </div>

        </div>

    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import AuthPageHeader from "../components/AuthPageHeader.vue";

import { useAuth } from "../stores/AuthStore";

import { useRoute, useRouter } from 'vue-router';
import ProductCardToggler from "../components/ProductCardToggler.vue";
const route = useRoute();
const router = useRouter();
const authStore = useAuth();

const products = ref([]);

watch(() => authStore.favoriteProducts, () => {
    if (route.name === 'wishlist') {
        fetchProducts();
    }
});

onMounted(() => {
    fetchProducts();
});

const fetchProducts = async () => {
    axios.get('/favorite-products', {
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        products.value = response.data.data.products;
        authStore.favoriteProducts = response.data.data.products?.length ?? 0;
    }).catch((error) => {
        if (error.response.status === 401) {
            authStore.token = null;
            authStore.user = null;
            authStore.addresses = [];
            authStore.favoriteProducts = 0;
            router.push('/');
        }
    });
};

</script>
