<template>
    <div class="relative bg-zinc-900 lg:py-[64px] lg:pt-8  overflow-hidden">
        <FooterTop />
        <FooterBottom />

        <div
            class="w-64 h-64 top-[50%] block md:hidden absolute bg-primary rounded-full blur-[150px]"
            :class="master.langDirection == 'rtl' ? '-left-32' : '-right-28'"
        ></div>

        <div
            class="w-64 h-64 left-0 -bottom-20 hidden md:block absolute bg-primary rounded-full blur-[270px]"
        ></div>
        <div
            class="w-64 h-64 left-[50%] top-[332px] hidden md:block absolute bg-primary rounded-full blur-[270px]"
        ></div>
        <div
            class="w-64 h-64 -right-20 -bottom-20 hidden md:block absolute bg-primary rounded-full blur-[270px]"
        ></div>
    </div>
</template>

<script setup>
import FooterTop from "./FooterTop.vue";
import FooterBottom from "./FooterBottom.vue";
import { useMaster } from "../stores/MasterStore";

const master = useMaster();
</script>
