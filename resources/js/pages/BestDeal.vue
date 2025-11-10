<template>
    <div class="main-container pt-8 pb-12">

        <div v-if="!isLoading" class="text-slate-800 text-lg lg:text-3xl font-bold">
            {{ $t('Best Deal') }}
        </div>
        <!-- loading -->
        <SkeletonLoader v-else class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg" />

        <!-- Products -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-6 items-start mt-6">
            <div v-if="!isLoading" v-for="product in products" :key="product.id" class="w-full">
                <ProductCardToggler :productData="product" />
            </div>
            <!-- loading -->
            <div v-else v-for="i in 12" :key="i">
                <SkeletonLoader class="w-full h-[220px] sm:h-[330px] rounded-lg" />
            </div>

            <div v-if="products.length == 0 && !isLoading" class="text-slate-950 text-xl font-medium leading-7">
                {{ $t('No Products Found') }}
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="products.length > 0 && !isLoading" class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
            <div class="text-slate-800 text-base font-normal leading-normal">
                {{ $t('Showing') }} {{ (perPage * (currentPage - 1) + 1) }} to {{ (perPage * (currentPage - 1) +
                products.length) }}
                {{ $t('of') }} {{ totalProducts }} {{ $t('results') }}
            </div>
            <div>
                <vue-awesome-paginate :total-items="totalProducts" :items-per-page="perPage" type="button"
                    :hide-prev-next-when-ends="true" :max-pages-shown="5" v-model="currentPage"
                    @click="onClickHandler" />
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useMaster } from '../stores/MasterStore';
import ProductCardToggler from '../components/ProductCardToggler.vue';
import SkeletonLoader from '../components/SkeletonLoader.vue';
import { useAuth } from '../stores/AuthStore';

const authStore = useAuth();
const master = useMaster();
const currentPage = ref(1);
const perPage = ref(12);

const products = ref([]);
const totalProducts = ref(0);
const isLoading = ref(true);

onMounted(() => {
    fetchProducts();
    window.scrollTo(0, 0);
});

const onClickHandler = async (page) => {
    currentPage.value = page;
    fetchProducts();
};

const fetchProducts = async () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    isLoading.value = true;
    axios.get('/products', {
        params: {
            page: currentPage.value,
            per_page: perPage.value,
            sort_type: 'top_selling'
        },
        headers: {
            'Accept-Language': master.locale || 'en',
            Authorization: authStore.token,
            Currency_Id: master.selectedCurrency.id,
        }
    }).then((response) => {
        totalProducts.value = response.data.data.total;
        products.value = response.data.data.products;
        setTimeout(() => {
            isLoading.value = false;
        }, 300);
    })
};

</script>
