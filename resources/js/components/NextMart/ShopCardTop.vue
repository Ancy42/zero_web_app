<template>
    <router-link :to="`/shops/${props.shop?.id}`" class="p-3 rounded-xl bg-neutral-100 inline-block w-full h-full">
        <div class="h-40 w-full">
            <img
                :src="props.shop?.logo"
                alt=""
                class="h-full w-full object-contain"
            />
        </div>
        <div
            class="self-stretch h-0 outline outline-1 outline-offset-[-0.50px] outline-gray-300 my-2"
        ></div>
        <div>
            <div
                class="self-stretch text-center justify-start text-zinc-900 text-lg font-medium font-['Lexend'] leading-relaxed"
            >
                {{ props.shop?.name }}
            </div>

            <div
                class="pt-1 pb-2 w-full flex justify-center items-center gap-2"
            >
                <div class="flex justify-center items-center">
                    <span class="star">
                        <RiStarFill class="w-[14px] text-warning" />
                    </span>
                    <span
                        class="text-black text-zinc-900 text-xs font-bold font-['Lexend'] leading-tight"
                        >{{ props.shop?.rating?.toFixed(1) }}</span
                    >
                    <span
                        class="text-slate-500 text-xs font-normal font-['Lexend'] leading-tight"
                        >({{ props.shop?.total_reviews }})</span
                    >
                </div>

                <div
                    class="w-0 h-3 origin-top-left rotate-0 outline outline-1 outline-offset-[-0.50px] outline-gray-300"
                ></div>

                <div>
                    <span class="text-slate-500 text-xs">Sold:</span>
                    <span class="font-semibold font-['Lexend'] leading-tight">
                        -/{{ props.shop?.total_products }}</span
                    >
                </div>
            </div>

            <button class="flex justify-center items-center gap-4 w-full">
                <p
                    class="text-primary-500 text-sm font-normal font-['Lexend'] leading-snug"
                >
                    Visit Store
                </p>
                <ArrowRightIcon class="w-4 h-4 text-primary-500" />
            </button>
        </div>
    </router-link>
</template>

<script setup>
import { StarIcon, ArrowRightIcon } from "@heroicons/vue/24/solid";
import {
    RiHomeLine,
    RiStarFill,
    RiHeart3Line,
    RiHeart3Fill,
    RiArrowRightLine,
    RiArrowRightFill,
    RiArrowRightLongLine,
} from "vue-remix-icons";
import { useMaster } from "../../stores/MasterStore";

const props = defineProps({
    shop: Object,
});

const master = useMaster();
</script>
