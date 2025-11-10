<template>
    <CategoryCardNextMart
        v-if="masterStore.themeName == 'NextMart'"
        :category="categoryData"
    />
    <CategoryCardPrimeCart
        v-if="masterStore.themeName == 'PrimeCart'"
        :category="categoryData"
    />
    <CategoryCardMegaMart
        v-if="masterStore.themeName == 'MegaMart'"
        :category="categoryData"
        :isDark="false"
    />
    <CategoryCardNovaStore
        v-if="masterStore.themeName == 'NovaStore'"
        :category="categoryData"
    />
    <CategoryCardUltraMart
        v-if="masterStore.themeName == 'UltraMart'"
        :category="categoryData"
    />
</template>

<script setup>
import { useMaster } from "../stores/MasterStore";

import CategoryCardNextMart from "./NextMart/CategoryCard.vue";
import CategoryCardPrimeCart from "./PrimeCart/CategoryCard.vue";
import CategoryCardMegaMart from "./MegaMart/CategoryCard.vue";
import CategoryCardNovaStore from "./NovaStore/CategoryCard.vue";
import CategoryCardUltraMart from "./UltraMart/CategoryCard.vue";

// store
const masterStore = useMaster();

// Define props for the parent component
const props = defineProps({
    categoryData: {
        type: Object,
        required: true,
    },
});
</script>
