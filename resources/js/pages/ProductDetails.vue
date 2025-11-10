<template>
    <ProductDetailsNovaStore v-if="master.themeName == 'NovaStore'"/>
    <ProductDetailsUltraMart v-if="master.themeName == 'UltraMart'" />
    <ProduceDetailsV4 v-if="master.themeName == 'MegaMart' || master.themeName == 'NextMart' || master.themeName == 'PrimeCart'" />
</template>

<script setup>
import { useMaster } from "../stores/MasterStore";
import ProductDetailsNovaStore from "../components/NovaStore/ProductDetails.vue";
import ProduceDetailsV4 from "../components/ProductDetailsV4.vue";
import ProductDetailsUltraMart from "../components/UltraMart/ProductDetails.vue";


const master = useMaster();

</script>

