<template>
    <section v-if="master.offerBanners.length > 2" class=" py-[10px] xl:py-[64px] px-4 2xl:px-0 bg-zinc-900 ">
        <div  class="container-2">
            <div
                class="grid grid-cols-3 gap-2 lg:gap-6  auto-rows-[200px] md:auto-rows-[200px] xl:auto-rows-[232px]"
            >
                <a
                    v-if="master.offerBanners[3].offer_banner"
                    :class="
                        master.offerBanners[3].offer_banner
                            ? 'col-span-3 md:col-span-1'
                            : 'col-span-1'
                    "
                    :href="master.offerBanners[3].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[3].offer_banner"
                        alt=""
                        class="h-full w-full object-cover object-center rounded-2xl"
                    />
                </a>
                <a
                    v-if="master.offerBanners[4].offer_banner"
                    :class="
                        master.offerBanners[4].offer_banner
                            ? 'col-span-3 md:col-span-1'
                            : 'col-span-1'
                    "
                    :href="master.offerBanners[4].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[4].offer_banner"
                        alt=""
                        class="h-full w-full object-cover object-center rounded-2xl"
                    />
                </a>
                <a
                    v-if="master.offerBanners[5].offer_banner"
                    :class="
                        master.offerBanners[5].offer_banner
                            ? 'col-span-3 md:col-span-1'
                            : 'col-span-1'
                    "
                    :href="master.offerBanners[5].link"
                    target="_blank"
                >
                    <img
                        :src="master.offerBanners[5].offer_banner"
                        alt=""
                        class="h-full w-full object-cover object-center rounded-2xl"
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
