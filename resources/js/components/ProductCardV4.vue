<template>
    <div
        class="self-stretch cursor-pointer bg-white rounded-lg shadow-[0px_2px_12px_0px_rgba(0,0,0,0.16)] inline-flex flex-col justify-start items-start overflow-hidden transition-all duration-200  group"
    >
        <div
            class="w-full h-44 relative flex flex-col justify-start items-start gap-2.5 overflow-hidden"
        >
            <RouterLink :to="`/products/${props.product?.slug}`">
                <img
                    class="self-stretch flex-1 group-hover:scale-110 transition-all duration-700"
                    :src="product?.thumbnail"
                    alt="product"
                />
            </RouterLink>

            <div
            @click="favoriteAddOrRemove"
                class="p-1 lg:p-2.5 right-[8px] top-[8px] absolute bg-white rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.16)] inline-flex justify-center items-center gap-2.5"
            >
                <RiHeart3Fill
                    v-if="product?.is_favorite"
                    class="w-6 h-6 text-primary-400"
                />
                <RiHeart3Line v-else class="w-6 h-6" />
            </div>
            <div
                class="w-full left-0 bottom-1 absolute inline-flex justify-center items-center px-4"
            >
                <div
                    class="h-5 px-1 bg-white/60 rounded flex justify-center items-center gap-2.5"
                >
                    <div class="flex justify-center items-center gap-0.5">
                        <RiStarFill class="w-[14px] text-warning" />

                        <div class="flex justify-start items-center gap-0.5">
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

            </div>
            <div
                class="h-4 px-1.5 py-0.5 left-[8px] top-[8px] absolute bg-red-500 rounded inline-flex justify-center items-center gap-2"
            >
                <div
                    class="justify-start text-white text-[10px] font-normal leading-none"
                >
                    {{ product?.discount_percentage }}%
                    {{ $t("OFF") }}
                </div>
            </div>
        </div>
        <div
            class="self-stretch p-3 flex flex-col justify-start items-start gap-3"
        >
            <div
                class="self-stretch flex flex-col justify-start items-start gap-2"
            >
                <div
                    class="w-full flex flex-col justify-start items-center gap-1"
                >
                    <p
                        class="self-stretch justify-start text-primary-500 text-xs font-normal font-['Albert_Sans'] leading-none text-center"
                    >
                        {{ product?.category }}
                    </p>
                    <p
                        class="w-full inline-block text-zinc-900 text-base font-semibold leading-normal h-12 overflow-hidden text-center"
                    >

                        {{ truncate(product?.name, 60) }}
                    </p>
                </div>
                <div
                    class="self-stretch inline-flex justify-center items-center"
                >
                    <div class="flex justify-start items-center gap-0.5">
                        <div
                        v-if="product?.discount_price > 0"
                            class="justify-start text-gray-400 text-xs font-normal line-through leading-none"
                        >
                            {{ masterStore.showCurrency(product?.price) }}
                        </div>
                        <div
                            class="justify-start text-primary text-base font-bold leading-normal"
                        >
                            {{
                                masterStore.showCurrency(
                                    product?.discount_price > 0
                                        ? product?.discount_price
                                        : product?.price
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <button
            
                @click="addToBasket(props.product)"
                class="w-full h-8 px-2 py-2.5 bg-white rounded-3xl outline outline-1 outline-offset-[-1px] outline-primary inline-flex justify-center items-center gap-2.5 overflow-hidden group-hover:bg-primary transition-all duration-300 "
                :disabled="product?.quantity === 0"
            >
                <div>
                    <svg
                        width="21"
                        height="21"
                        viewBox="0 0 21 21"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="group-hover:fill-white fill-primary transition-all duration-500"
                    >
                        <path
                            d="M8.60205 19C8.60205 19.69 8.04205 20.25 7.35205 20.25C6.66305 20.25 6.09705 19.69 6.09705 19C6.09705 18.31 6.65204 17.75 7.34204 17.75H7.35205C8.04205 17.75 8.60205 18.31 8.60205 19ZM15.3521 17.75H15.342C14.652 17.75 14.097 18.31 14.097 19C14.097 19.69 14.6621 20.25 15.3521 20.25C16.0421 20.25 16.6021 19.69 16.6021 19C16.6021 18.31 16.0421 17.75 15.3521 17.75ZM20.037 6.49197L19.0231 12.658C18.7601 14.104 18.106 15.75 15.332 15.75H7.06604C5.70704 15.75 4.53599 14.735 4.34399 13.389L2.83398 2.82397C2.74598 2.21197 2.21505 1.75098 1.59705 1.75098H1.33203C0.918031 1.75098 0.582031 1.41498 0.582031 1.00098C0.582031 0.586977 0.918031 0.250977 1.33203 0.250977H1.59802C2.95702 0.250977 4.12807 1.26597 4.32007 2.61197L4.41199 3.25098H17.332C18.15 3.25098 18.9201 3.61097 19.4441 4.23897C19.9671 4.86597 20.184 5.68797 20.037 6.49197ZM18.291 5.19897C18.053 4.91397 17.7031 4.74997 17.3311 4.74997H4.625L5.82898 13.177C5.91698 13.789 6.44804 14.25 7.06604 14.25H15.332C16.929 14.25 17.318 13.654 17.545 12.403L18.5591 6.23596C18.6281 5.85796 18.529 5.48397 18.291 5.19897ZM13.832 8.74997H12.582V7.49997C12.582 7.08597 12.246 6.74997 11.832 6.74997C11.418 6.74997 11.082 7.08597 11.082 7.49997V8.74997H9.83203C9.41803 8.74997 9.08203 9.08597 9.08203 9.49997C9.08203 9.91397 9.41803 10.25 9.83203 10.25H11.082V11.5C11.082 11.914 11.418 12.25 11.832 12.25C12.246 12.25 12.582 11.914 12.582 11.5V10.25H13.832C14.246 10.25 14.582 9.91397 14.582 9.49997C14.582 9.08597 14.246 8.74997 13.832 8.74997Z"
                        />
                    </svg>
                </div>
                <p
                    class="justify-start text-primary text-sm font-medium leading-snug transition-all duration-300 group-hover:text-white capitalize"
                >
                    {{ product?.quantity === 0 ? "out of stock" : $t("add to cart") }}
                </p>
            </button>
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
import BagIcon from "../icons/Bag.vue";
import { useAuth } from "../stores/AuthStore";
import { useBasketStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";
import { useTruncateText } from "../composables/useTruncateText";

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
    console.log('clicked')
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
