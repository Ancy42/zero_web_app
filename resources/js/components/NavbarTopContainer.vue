<template>
    <NavbarTopV1 v-if="themeName == 'NovaStore'" />
    <!-- <NavbarTopV3 v-if="themeName == 'NextMart'" /> -->
    <NavbarTopV4 v-if="themeName == 'MegaMart'" />
</template>

<script setup>
import NavbarTopV1 from "./NavbarTopV1.vue";
import NavbarTopV3 from "./NavbarTopV3.vue";
import NavbarTopV4 from "./NavbarTopV4.vue";

const props = defineProps({
    themeName: {
        type: String,
        default: "",
    },
});
</script>
