<template>
    <div>
        <div class="main-container py-4 bg-slate-100 mt-[25px]">
            <div
                v-if="!isLoading"
                class="w-full p-2 md:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between"
            >
                <!-- Back -->
                <router-link
                    to="/"
                    class="py-2 flex gap-1 sm:gap-2 items-center justify-center"
                >
                    <ArrowLeftIcon
                        class="w-4 h-4 sm:w-6 sm:h-6 text-slate-600"
                    />
                    <div
                        class="text-slate-600 text-sm sm:text-base font-normal leading-normal"
                    >
                        {{ $t("Back") }}
                    </div>
                </router-link>

                <!-- Categories slider -->
                <div class="grow overflow-x-auto whitespace-nowrap">
                    <span class="text-primary"
                        >“{{ master.search || "all" }}”</span
                    >
                    <span class="text-slate-800 text-base font-normal pl-2">
                        {{ totalProducts }} {{ $t("items found") }}
                    </span>
                </div>

                <!-- Filter button -->
                <div>
                    <button
                        class="p-2 sm:px-4 sm:py-3 bg-slate-200 rounded-md sm:rounded-[10px] justify-center items-center gap-2 inline-flex text-slate-600 text-sm sm:text-base font-normal leading-normal border-0 outline-none hover:text-primary transition duration-300"
                        @click="showFilterCanvas = true"
                    >
                        <FunnelIcon class="w-4 h-4 sm:w-6 sm:h-6" />
                        <div class="grow shrink basis-0">
                            {{ $t("Filter") }}
                        </div>
                    </button>
                </div>
            </div>
            <!-- loading -->
            <div
                v-else
                class="w-full p-2 md:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between"
            >
                <SkeletonLoader
                    v-for="i in 2"
                    class="w-24 sm:w-32 md:w-72 lg:w-96 h-12 rounded"
                />
            </div>
        </div>

        <div class="main-container py-12">
            <div
                class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-6 items-start"
            >
                <div
                    v-if="!isLoading"
                    v-for="product in products"
                    :key="product.id"
                    class="w-full"
                >
                    <ProductCardToggler :productData="product" />
                </div>

                <!-- loading -->
                <div v-else v-for="i in 12" :key="i">
                    <SkeletonLoader
                        class="w-full h-[220px] sm:h-[330px] rounded-lg"
                    />
                </div>
            </div>
            <div
                v-if="products.length == 0 && !isLoading"
                class="flex justify-center items-center w-full mt-8"
            >
                <div
                    class="text-slate-800 text-base font-normal leading-normal"
                >
                    {{ $t("No products found") }}
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="products.length > 0 && !isLoading"
                class="flex justify-between items-center w-full mt-8 gap-4 flex-wrap"
            >
                <div
                    class="text-slate-800 text-base font-normal leading-normal"
                >
                    {{ $t("Showing") }} {{ perPage * (currentPage - 1) + 1 }}
                    {{ $t("to") }}
                    {{ perPage * (currentPage - 1) + products.length }}
                    {{ $t("of") }} {{ totalProducts }} {{ $t("results") }}
                </div>
                <div>
                    <vue-awesome-paginate
                        :total-items="totalProducts"
                        :items-per-page="perPage"
                        type="button"
                        :max-pages-shown="maxPagesShown"
                        v-model="currentPage"
                        :hide-prev-next-when-ends="true"
                        @click="onClickHandler"
                    />
                </div>
            </div>
        </div>

        <!-- Filter Canvas Drawer -->
        <TransitionRoot as="template" :show="showFilterCanvas">
            <Dialog
                as="div"
                class="relative z-50"
                @close="showFilterCanvas = false"
            >
                <TransitionChild
                    as="template"
                    enter="ease-in-out duration-500"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in-out duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity"
                    />
                </TransitionChild>

                <div class="fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div
                            class="pointer-events-none fixed inset-y-0 flex max-w-full"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'left-0 sm:pr-10'
                                    : 'right-0 sm:pl-10'
                            "
                        >
                            <TransitionChild
                                as="template"
                                enter="transform transition ease-in-out duration-500 sm:duration-700"
                                :enter-from="
                                    master.langDirection == 'rtl'
                                        ? '-translate-x-full'
                                        : 'translate-x-full'
                                "
                                enter-to="translate-x-0"
                                leave="transform transition ease-in-out duration-500 sm:duration-700"
                                leave-from="translate-x-0"
                                :leave-to="
                                    master.langDirection == 'rtl'
                                        ? '-translate-x-full'
                                        : 'translate-x-full'
                                "
                            >
                                <DialogPanel
                                    class="pointer-events-auto relative w-screen max-w-md"
                                >
                                    <TransitionChild
                                        as="template"
                                        enter="ease-in-out duration-500"
                                        enter-from="opacity-0"
                                        enter-to="opacity-100"
                                        leave="ease-in-out duration-500"
                                        leave-from="opacity-100"
                                        leave-to="opacity-0"
                                    >
                                        <div
                                            class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"
                                        ></div>
                                    </TransitionChild>
                                    <div
                                        class="flex h-full flex-col justify-between overflow-y-scroll rounded-lg rounded-tr-none rounded-br-none bg-white shadow-xl"
                                    >
                                        <div class="flex flex-col gap-7">
                                            <div
                                                class="flex justify-between items-center p-4 px-6 sticky top-0 right-0 z-10 bg-neutral-100"
                                            >
                                                <div
                                                    class="text-zinc-900 text-2xl font-semibold leading-loose font-['Poppins']"
                                                >
                                                    {{ $t("Filter") }}
                                                </div>
                                                <button
                                                    class="w-8 h-8 flex justify-center items-center bg-slate-100 rounded-full"
                                                    @click="
                                                        showFilterCanvas = false
                                                    "
                                                >
                                                    <XMarkIcon
                                                        class="w-5 h-5 text-slate-700"
                                                    />
                                                </button>
                                            </div>


                                            <section class="flex flex-col gap-4 p-4 px-6">
                                                <!-- price range -->
                                                <div
                                                    class="border-b border-gray-100 space-y-4 pb-4"
                                                >
                                                    <div
                                                        class="flex justify-between items-center gap-4"
                                                    >
                                                        <div
                                                            class="text-slate-900 text-lg font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Filter by Price"
                                                                )
                                                            }}
                                                        </div>
    
                                                        <button
                                                            class="w-14 h-8 px-2.5 py-1.5 bg-primary rounded inline-flex justify-center items-center gap-2 text-white text-sm font-medium font-['Poppins'] leading-snug"
                                                        >
                                                            Filter
                                                        </button>
                                                    </div>
    
                                                    <div class="w-[98%]">
                                                        <vue-slider
                                                            v-model="priceRange"
                                                            :min="filter.min_price"
                                                            :max="filter.max_price"
                                                            :rail-style="railStyle"
                                                            :process-style="
                                                                processStyle
                                                            "
                                                            :dot-style="dotStyle"
                                                        ></vue-slider>
                                                    </div>
    
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <p
                                                            class="text-slate-500 text-base font-normal font-['Poppins'] leading-normal flex-shrink-0"
                                                        >
                                                            Price:
                                                        </p>
    
                                                        <div
                                                            class="flex items-center gap-1 flex-1 min-w-0"
                                                        >
                                                            <input
                                                                v-model="
                                                                    priceRange[0]
                                                                "
                                                                @input="
                                                                    priceRange = [
                                                                        $event
                                                                            .target
                                                                            .valueAsNumber,
                                                                        priceRange[1],
                                                                    ]
                                                                "
                                                                type="number"
                                                                name="min-price"
                                                                class="h-8 flex-1 min-w-0 px-4 bg-neutral-100 rounded text-zinc-900 text-sm font-normal font-['Poppins'] leading-snug outline outline-1 outline-offset-[-1px] outline-gray-300"
                                                            />
    
                                                            <p
                                                                class="text-gray-700 text-base font-normal font-['Poppins'] leading-normal px-1"
                                                            >
                                                                -
                                                            </p>
    
                                                            <input
                                                                v-model="
                                                                    priceRange[1]
                                                                "
                                                                @input="
                                                                    priceRange = [
                                                                        priceRange[0],
                                                                        $event
                                                                            .target
                                                                            .valueAsNumber,
                                                                    ]
                                                                "
                                                                type="number"
                                                                name="max-price"
                                                                class="h-8 flex-1 min-w-0 px-4 bg-neutral-100 rounded text-zinc-900 text-sm font-normal font-['Poppins'] leading-snug outline outline-1 outline-offset-[-1px] outline-gray-300"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                            </section>

                                            <!-- Customer Review -->
                                            <div class="p-4">
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Customer Review") }}
                                                </div>
                                                <!-- Rating -->
                                                <div
                                                    class="flex flex-col gap-2 mt-3"
                                                >
                                                    <div
                                                        v-for="ratingNumber in ratings"
                                                        :key="ratingNumber"
                                                    >
                                                        <label
                                                            :for="`rating${ratingNumber}`"
                                                            class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between px-2 py-1.5 bg-white rounded-lg border border-slate-100 gap-1.5"
                                                        >
                                                            <div
                                                                class="flex items-center gap-1"
                                                            >
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <StarIcon
                                                                        v-for="i in 5"
                                                                        :key="i"
                                                                        class="w-5 h-5"
                                                                        :class="
                                                                            i <=
                                                                            ratingNumber
                                                                                ? 'text-amber-500'
                                                                                : 'text-gray-200'
                                                                        "
                                                                    />
                                                                </div>
                                                                <div
                                                                    class="text-base font-medium leading-normal"
                                                                >
                                                                    {{
                                                                        ratingNumber
                                                                    }}.0
                                                                </div>
                                                            </div>
                                                            <input
                                                                type="radio"
                                                                v-model="
                                                                    filterFormData.rating
                                                                "
                                                                :id="`rating${ratingNumber}`"
                                                                name="rating"
                                                                class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300"
                                                                :value="
                                                                    ratingNumber
                                                                "
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sort by -->
                                            <div class="p-4">
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Sort by") }}
                                                </div>

                                                <select
                                                    v-model="
                                                        filterFormData.sort_type
                                                    "
                                                    class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                >
                                                    <option
                                                        v-for="shortBy in filterSortBy"
                                                        :key="shortBy"
                                                        :value="shortBy.value"
                                                    >
                                                        {{ shortBy.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="p-4">
                                                <div
                                                    class="flex justify-between items-center gap-2"
                                                >
                                                    <div
                                                        class="text-slate-950 text-base font-medium leading-normal"
                                                    >
                                                        {{
                                                            $t("Product Price")
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-primary text-base font-normal leading-normal"
                                                    >
                                                        {{
                                                            master.showCurrency(
                                                                priceRange[0]
                                                            )
                                                        }}
                                                        -
                                                        {{
                                                            master.showCurrency(
                                                                priceRange[1]
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                                <div class="w-[98%]">
                                                    <vue-slider
                                                        v-model="priceRange"
                                                        :min="filter.min_price"
                                                        :max="filter.max_price"
                                                    ></vue-slider>
                                                </div>
                                                <div
                                                    class="text-slate-400 text-xs font-normal leading-none flex justify-between mt-2"
                                                >
                                                    <span>
                                                        {{
                                                            master.showCurrency(
                                                                filter.min_price
                                                            )
                                                        }}
                                                    </span>
                                                    <span>
                                                        {{
                                                            master.showCurrency(
                                                                filter.max_price
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Category -->
                                            <div class="p-4">
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Category") }}
                                                </div>

                                                <select
                                                    v-model="
                                                        filterFormData.category_id
                                                    "
                                                    class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                >
                                                    <option value="" selected>
                                                        {{
                                                            $t(
                                                                "Select Category"
                                                            )
                                                        }}
                                                    </option>
                                                    <option
                                                        v-for="category in master.categories"
                                                        :key="category.id"
                                                        :value="category.id"
                                                    >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Brand -->
                                            <div class="p-4">
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Brand") }}
                                                </div>

                                                <select
                                                    v-model="
                                                        filterFormData.brand_id
                                                    "
                                                    class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                >
                                                    <option value="" selected>
                                                        {{ $t("Select Brand") }}
                                                    </option>
                                                    <option
                                                        v-for="brand in filter.brands"
                                                        :key="brand.id"
                                                        :value="brand.id"
                                                    >
                                                        {{ brand.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- color -->
                                            <!-- <div>
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Color") }}
                                                </div>

                                                <div
                                                    class="flex flex-wrap gap-4 p-3 rounded-xl border border-slate-200 mt-1"
                                                >
                                                    <label
                                                        v-for="color in filter.colors"
                                                        :key="color.id"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2"
                                                    >
                                                        <input
                                                            type="radio"
                                                            v-model="
                                                                filterFormData.color_id
                                                            "
                                                            :value="color.id"
                                                            :id="
                                                                'color-' +
                                                                color.id
                                                            "
                                                            name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300"
                                                        />
                                                        <span>{{
                                                            color.name
                                                        }}</span>
                                                    </label>
                                                </div>
                                            </div> -->

                                            <!-- size -->
                                            <!-- <div>
                                                <div
                                                    class="text-slate-950 text-base font-medium leading-normal"
                                                >
                                                    {{ $t("Size") }}
                                                </div>

                                                <div
                                                    class="flex flex-wrap gap-4 p-3 rounded-xl border border-slate-200 mt-1"
                                                >
                                                    <label
                                                        v-for="size in filter.sizes"
                                                        :key="size.id"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2"
                                                    >
                                                        <input
                                                            type="radio"
                                                            v-model="
                                                                filterFormData.size_id
                                                            "
                                                            :value="size.id"
                                                            :id="
                                                                'size-' +
                                                                size.id
                                                            "
                                                            name="size"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300"
                                                        />
                                                        <span>{{
                                                            size.name
                                                        }}</span>
                                                    </label>
                                                </div>
                                            </div> -->
                                        </div>

                                        <!-- button Clear and Apply -->
                                        <div
                                            class="sticky bottom-0 bg-white flex gap-6 p-6 border-t border-slate-200"
                                        >
                                            <button
                                                class="grow px-4 py-3 rounded-[10px] border border-primary text-primary text-base font-medium leading-normal"
                                                @click="clearFilter"
                                            >
                                                {{ $t("Clear") }}
                                            </button>
                                            <button
                                                class="grow px-4 py-3 bg-primary rounded-[10px] border border-primary text-white text-base font-medium leading-normal"
                                                @click="applyFilter"
                                            >
                                                {{ $t("Apply") }}
                                            </button>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    FunnelIcon,
    XMarkIcon,
    ArrowLeftIcon,
} from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
import ProductCardToggler from "../components/ProductCardToggler.vue";
import { useMaster } from "../stores/MasterStore";

import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import SkeletonLoader from "../components/SkeletonLoader.vue";
import { useScroll } from "../composables/useScroll";
import { computed } from "vue";
import { useRouter } from "vue-router";

const priceRange = ref([0, 1000]);


// range slider styles
const railStyle = {
    height: "4px",
};

const processStyle = {
    backgroundColor: "var(--primary)",
};

const dotStyle = {
    borderColor: "var(--primary)",
    backgroundColor: "var(--primary)",
    borderRadius: "0px",
    width: "8px",
};

const master = useMaster();
const router = useRouter();

const isLoading = ref(true);

const {
    scrollY,
    viewportHeight,
    documentHeight,
    scrollPercentage,
    viewportWidth,
} = useScroll();

onMounted(() => {
    fetchProducts();
    window.scrollTo(0, 0);
    setRangeValue.value = true;
});

onBeforeUnmount(() => {
    setRangeValue.value = false;
    master.search = null;
});

const search = master.search;

watch(
    () => master.search,
    () => {
        fetchProducts();
    }
);

const currentPage = ref(1);
const perPage = 12;
const maxPagesShown = computed(() => {
    if (viewportWidth.value <= 320) {
        return 2;
    }

    if (viewportWidth.value <= 780) {
        return 3;
    }

    return 5; // return all for larger screens
});

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchProducts();
};

const showFilterCanvas = ref(false);

const filterFormData = ref({
    rating: null,
    sort_type: "default",
    brand_id: "",
    color_id: null,
    size_id: null,
    min_price: null,
    max_price: null,
    category_id: "",
});

const filter = ref({
    sizes: [],
    brands: [],
    colors: [],
    min_price: 0,
    max_price: 1000,
});

const ratings = [5, 4, 3, 2, 1];

const categories = master.categories;

const products = ref([]);
const totalProducts = ref(0);
const setRangeValue = ref(true);

const fetchProducts = async () => {
    isLoading.value = true;
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    axios
        .get("/products", {
            params: {
                page: currentPage.value,
                per_page: perPage,
                search: master.search,
                ...filterFormData.value,
            },
            headers: {
                "Accept-Language": master.locale || "en",
                currency_id: master.selectedCurrency.id,
            },
        })
        .then((response) => {
            totalProducts.value = response.data.data.total;
            products.value = response.data.data.products;
            filter.value = response.data.data.filters;

            if (setRangeValue.value) {
                priceRange.value = [
                    Math.floor(filter.value.min_price),
                    Math.floor(filter.value.max_price),
                ];
            }

            setTimeout(() => {
                isLoading.value = false;
            }, 200);
        })
        .catch((error) => {
            isLoading.value = false;
        });
};

const clearFilter = () => {
    filterFormData.value = {
        rating: null,
        short_by: "default",
        brand_id: "",
        color_id: null,
        size_id: null,
        min_price: filter.value.min_price,
        max_price: filter.value.max_price,
        category_id: "",
    };
    priceRange.value = [
        Math.floor(filter.value.min_price),
        Math.floor(filter.value.max_price),
    ];
    setRangeValue.value = true;
};

const applyFilter = () => {
    filterFormData.value.min_price = priceRange.value[0];
    filterFormData.value.max_price = priceRange.value[1];

    setRangeValue.value = false;
    master.search = null;
    currentPage.value = 1;
    showFilterCanvas.value = false;
    fetchProducts();
};

const fetchCategories = async () => {
    if (categories.value.length === 0) {
        axios.get("/categories").then((response) => {
            categories.value = response.data.data.categories;
        });
    }
};

const filterSortBy = [
    {
        name: "Default Sorting",
        value: "default",
    },
    {
        name: "High to Low",
        value: "high_to_low",
    },
    {
        name: "Low to High",
        value: "low_to_high",
    },
    {
        name: "Most Selling",
        value: "top_selling",
    },
    {
        name: "New Product",
        value: "newest",
    },
];

watch(
    () => filterFormData.value.category_id,
    () => {
        router.push("/categories/" + filterFormData.value.category_id);
    }
);
</script>

<style>
.vue-slider-process {
    @apply bg-primary;
}

input[type="range"]::-webkit-slider-runnable-track,
input[type="range"]::-ms-track,
input[type="range"]::-moz-range-track {
    background: #000;
}

input[type="range"]::-moz-range-thumb,
input[type="range"]::-ms-thumb,
input[type="range"]::-webkit-slider-thumb {
    background: #000;
}
</style>
