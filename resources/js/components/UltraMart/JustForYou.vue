<template>
    <section class="bg-primary-50">
        <SectionContainer :title="'Just For You'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <RouterLink
                        to="#"
                        class="text-primary text-base md:text-lg font-normal leading-normal"
                    >
                        {{ $t("View All") }}
                    </RouterLink>
                </div>
            </template>

            <!-- Products -->
            <div
                v-if="!isLoading && products"
                class="grid grid-cols-1 sm2:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 xl:gap-8 items-start"
            >
                <ProductCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <!-- loading -->
            <div
                v-if="isLoading"
                class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 xl:gap-8 items-start"
            >
                <div v-for="i in 6" :key="i">
                    <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
                </div>
            </div>
        </SectionContainer>
    </section>

    <!-- Load More Products -->
    <div
        v-if="!isLoading"
        class="mt-4 md:mt-[24px] mb-[145px] md:mb-[83px] w-full flex justify-center px-4 md:px-0"
    >
        <button
            v-if="hasMoreProducts && !loadMore"
            class="max-w-96 w-full max-h-12 md:max-h-14 px-6 py-4 rounded-[10px] border border-primary text-primary text-lg font-medium font-['Lato'] leading-relaxed inline-flex justify-center items-center gap-2.5 transition-all duration-300 group hover:bg-primary hover:text-white"
            @click="loadMoreProducts()"
        >
            <span>{{ $t("Load More Products") }}</span>
        </button>

        <button
            v-if="loadMore"
            class="md:max-w-[450px] w-full px-[23px] py-[15px] rounded-[10px] border border-primary-200 text-primary flex items-center justify-center cursor-not-allowed text-primary text-base font-medium leading-6 transition-all duration-300 group hover:bg-primary hover:text-white"
            disabled
        >
            <svg
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
            {{ $t("Loading products") }}...
        </button>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useAuth } from "../../stores/AuthStore";
import SkeletonLoader from "../SkeletonLoader.vue";
import SectionContainer from "../SectionContainer.vue";
import ProductCard from "../UltraMart/ProductCard.vue";

const authStore = useAuth();

const props = defineProps({
    justForYou: Object,
    isLoading: Boolean,
});

const currentPage = ref(2);
const hasMoreProducts = ref(false);
const totalPages = ref(1);
const loadMore = ref(false);

const products = ref([]);

onMounted(() => {
    if (props.justForYou) {
        products.value = props.justForYou?.products;
        totalPages.value = Math.ceil(props.justForYou?.total / 12);
        if (totalPages.value > 1) {
            hasMoreProducts.value = true;
        }
    } else {
        return;
    }
});

watch(
    () => props.justForYou,
    () => {
        products.value = props.justForYou?.products;
        totalPages.value = Math.ceil(props.justForYou?.total / 12);
        if (totalPages.value > 1) {
            hasMoreProducts.value = true;
        }
    }
);

const loadMoreProducts = () => {
    loadMore.value = true;
    axios
        .get("/home?page=" + currentPage.value + "&per_page=12", {
            headers: {
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            products.value = products.value.concat(
                response.data.data.just_for_you.products
            );
            currentPage.value++;
            if (currentPage.value >= totalPages.value) {
                hasMoreProducts.value = false;
            }
            loadMore.value = false;
        })
        .catch((error) => {
            loadMore.value = false;
            console.log(error);
        });
};
</script>
