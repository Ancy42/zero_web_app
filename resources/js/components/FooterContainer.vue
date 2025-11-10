<template>
    <Footer v-if="varientName == 'NovaStore' || varientName == 'MegaMart' || varientName == 'NextMart' || varientName == 'PrimeCart'" />
    <FooterUltraMart v-else-if="varientName == 'UltraMart'" />
    <!-- <FooterV3 v-else-if="varientName == 'NextMart'" /> -->
</template>

<script setup>
import Footer from "../components/Footer.vue";
import FooterUltraMart from "./UltraMart/Footer.vue";
import FooterV3 from "./FooterV3.vue";

const props = defineProps({
    varientName:{
        type: String,
        default: 'home-varient-1'
    }
})
</script>