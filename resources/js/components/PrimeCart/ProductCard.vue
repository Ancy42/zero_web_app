<template>
    <div
        class="w-full cursor-pointer bg-white bg-white rounded-lg shadow-[0px_2px_12px_0px_rgba(0,0,0,0.16)] inline-flex flex-col justify-start items-start overflow-hidden transition-all duration-200 group"
    >
        <div
            class="w-full h-60 relative flex flex-col justify-start items-start gap-2.5 overflow-hidden"
        >
            <RouterLink
                :to="`/products/${props.product?.slug}`"
                class="h-full w-full"
            >
                <img
                    class="self-stretch flex-1 group-hover:scale-110 transition-all duration-700 h-full w-full object-cover"
                    :src="product?.thumbnail"
                    alt="product"
                />
            </RouterLink>

            <div
                v-if="product?.discount_percentage > 0"
                class="h-4 px-1.5 py-0.5 left-[8px] top-[8px] absolute bg-red-500 rounded inline-flex justify-center items-center gap-2"
            >
                <div
                    class="justify-start text-white text-[10px] font-normal leading-none"
                >
                    {{ product?.discount_percentage }}%
                    {{ $t("OFF") }}
                </div>
            </div>

            <div
                @click="favoriteAddOrRemove"
                class="right-[8px] top-[8px] absolute w-10 h-10 bg-white rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.16)] inline-flex justify-center items-center gap-2.5"
            >
                <RiHeart3Fill
                    v-if="product?.is_favorite"
                    class="w-6 h-6 text-error"
                />
                <RiHeart3Line v-else class="w-6 h-6 text-black" />
            </div>

            <!-- <div
                class="absolute bottom-0 right-0 w-full h-auto flex justify-center items-center gap-6 p-3 transition-all duration-200 lg:translate-y-full translate-y-0 group-hover:translate-y-0"
            >
                <div
                    @click="favoriteAddOrRemove"
                    class="w-10 h-10 bg-black rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.16)] inline-flex justify-center items-center gap-2.5"
                >
                    <RiHeart3Fill
                        v-if="product?.is_favorite"
                        class="w-6 h-6 text-error"
                    />
                    <RiHeart3Line v-else class="w-6 h-6 text-white" />
                </div>

                <RouterLink
                    :to="`/products/${props.product?.slug}`"
                    @click="favoriteAddOrRemove"
                    class="w-10 h-10 bg-black rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.16)] inline-flex justify-center items-center gap-2.5"
                >
                    <EyeIcon class="w-6 h-6 text-white" />
                </RouterLink>

                <button
                    @click="addToBasket(props.product)"
                    class="w-10 h-10 bg-black rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.16)] inline-flex justify-center items-center gap-2.5"
                    :disabled="product?.quantity === 0"
                >
                    <Cart colorClass="fill-white" height="21" width="21" />
                </button>
            </div> -->
        </div>

        <div
            class="self-stretch p-3 flex flex-col justify-start items-start gap-1"
        >
            <div
                class="self-stretch flex flex-col justify-start items-start gap-1"
            >
                <RouterLink
                    :to="`/products/${props.product?.slug}`"
                    class="w-full flex flex-col justify-start items-center gap-1"
                >
                    <div class="flex justify-between items-center w-full">
                        <p
                            class="text-primary-500 text-xs font-normal leading-none text-center"
                        >
                            {{ product?.category }}
                        </p>

                        <div class="flex justify-center items-center gap-0.5">
                            <RiStarFill class="w-[14px] text-warning" />

                            <div
                                class="flex justify-start items-center gap-0.5"
                            >
                                <p
                                    class="justify-start text-zinc-900 text-xs font-bold leading-tight"
                                >
                                    {{ product?.rating }}
                                </p>
                                <p
                                    class="justify-start text-slate-500 text-xs font-normal leading-tight"
                                >
                                    ({{ product?.total_reviews }})
                                </p>
                            </div>
                        </div>
                    </div>
                    <p
                        class="w-full inline-block text-zinc-900 text-lg font-medium leading-normal h-12 overflow-hidden text-start"
                    >
                        {{ truncate(product?.name, 60) }}
                    </p>
                </RouterLink>

                <div
                    class="self-stretch inline-flex justify-between items-center"
                >
                    <div class="flex justify-start items-center gap-0.5">
                        <div
                            class="justify-start text-primary-500 text-lg font-bold leading-relaxed"
                        >
                            {{
                                masterStore.showCurrency(
                                    product?.discount_price > 0
                                        ? product?.discount_price
                                        : product?.price
                                )
                            }}
                        </div>

                        <div
                            v-if="product?.discount_price > 0"
                            class="justify-start text-gray-400 text-xs font-normal line-through leading-none"
                        >
                            {{ masterStore.showCurrency(product?.price) }}
                        </div>
                    </div>

                    <button
                        @click="addToBasket(product)"
                        class="h-10 px-2 py-2.5 bg-white rounded-lg shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)] inline-flex justify-center items-center gap-2.5 overflow-hidden group-hover:bg-primary transition-all duration-200"
                        :disabled="product?.quantity === 0"
                    >
                        <CartIcon
                            colorClass="fill-primary group-hover:fill-white transition-all duration-100"
                            height="21"
                            width="21"
                        />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    RiHomeLine,
    RiStarFill,
    RiHeart3Line,
    RiHeart3Fill,
} from "vue-remix-icons";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
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
    console.log("clicked");
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
