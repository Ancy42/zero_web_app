<template>
    <div class="container-3 grid grid-cols-1 md:grid-cols-4 gap-4">
        <a
            :href="master.offerBanners[0].link"
            v-if="master.offerBanners.length > 0"
            class="hidden md:block"
        >
            <img
                :src="master.offerBanners[0].offer_banner"
                alt=""
                class="w-full h-full max-h-[280px] md:object-contain"
            />
        </a>
        <a
            :href="master.offerBanners[1].link"
            v-if="master.offerBanners.length > 1"
            class="hidden md:block"
        >
            <img
                :src="master.offerBanners[1].offer_banner"
                alt=""
                class="w-full h-full max-h-[280px] md:object-contain"
            />
        </a>
        <div
            v-if="master.offerBanners.length > 1"
            class="grid grid-cols-2 md:hidden gap-4"
        >
            <a :href="master.offerBanners[0].link">
                <img
                    :src="master.offerBanners[0].offer_banner"
                    alt=""
                    class="w-full h-full max-h-[280px] md:object-contain"
                />
            </a>
            <a :href="master.offerBanners[1].link">
                <img
                    :src="master.offerBanners[1].offer_banner"
                    alt=""
                    class="w-full h-full max-h-[280px] md:object-contain"
                />
            </a>
        </div>
        <a
            :href="master.offerBanners[2].link"
            v-if="master.offerBanners.length > 2"
            class="md:col-span-2"
        >
            <img
                :src="master.offerBanners[2].offer_banner"
                alt=""
                class="w-full h-full max-h-[280px] object-contain"
            />
        </a>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useMaster } from "../../stores/MasterStore";

// stores
const master = useMaster();

// variables
const isLoading = ref(true);
const allProducts = ref([]);

function getAllProducts() {
    isLoading.value = true;
    axios
        .get("/products?page=1&per_page=6", {})
        .then((response) => {
            console.log(response.data.data.products);
            allProducts.value = [...response.data.data.products];
            isLoading.value = false;
        })
        .catch((error) => {
            isLoading.value = false;
        });
}

onMounted(() => {
    getAllProducts();
});
</script>
