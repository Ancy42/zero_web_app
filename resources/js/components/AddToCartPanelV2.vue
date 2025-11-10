<template>
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:hidden fixed right-0 bg-white h-auto w-full transition-all duration-500 z-20 bottom-0 p-4 gap-1"
    >
        <div class="flex justify-start items-center gap-2">
            <p class="text-primary-500 text-base md:text-xl font-bold leading-normal">
                {{ master.showCurrency(parseFloat(currentPrice).toFixed(2)) }}
            </p>
            <p
            v-if="hasDiscountPrice"
                class="justify-start text-slate-400 text-xs md:text-lg font-normal font-['Lato'] leading-tight line-through"
            >
                {{ master.showCurrency(parseFloat(previousPrice).toFixed(2)) }}
            </p>

            <button
                v-if="discountPercentage > 0"
                class="h-4 md:h-5 px-1 py-0.5 bg-error rounded inline-flex justify-center items-center gap-2"
            >
                <p
                    class="justify-start text-white text-[10px] font-medium font-['Inter'] leading-none"
                >
                    {{ discountPercentage }}% {{ $t("OFF") }}
                </p>
            </button>
        </div>
        <div class="w-full h-full flex justify-center items-center gap-6">
            <button
                v-if="!showProductQtyCounter"
                class="w-full justify-center items-center text-primary flex gap-1 p-3 rounded-[8px] border border-primary bg-white"
                @click="addToCart"
            >
                <div class="w-5 h-5">
                    <CartIcon colorClass="fill-primary" />
                </div>
                <div class="text-base font-medium font-['Lato'] leading-normal">
                    {{ $t("Add to Cart") }}
                </div>
            </button>

            <div
                v-else
                class="p-2 rounded-[10px] border border-primary inline-flex gap-2 w-full h-full"
            >
                <div
                    class="flex justify-between items-center w-full h-full p-1 outline outline-1 outline-offset-[-1px] outline-slate-200 rounded"
                >
                    <button
                        class="bg-gray-100 rounded"
                        @click="decrementQty"
                    >
                        <MinusIcon class="w-5 h-5 text-primary-600" />
                    </button>

                    <div
                        class="w-6 flex items-center justify-center text-center text-primary-600 text-base font-semibold leading-6"
                    >
                        {{ qty }}
                    </div>

                    <button
                        class="bg-gray-100 rounded"
                        @click="incrementQty"
                    >
                        <PlusIcon class="w-5 h-5 text-primary-600" />
                    </button>
                </div>
            </div>
            <button
                class="w-full justify-center items-center flex gap-1 text-white bg-primary p-3 rounded-[8px] border border-primary"
                @click="buyNow"
            >
                <div class="w-5 h-5">
                    <BagIcon :colorClass="'text-white'" />
                </div>
                <span class="text-base font-medium leading-normal">
                    {{ $t("Buy Now") }}
                </span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { RiHomeLine, RiArrowUpDoubleLine } from "vue-remix-icons";
import { useScroll } from "../composables/useScroll";
import CartIcon from "../icons/Cart.vue";
import BagIcon from "../icons/Bag.vue";
import { MinusIcon, PlusIcon } from "@heroicons/vue/24/outline";
import { useMaster } from "../stores/MasterStore";

const props = defineProps({
    showProductQtyCounter: {
        type: Boolean,
        default: false,
    },
    qty: {
        type: Number,
        default: 0,
    },
    discountPercentage: {
        type: Number,
        default: 0,
    },
    previousPrice: {
        type: Number,
        default: 0,
    },
    currentPrice: {
        type: Number,
        default: 0,
    },
    hasDiscountPrice: {
        type: Boolean,
        default: false,
    },
});

// store
const master = useMaster();

const {
    scrollY,
    viewportHeight,
    documentHeight,
    scrollPercentage,
    viewportWidth,
} = useScroll();

const emits = defineEmits([
    "addToCart",
    "buyNow",
    "incrementQty",
    "decrementQty",
]);

const addToCart = () => {
    emits("addToCart");
};

const buyNow = () => {
    emits("buyNow");
};

const decrementQty = () => {
    emits("decrementQty");
};

const incrementQty = () => {
    emits("incrementQty");
};
</script>
