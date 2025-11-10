<template>
    <section class="bg-neutral-100 mb-[60px] md:mb-[83px] px-4 2xl:px-0">
        <SectionContainerV5 :title="'Just For You'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <ViewAllBtn2 link="/products" />
                </div>
            </template>

            <!-- Products -->
            <div
                v-if="!isLoading && products"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 xl:gap-8 items-start"
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
                class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 xl:gap-8 items-start"
            >
                <div v-for="i in 6" :key="i">
                    <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
                </div>
            </div>

            <!-- Load More Products -->
            <div
                v-if="!isLoading"
                class="mt-4 md:mt-[24px] w-full flex justify-center"
            >
                <button
                    v-if="hasMoreProducts && !loadMore"
                    @click="loadMoreProducts()"
                    class="px-6 py-2.5 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-red-500 inline-flex justify-center items-center gap-2.5"
                >
                    <div
                        class="justify-start text-red-500 text-xl font-normal font-['Lexend'] leading-7"
                    >
                        {{ $t("Load More Products") }}
                    </div>
                </button>

                <button
                    v-if="loadMore"
                    class="px-6 py-2.5 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-red-500 inline-flex justify-center items-center gap-2.5 text-red-500 text-xl font-normal font-['Lexend'] leading-7"
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
        </SectionContainerV5>
    </section>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useAuth } from "../../stores/AuthStore";
import SkeletonLoader from "../SkeletonLoader.vue";
import ProductCard from "./ProductCard.vue";
import SectionContainerV5 from "../SectionContainerV5.vue";
import ViewAllBtn2 from "../ViewAllBtn2.vue";

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
