<template>
    <ShopCardTopNextMart v-if="masterStore.themeName == 'NextMart'" :shop="shopData" />
    <ShopCardTopNovaStore v-if="masterStore.themeName == 'NovaStore' || masterStore.themeName == 'PrimeCart'" :shop="shopData" />
    <ShopCardTopMegaMart v-if="masterStore.themeName == 'MegaMart'" :shop="shopData" />
    <ShopCardTopUltraMart v-if="masterStore.themeName == 'UltraMart'" :shop="shopData" />
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMaster } from "../stores/MasterStore";
import { ref } from "vue";
import ShopCardTopNextMart from "./NextMart/ShopCardTop.vue";
import ShopCardTopNovaStore from "./NovaStore/ShopCardTop.vue";
import ShopCardTopMegaMart from "./MegaMart/ShopCardTop.vue";
import ShopCardTopPrimeCart from "./PrimeCart/ShopCardTop.vue";
import ShopCardTopUltraMart from "./UltraMart/ShopCardTop.vue";

// store
const masterStore = useMaster();


// Define props for the parent component
const props = defineProps({
    shopData: {
        type: Object,
        required: true,
    },
});

</script>
