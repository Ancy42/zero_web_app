<template>
    <div class="rounded-2xl overflow-hidden relative group">
        <div
            class="w-full h-52 overflow-hidden bg-white relative rounded-2xl rounded-b-none border-b-0 border border-primary"
        >
            <!-- product image  -->
            <RouterLink :to="`/products/${props.product?.slug}`">
                <img
                    :src="product?.thumbnail"
                    alt="product"
                    class="w-full h-full object-cover"
                />
            </RouterLink>

            <!-- add to wishlist  -->
            <div
                @click="favoriteAddOrRemove"
                class="absolute top-2 w-10 h-10 p-2.5 bg-white lg:bg-gray-300 rounded-xl backdrop-blur-sm inline-flex justify-center items-center gap-2.5 cursor-pointer opacity-0 scale-0 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100"
                :class="
                    masterStore.langDirection == 'rtl' ? 'left-2' : 'right-2'
                "
            >
                <RiHeart3Fill
                    v-if="product?.is_favorite"
                    class="w-6 h-6 text-primary-400"
                />
                <RiHeart3Line v-else class="w-6 h-6 text-black lg:text-white" />
            </div>

            <!-- badge  -->
            <div
                v-if="isFlashSale"
                class="absolute top-4 bg-error h-4 w-28 text-center text-xs text-white opacity-100 group-hover:opacity-0 transition-all duration-500"
                :class="
                    masterStore.langDirection == 'rtl'
                        ? '-right-7 rotate-45'
                        : '-left-7 -rotate-45'
                "
            >
                <p
                    class="text-white text-[6.47px] font-bold font-['Montserrat']"
                >
                    Flash Sale
                </p>
            </div>

            <div
                v-if="product?.quantity <= 0"
                class="absolute bottom-0 w-full left-0 h-10 px-2 py-2.5 bg-gray-300/80 flex justify-center items-center"
            >
                <p
                    class="text-zinc-900 text-sm font-semibold font-['Inter'] leading-snug"
                >
                    Out Off Stock
                </p>
            </div>
        </div>

        <div>
            <RouterLink :to="`/products/${props.product?.slug}`"
                class="p-3 space-y-2 relative z-10 bg-white pb-8 rounded-2xl rounded-t-none border-t-0 border border-primary block"
            >
                <!-- product description  -->
                <div
                    class="text-black text-sm lg:text-base font-normal leading-normal h-10 lg:h-12 overflow-hidden"
                >
                    {{ truncate(product?.name, 60) }}
                </div>

                <!-- price and discount  -->
                <div class="flex justify-start items-center gap-1">
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
                        class="text-gray-400 text-lg font-light line-through leading-relaxed"
                    >
                        {{ masterStore.showCurrency(product?.price) }}
                    </p>
                    <button
                        v-if="product?.discount_price > 0"
                        class="ml-auto w-auto h-6 px-2.5 py-0.5 lg:px-2.5 lg:py-0.5 bg-red-500 rounded inline-flex justify-center items-center gap-2"
                    >
                        <p
                            class="text-white text-[10px] lg:text-xs font-medium leading-tight"
                        >
                            {{ product?.discount_percentage }}%
                            {{ $t("OFF") }}
                        </p>
                    </button>
                </div>

                <!-- ratings  -->
                <div class="flex justify-between items-center">
                    <div class="flex justify-start items-center gap-1">
                        <RiStarFill class="w-[14px] text-warning" />

                        <p
                            class="text-slate-950 text-xs lg:text-sm font-semibold leading-tight lg:leading-snug"
                        >
                            {{ product?.rating }}
                        </p>

                        <p
                            class="text-slate-500 text-xs lg: font-normal leading-tight lg:leading-snug"
                        >
                            ({{ product?.total_reviews }})
                        </p>
                    </div>
                    <div class="flex justify-end items-center gap-1">
                        <img
                            src="../../../../public/assets/icons/box-tick2.svg"
                            alt=""
                        />
                        <p
                            class="text-black text-xs lg:text-sm font-semibold leading-tight lg:leading-snug"
                        >
                            {{ product?.total_sold }}
                        </p>
                        <p
                            class="justify-start text-slate-500 text-xs lg:text-sm font-normal leading-tight lg:leading-snug"
                        >
                            {{ $t("Sold") }}
                        </p>
                    </div>
                </div>
            </RouterLink>
            
            <div
                v-if="product?.quantity > 0"
                class="text-black relative z-10 flex justify-center items-center gap-3 -mt-5"
            >
                <button
                    @click="addToBasket(props.product)"
                    class="w-fit h-10 px-2.5 py-2.5 max-w-10 group-hover:max-w-[200px] transition-all duration-700 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500 inline-flex justify-start items-center gap-2.5 overflow-hidden"
                >
                    <div
                        class="w-6 h-6 relative flex justify-center items-center"
                    >
                        <svg
                            width="21"
                            height="21"
                            viewBox="0 0 21 21"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="fill-primary transition-all duration-700"
                        >
                            <path
                                d="M8.60205 19C8.60205 19.69 8.04205 20.25 7.35205 20.25C6.66305 20.25 6.09705 19.69 6.09705 19C6.09705 18.31 6.65204 17.75 7.34204 17.75H7.35205C8.04205 17.75 8.60205 18.31 8.60205 19ZM15.3521 17.75H15.342C14.652 17.75 14.097 18.31 14.097 19C14.097 19.69 14.6621 20.25 15.3521 20.25C16.0421 20.25 16.6021 19.69 16.6021 19C16.6021 18.31 16.0421 17.75 15.3521 17.75ZM20.037 6.49197L19.0231 12.658C18.7601 14.104 18.106 15.75 15.332 15.75H7.06604C5.70704 15.75 4.53599 14.735 4.34399 13.389L2.83398 2.82397C2.74598 2.21197 2.21505 1.75098 1.59705 1.75098H1.33203C0.918031 1.75098 0.582031 1.41498 0.582031 1.00098C0.582031 0.586977 0.918031 0.250977 1.33203 0.250977H1.59802C2.95702 0.250977 4.12807 1.26597 4.32007 2.61197L4.41199 3.25098H17.332C18.15 3.25098 18.9201 3.61097 19.4441 4.23897C19.9671 4.86597 20.184 5.68797 20.037 6.49197ZM18.291 5.19897C18.053 4.91397 17.7031 4.74997 17.3311 4.74997H4.625L5.82898 13.177C5.91698 13.789 6.44804 14.25 7.06604 14.25H15.332C16.929 14.25 17.318 13.654 17.545 12.403L18.5591 6.23596C18.6281 5.85796 18.529 5.48397 18.291 5.19897ZM13.832 8.74997H12.582V7.49997C12.582 7.08597 12.246 6.74997 11.832 6.74997C11.418 6.74997 11.082 7.08597 11.082 7.49997V8.74997H9.83203C9.41803 8.74997 9.08203 9.08597 9.08203 9.49997C9.08203 9.91397 9.41803 10.25 9.83203 10.25H11.082V11.5C11.082 11.914 11.418 12.25 11.832 12.25C12.246 12.25 12.582 11.914 12.582 11.5V10.25H13.832C14.246 10.25 14.582 9.91397 14.582 9.49997C14.582 9.08597 14.246 8.74997 13.832 8.74997Z"
                            />
                        </svg>
                    </div>

                    <p
                        class="text-nowrap text-primary text-sm leading-tight transition-all duration-700"
                    >
                        {{ $t("Add to Cart") }}
                    </p>
                </button>

                <button
                    @click="buyNow"
                    data-property-1="Bt Icon"
                    class="w-fit h-10 px-2.5 py-2.5 max-w-10 group-hover:max-w-[200px] transition-all duration-700 bg-primary rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500 inline-flex justify-start items-center gap-2.5 overflow-hidden"
                >
                    <img
                        src="../../../../public/assets/icons/shopping-bag.svg"
                        alt=""
                        class="w-[21px] h-[21px]"
                    />

                    <p
                        class="text-nowrap text-white text-sm leading-tight transition-all duration-700"
                    >
                        {{ $t("Buy Now") }}
                    </p>
                </button>
            </div>
        </div>

        <!-- <div
            class="absolute top-0 right-0 w-full h-full -translate-y-5 rounded-2xl overflow-hidden bg-white z-0"
        ></div> -->
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

const router = useRouter();

const masterStore = useMaster();

const basketStore = useBasketStore();
const authStore = useAuth();

const toast = useToast();

const { truncate } = useTruncateText();

const props = defineProps({
    product: Object,
    isFlashSale: {
        type: Boolean,
        default: false,
    },
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
