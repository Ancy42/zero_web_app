<template>
    <div
        class="grid grid-cols-5 bg-neutral-700 rounded-xl shadow-[inset_0px_0px_10px_0px_rgba(0,0,0,0.16)] overflow-hidden"
    >
        <div class="col-span-2 h-full bg-white relative">
            <img
                class="w-full h-full object-cover"
                :src="product?.thumbnail"
                alt="product"
            />

            <div
                class="absolute top-2.5 bg-error h-4 w-28 text-center text-xs text-white"
                :class="
                    masterStore.langDirection == 'rtl'
                        ? '-right-8 rotate-45'
                        : '-left-10 -rotate-45'
                "
            >
                <div
                    class="text-white text-[6.78px] font-bold font-['Montserrat']"
                >
                    Flash Sale
                </div>
            </div>
        </div>
        <div class="col-span-3 w-full h-full p-3 flex flex-col justify-start gap-2">
            <div class="flex flex-col items-start justify-start gap-1 grow">
                <div class="w-full flex justify-between items-center">
                    <p
                        class="text-primary-500 text-xs font-normal leading-none text-nowrap"
                    >
                        {{ product?.category }}
                    </p>

                    <div
                        class="inline-flex justify-end items-center gap-0.5 w-full"
                    >
                        <RiStarFill class="w-4 h-4 text-warning" />
                        <div class="text-white text-xs font-bold leading-tight">
                            {{ product?.rating }}
                        </div>
                        <div
                            class="text-white/50 text-xs font-normal leading-none"
                        >
                            ({{ product?.total_reviews }})
                        </div>
                    </div>
                </div>

                <RouterLink :to="`/products/${props.product?.slug}`" class="self-stretch justify-start text-white text-base font-medium leading-normal">
                    
                
                    {{ truncate(product?.name, 40) }}
                </RouterLink>

                <div class="flex justify-start items-center gap-1 mt-auto">
                    <p
                        class="text-primary-500 text-lg font-bold leading-relaxed"
                    >
                        {{
                            masterStore.showCurrency(
                                product?.discount_price > 0
                                    ? product?.discount_price
                                    : product?.price
                            )
                        }}
                    </p>

                    <p
                        v-if="product?.discount_price > 0"
                        class="text-gray-400 text-xs font-normal line-through leading-none"
                    >
                        {{ masterStore.showCurrency(product?.price) }}
                    </p>
                </div>
            </div>

            <div class=" flex justify-between items-center gap-2">
                <button
                    @click="favoriteAddOrRemove"
                    class="w-10 h-10 p-2.5 bg-white rounded-lg shadow-[0px_2px_4px_0px_rgba(0,0,0,0.08)] outline outline-1 outline-offset-[-1px] outline-gray-100 flex justify-center items-center"
                >
                    <RiHeart3Fill
                        v-if="product?.is_favorite"
                        class="w-8 h-8 text-primary-400"
                    />
                    <RiHeart3Line v-else class="w-8 h-8" />
                </button>

                <button
                    @click="addToBasket(props.product)"
                    class="w-full h-10 px-4 flex justify-center items-center gap-[10px] capitalize bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500"
                    :disabled="product?.quantity === 0"
                >
                    <Cart colorClass="fill-primary-500" />
                    <p
                        class="text-primary-500 text-sm font-medium leading-snug text-nowrap"
                    >
                        {{
                            product?.quantity === 0
                                ? "out of stock"
                                : $t("add to cart")
                        }}
                    </p>
                </button>
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
import Cart from "../../icons/Cart.vue";

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
