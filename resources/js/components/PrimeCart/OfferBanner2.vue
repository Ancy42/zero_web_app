<template>
    <section v-if="master.offerBanners.length > 6" class=" py-[10px] xl:py-[64px] px-4 2xl:px-0 bg-zinc-900  mb-[30px] md:mb-[83px]">
        <div  class="container-2">
            <div
                class="grid grid-cols-2 gap-2 lg:gap-6  xl:grid-rows-[300px] 2xl:grid-rows-[404px]"
            >
                <a
                    v-if="master.offerBanners[6].offer_banner"
                    :class="
                        master.offerBanners[6].offer_banner
                            ? 'col-span-1'
                            : 'col-span-1'
                    "
                    :href="master.offerBanners[6].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[6].offer_banner"
                        alt=""
                        class="h-full w-full object-cover object-center  rounded-2xl"
                    />
                </a>
                <a
                    v-if="master.offerBanners[7].offer_banner"
                    :class="
                        master.offerBanners[7].offer_banner
                            ? 'col-span-1'
                            : 'col-span-1'
                    "
                    :href="master.offerBanners[7].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[7].offer_banner"
                        alt=""
                        class="h-full w-full object-cover object-center  rounded-2xl"
                    />
                </a>
            </div>
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
