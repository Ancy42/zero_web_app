<template>
    <ProductCardNextMart v-if="masterStore.themeName == 'NextMart'" :product="productData" />
    <ProductCardPrimeCart v-if="masterStore.themeName == 'PrimeCart'" :product="productData" />
    <ProductCardMegaMart v-if="masterStore.themeName == 'MegaMart'" :product="productData" />
    <ProductCardNovaStore v-if="masterStore.themeName == 'NovaStore'" :product="productData" />
    <ProductCardUltraMart v-if="masterStore.themeName == 'UltraMart'" :product="productData" />
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMaster } from "../stores/MasterStore";
import { ref } from "vue";
import ProductCardNextMart from "./NextMart/ProductCard.vue";
import ProductCardPrimeCart from "./PrimeCart/ProductCard.vue";
import ProductCardMegaMart from "./MegaMart/ProductCard.vue";
import ProductCardNovaStore from "./NovaStore/ProductCard.vue"
import ProductCardUltraMart from "./UltraMart/ProductCard.vue";

// store
const masterStore = useMaster();


// Define props for the parent component
const props = defineProps({
    productData: {
        type: Object,
        required: true,
    },
});

</script>
