<template>
    <div
        class="flex items-center bg-white rounded-2xl overflow-hidden outline outline-1 outline-gray-100"
    >
        <div class="w-44 h-full">
            <RouterLink
                :to="`/products/${props.product?.slug}`"
                class="w-full h-full"
            >
                <img
                    class="h-full w-full object-cover group-hover:scale-110 transition-all duration-700"
                    :src="product?.thumbnail"
                    alt="product"
                />
            </RouterLink>
        </div>
        <div class="p-3 space-y-2 h-full w-full">
            <p
                class="text-zinc-900 text-sm font-medium font-['Inter'] leading-snug"
            >
                {{ truncate(product?.name, 60) }}
            </p>
            <div class="flex justify-start items-center flex-wrap gap-1">
                <div
                    class="justify-start text-primary-500 text-lg font-bold font-['Inter'] leading-relaxed"
                >
                    {{ masterStore.showCurrency(product?.price) }}
                </div>

                <div
                    v-if="product?.discount_price > 0"
                    class="justify-start text-gray-400 text-lg font-light font-['Inter'] line-through leading-relaxed"
                >
                    {{ masterStore.showCurrency(product?.price) }}
                </div>

                <div
                    v-if="product?.discount_percentage > 0"
                    class="h-4 px-1.5 py-0.5 bg-red-500 rounded inline-flex justify-center items-center gap-2 ml-auto"
                >
                    <div
                        class="justify-start text-white text-[10px] font-normal leading-none"
                    >
                        {{ product?.discount_percentage }}%
                        {{ $t("OFF") }}
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="flex justify-start items-center gap-1">
                    <RiStarFill class="w-4 h-4 text-warning" />
                    <div
                        class="justify-start text-slate-950 text-xs font-semibold font-['Inter'] leading-tight"
                    >
                        {{ product?.rating }}
                    </div>
                    <div
                        class="justify-start text-slate-500 text-xs font-normal font-['Inter'] leading-tight"
                    >
                        ({{ product?.total_reviews }})
                    </div>
                </div>

                <div class="inline-flex justify-start items-center gap-1">
                    <img
                        src="../../../../public/assets/icons/box-tick2.svg"
                        alt=""
                        class="w-4 h-4 relative"
                    />
                    <div
                        class="justify-start text-zinc-900 text-xs font-semibold font-['Inter'] leading-tight"
                    >
                        {{ product?.total_sold }}
                    </div>
                    <div
                        class="justify-start text-slate-500 text-xs font-normal font-['Inter'] leading-tight"
                    >
                        {{ $t("Sold") }}
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center gap-3">
                <button
                    @click="addToCart"
                    class="p-2 outline outline-1 outline-offset-[-1px] outline-primary flex justify-center items-center rounded-lg"
                    :disabled="product?.quantity === 0"
                >
                    <CartIcon colorClass="fill-primary" />
                </button>

                <button
                    @click="buyNow"
                    class="bg-primary p-2 w-full h-full flex justify-center items-center gap-2 rounded-lg"
                >
                    <BagIcon :colorClass="'text-white'" />
                    <p
                        class="text-white text-sm font-medium font-['Inter'] leading-snug"
                    >
                        Buy Now
                    </p>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { HeartIcon as HeartIconOutline } from "@heroicons/vue/24/outline";
import { HeartIcon, StarIcon } from "@heroicons/vue/24/solid";
import {
    RiHomeLine,
    RiStarFill,
    RiHeart3Line,
    RiHeart3Fill,
} from "vue-remix-icons";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import BagIcon from "../../icons/Bag.vue";
import { useAuth } from "../../stores/AuthStore";
import { useBasketStore } from "../../stores/BasketStore";
import { useMaster } from "../../stores/MasterStore";
import { useTruncateText } from "../../composables/useTruncateText";
import CartIcon from "../../icons/Cart.vue";

const router = useRouter();

const masterStore = useMaster();

const basketStore = useBasketStore();
const authStore = useAuth();

const toast = useToast();

const { truncate } = useTruncateText();

const props = defineProps({
    product: Object,
});

const orderData = {
    is_buy_now: false,
    product_id: props.product?.id,
    quantity: 1,
    size: null,
    color: null,
    unit: null,
};

const addToBasket = (product) => {
    // add product to basket
    basketStore.addToCart(orderData, product);
};

const buyNow = async () => {
    if (authStore.token === null) {
        return (authStore.loginModal = true);
    }

    await basketStore.addToCart(
        {
            product_id: props.product?.id,
            is_buy_now: true,
            quantity: 1,
            size: null,
            color: null,
            unit: null,
        },
        props.product
    );

    basketStore.buyNowShopId = props.product?.shop.id;
    router.push({ name: "buynow" });
};

const isFavorite = ref(props.product?.is_favorite);

const favoriteAddOrRemove = () => {
    if (authStore.token === null) {
        return (authStore.loginModal = true);
    }
    axios
        .post(
            "/favorite-add-or-remove",
            {
                product_id: props.product.id,
            },
            {
                headers: {
                    Authorization: authStore.token,
                },
            }
        )
        .then((response) => {
            props.product.is_favorite = !props.product.is_favorite;
            isFavorite.value = response.data.data.product.is_favorite;
            if (isFavorite.value === false) {
                toast.warning("Product removed from favorite", {
                    position:
                        masterStore.langDirection === "rtl"
                            ? "bottom-right"
                            : "bottom-left",
                });
            } else {
                toast.success("Product added to favorite", {
                    position:
                        masterStore.langDirection === "rtl"
                            ? "bottom-right"
                            : "bottom-left",
                });
            }
            authStore.favoriteRemove = true;
            authStore.fetchFavoriteProducts();
        });
};

const showProductDetails = () => {
    if (props.product.quantity > 0) {
        router.push({
            name: "productDetails",
            params: { id: props.product.id },
        });
    }
};
</script>
