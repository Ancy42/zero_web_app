<template>
    <div class="relative bg-zinc-900 lg:py-[64px]  overflow-hidden">
        <FooterTopV3 />
        <FooterBottomV3 />

        <div
            class="w-64 h-64 top-0 block md:hidden absolute bg-primary rounded-full blur-[150px]"
            :class="master.langDirection == 'rtl' ? '-left-32' : '-right-28'"
        ></div>

        <div
            class="w-64 h-64 -left-28 -bottom-28 hidden md:block absolute bg-[#D1F1697A] rounded-full blur-[200px]"
        ></div>
        <div
            class="w-64 h-64 left-[50%] -top-28 hidden md:block absolute bg-[#F16969] rounded-full blur-[200px]"
        ></div>
        <div
            class="w-64 h-64 -right-20 -bottom-20 hidden md:block absolute bg-[#D1F1697A] rounded-full blur-[200px]"
        ></div>
    </div>
</template>

<script setup>
import FooterBottom from "./FooterBottom.vue";
import { useMaster } from "../stores/MasterStore";
import FooterTopV3 from "./FooterTopV3.vue";
import FooterBottomV3 from "./FooterBottomV3.vue";

const master = useMaster();
</script>
