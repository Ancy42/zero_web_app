<template>
    <section
        v-if="master.offerBanners.length > 0"
        class="container-2 py-[10px] md:py-[30px] lg:py-[60px] px-4 2xl:px-0"
    >
        <div
            class="grid grid-cols-12 gap-2 lg:gap-6 md:grid-rows-[200px] lg:grid-rows-[300px] xl:grid-rows-[556px]"
        >
            <a
                v-if="master.offerBanners[0].offer_banner"
                :href="master.offerBanners[0].link"
                target="_blank"
                :class="
                    master.offerBanners[0].offer_banner
                        ? 'col-span-6 md:col-span-8'
                        : 'col-span-12'
                "
            >
                <img
                    :src="master.offerBanners[0].offer_banner"
                    alt=""
                    class="h-full w-full object-cover rounded-lg"
                />
            </a>
            <a
                v-if="master.offerBanners[1].offer_banner"
                :class="
                    master.offerBanners[1].offer_banner
                        ? 'col-span-6 md:col-span-4'
                        : 'col-span-12'
                "
                :href="master.offerBanners[1].link"
                target="_blank"
            >
                <img
                    :src="master.offerBanners[1].offer_banner"
                    alt=""
                    class="h-full w-full object-cover rounded-lg"
                />
            </a>
        </div>
    </section>
</template>

<script setup>
import { useMaster } from "../../stores/MasterStore";

const props = defineProps({
    isLoading: {
        type: Boolean,
        default: true,
    },
});

const master = useMaster();
</script>
