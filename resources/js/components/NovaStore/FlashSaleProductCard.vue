<template>
    <div
        class="w-full bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-gray-100 inline-flex flex-col justify-start items-start overflow-hidden group transition-all duration-500 hover:bg-[rgba(var(--color-primary-rgb),0.03)] hover:border-none hover:shadow-[0px_0px_8px_0px_rgba(81,175,91,0.24)] hover:outline hover:outline-green-200"
    >
        <div
            class="self-stretch h-40 md:h-60 relative inline-flex flex-col justify-start items-start gap-2.5 overflow-hidden"
        >
            <img
                class="h-full w-full object-cover group-hover:scale-110 transition-all duration-500"
                :src="props.product?.thumbnail"
            />
            <button
            @click="favoriteAddOrRemove"
                class="w-10 h-10 p-2.5 top-2 absolute bg-white rounded-3xl shadow-[0px_2px_4px_0px_rgba(0,0,0,0.08)] inline-flex justify-center items-center gap-2.5"
                :class="
                    masterStore.langDirection == 'rtl' ? 'left-2' : 'right-2'
                "
            >
                <RiHeart3Fill
                    v-if="props.product?.is_favorite"
                    class="w-[22px] text-primary-400"
                />
                <RiHeart3Line v-else class="w-[22px]" />
            </button>

            <div
                class="absolute top-4 bg-primary-400 h-4 w-28 text-center text-xs text-white"
                :class="
                    masterStore.langDirection == 'rtl'
                        ? '-right-7 rotate-45'
                        : '-left-7 -rotate-45'
                "
            >
                <div
                    class="text-white text-[7.78px] font-bold font-['Montserrat']"
                >
                    Flash Sale
                </div>
            </div>
        </div>
        <div
            class="self-stretch p-3 flex flex-col justify-start items-start gap-3"
        >
            <div
                class="self-stretch flex flex-col justify-start items-center gap-1"
            >
                <div
                    class="self-stretch justify-start text-primary text-xs font-normal font-['Albert_Sans'] leading-none"
                >
                    {{ product?.category }}
                </div>
                <RouterLink
                    :to="`/products/${product?.slug}`"
                    class="self-stretch inline-flex justify-start items-center gap-6"
                >
                    <div
                        class="flex-1 justify-start text-zinc-900 text-lg font-semibold font-['Lato'] leading-relaxed h-14 overflow-hidden"
                    >
                        {{ truncate(product?.name, 60) }}
                    </div>
                </RouterLink>
                <div
                    class="self-stretch inline-flex justify-between items-center"
                >
                    <div
                        class="h-5 rounded flex justify-center items-center gap-2.5"
                    >
                        <div class="flex justify-start items-center gap-0.5">
                            <RiStarFill class="w-[14px] text-warning" />
                            <div
                                class="flex justify-start items-center gap-0.5"
                            >
                                <div
                                    class="justify-start text-zinc-900 text-xs font-bold font-['Lato'] leading-tight"
                                >
                                    {{ product?.rating }}
                                </div>
                                <div
                                    class="justify-start text-slate-500 text-xs font-normal font-['Lato'] leading-none"
                                >
                                    ({{ product?.total_reviews }})
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="h-5 rounded flex justify-center items-center gap-2.5"
                    >
                        <div class="flex justify-start items-center gap-1">
                            <div
                                class="justify-start text-slate-500 text-xs font-normal font-['Lato'] leading-none"
                            >
                                {{ $t("Available Item") }}:
                            </div>
                            <div class="flex justify-start items-center">
                                <div
                                    class="justify-start text-zinc-900 text-xs font-bold font-['Lato'] leading-tight"
                                >
                                    {{ product?.total_sold }}/
                                </div>
                                <div
                                    class="justify-start text-zinc-900 text-xs font-bold font-['Lato'] leading-tight"
                                >
                                    {{ product?.quantity }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="self-stretch inline-flex justify-start items-center gap-3"
            >
                <div class="flex-1 h-10 flex justify-start items-center gap-2">
                    <div
                        class="inline-flex flex-col justify-center items-start"
                    >
                        <div
                            class="h-3.5 px-1 py-0.5 bg-red-500 rounded inline-flex justify-center items-center gap-2"
                        >
                            <div
                                v-if="props.product?.discount_percentage > 0"
                                class="justify-start text-white text-[8px] font-normal font-['Lato'] leading-none"
                            >
                                {{ props.product?.discount_percentage }}%
                                {{ $t("OFF") }}
                            </div>
                        </div>
                        <div
                            class="inline-flex justify-start items-center gap-0.5"
                        >
                            <div
                                class="justify-start text-primary text-lg font-bold font-['Lato'] leading-relaxed"
                            >
                                {{
                                    masterStore.showCurrency(
                                        props.product?.discount_price > 0
                                            ? props.product?.discount_price
                                            : props.product?.price
                                    )
                                }}
                            </div>
                            <div
                                class="justify-start text-gray-400 text-xs font-normal font-['Lato'] line-through leading-none"
                            >
                                {{
                                    masterStore.showCurrency(
                                        props.product?.price
                                    )
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="flex-1 h-10 px-2 py-2.5 bg-green-50 rounded outline outline-1 outline-offset-[-1px] outline-primary flex justify-center items-center gap-2.5 overflow-hidden group-hover:bg-primary group-hover:text-white transition-all duration-500 cursor-pointer"
                    v-if="props.product?.quantity > 0"
                    @click="addToBasket(props.product)"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 21 21"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="group-hover:fill-white fill-primary transition-all duration-500"
                    >
                        <path
                            d="M8.60205 19C8.60205 19.69 8.04205 20.25 7.35205 20.25C6.66305 20.25 6.09705 19.69 6.09705 19C6.09705 18.31 6.65204 17.75 7.34204 17.75H7.35205C8.04205 17.75 8.60205 18.31 8.60205 19ZM15.3521 17.75H15.342C14.652 17.75 14.097 18.31 14.097 19C14.097 19.69 14.6621 20.25 15.3521 20.25C16.0421 20.25 16.6021 19.69 16.6021 19C16.6021 18.31 16.0421 17.75 15.3521 17.75ZM20.037 6.49197L19.0231 12.658C18.7601 14.104 18.106 15.75 15.332 15.75H7.06604C5.70704 15.75 4.53599 14.735 4.34399 13.389L2.83398 2.82397C2.74598 2.21197 2.21505 1.75098 1.59705 1.75098H1.33203C0.918031 1.75098 0.582031 1.41498 0.582031 1.00098C0.582031 0.586977 0.918031 0.250977 1.33203 0.250977H1.59802C2.95702 0.250977 4.12807 1.26597 4.32007 2.61197L4.41199 3.25098H17.332C18.15 3.25098 18.9201 3.61097 19.4441 4.23897C19.9671 4.86597 20.184 5.68797 20.037 6.49197ZM18.291 5.19897C18.053 4.91397 17.7031 4.74997 17.3311 4.74997H4.625L5.82898 13.177C5.91698 13.789 6.44804 14.25 7.06604 14.25H15.332C16.929 14.25 17.318 13.654 17.545 12.403L18.5591 6.23596C18.6281 5.85796 18.529 5.48397 18.291 5.19897ZM13.832 8.74997H12.582V7.49997C12.582 7.08597 12.246 6.74997 11.832 6.74997C11.418 6.74997 11.082 7.08597 11.082 7.49997V8.74997H9.83203C9.41803 8.74997 9.08203 9.08597 9.08203 9.49997C9.08203 9.91397 9.41803 10.25 9.83203 10.25H11.082V11.5C11.082 11.914 11.418 12.25 11.832 12.25C12.246 12.25 12.582 11.914 12.582 11.5V10.25H13.832C14.246 10.25 14.582 9.91397 14.582 9.49997C14.582 9.08597 14.246 8.74997 13.832 8.74997Z"
                        />
                    </svg>
                    <div class="flex justify-center items-center gap-1">
                        <div
                            class="text-primary group-hover:text-white transition-all duration-500 text-sm font-medium font-['Lato'] leading-snug capitalize"
                        >
                            {{ $t("add to cart") }}
                        </div>
                    </div>
                </div>

                <div
                    class="flex-1 h-10 px-2 py-2.5 bg-green-50 rounded outline outline-1 outline-offset-[-1px] outline-primary flex justify-center items-center gap-2.5 overflow-hidden group-hover:bg-primary group-hover:text-white transition-all duration-500 disabled:cursor-not-allowed disabled:opacity-50"
                    v-else
                    disabled
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 21 21"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="group-hover:fill-white fill-primary transition-all duration-500"
                    >
                        <path
                            d="M8.60205 19C8.60205 19.69 8.04205 20.25 7.35205 20.25C6.66305 20.25 6.09705 19.69 6.09705 19C6.09705 18.31 6.65204 17.75 7.34204 17.75H7.35205C8.04205 17.75 8.60205 18.31 8.60205 19ZM15.3521 17.75H15.342C14.652 17.75 14.097 18.31 14.097 19C14.097 19.69 14.6621 20.25 15.3521 20.25C16.0421 20.25 16.6021 19.69 16.6021 19C16.6021 18.31 16.0421 17.75 15.3521 17.75ZM20.037 6.49197L19.0231 12.658C18.7601 14.104 18.106 15.75 15.332 15.75H7.06604C5.70704 15.75 4.53599 14.735 4.34399 13.389L2.83398 2.82397C2.74598 2.21197 2.21505 1.75098 1.59705 1.75098H1.33203C0.918031 1.75098 0.582031 1.41498 0.582031 1.00098C0.582031 0.586977 0.918031 0.250977 1.33203 0.250977H1.59802C2.95702 0.250977 4.12807 1.26597 4.32007 2.61197L4.41199 3.25098H17.332C18.15 3.25098 18.9201 3.61097 19.4441 4.23897C19.9671 4.86597 20.184 5.68797 20.037 6.49197ZM18.291 5.19897C18.053 4.91397 17.7031 4.74997 17.3311 4.74997H4.625L5.82898 13.177C5.91698 13.789 6.44804 14.25 7.06604 14.25H15.332C16.929 14.25 17.318 13.654 17.545 12.403L18.5591 6.23596C18.6281 5.85796 18.529 5.48397 18.291 5.19897ZM13.832 8.74997H12.582V7.49997C12.582 7.08597 12.246 6.74997 11.832 6.74997C11.418 6.74997 11.082 7.08597 11.082 7.49997V8.74997H9.83203C9.41803 8.74997 9.08203 9.08597 9.08203 9.49997C9.08203 9.91397 9.41803 10.25 9.83203 10.25H11.082V11.5C11.082 11.914 11.418 12.25 11.832 12.25C12.246 12.25 12.582 11.914 12.582 11.5V10.25H13.832C14.246 10.25 14.582 9.91397 14.582 9.49997C14.582 9.08597 14.246 8.74997 13.832 8.74997Z"
                        />
                    </svg>
                    <div class="flex justify-center items-center gap-1">
                        <div
                            class="text-primary group-hover:text-white transition-all duration-500 text-sm font-medium font-['Lato'] leading-snug capitalize"
                        >
                            {{ $t("add to cart") }}
                        </div>
                    </div>
                </div>
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
import { RouterLink, useRouter } from "vue-router";
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

<style scoped>
.productCard {
    position: relative;
    border: none;
    border-radius: var(--radius);
    background: var(--primary-500);
    -webkit-transition: var(--transition);
    transition: var(--transition);
    outline-color: transparent;
    outline-style: solid;
    outline-width: 1px;
    outline-offset: -1px;
    z-index: 1;
}
.productCard:hover {
    background: rgba(var(--color-primary-rgb), 0.03);
    border: none;
    box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.24);
    outline-color: #bbf7d0;
}

.productCard:hover .product__actions .rs-btn {
    background: var(--primary);
    border: 1px solid var(--primary);
    color: white;
}
.productCard .discount__badge {
    font-weight: var(--font-regular);
    font-size: 10px;
    line-height: 14px;
    color: white;
    background: var(--primary-400);
    border-radius: 4px;
    padding: 1px 7px;
}
.productCard .product__thumb {
    position: relative;
    width: 100%;
    height: 240px;
    overflow: hidden;
    display: block;
    border-radius: var(--radius) var(--radius) 0 0;
}
.productCard .product__thumb img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: top;
    object-position: top;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.productCard .product__thumb .meta__top {
    position: absolute;
    top: 8px;
    left: 8px;
    width: calc(100% - 16px);
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    z-index: 11;
}
.productCard .product__thumb .meta__top .wishlist {
    height: 32px;
    width: 32px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    background: white;
    -webkit-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1607843137);
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1607843137);
    border-radius: 50%;
}
.productCard .product__thumb .meta__top .wishlist input {
    display: none;
}
.productCard
    .product__thumb
    .meta__top
    .wishlist
    input:checked
    + label::before {
    content: "\ee0a";
    color: var(--primary-400);
}
.productCard .product__thumb .meta__top .wishlist label {
    font-size: 22px;
    cursor: pointer;
}
.productCard .product__thumb .meta__top .wishlist label::before {
    content: "\ee0b";
    font-family: "remixicon";
    color: var(--primary-600);
}
.productCard .product__availability {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    gap: 10px;
    position: absolute;
    bottom: 4px;
    left: 12px;
    width: calc(100% - 24px);
}
.productCard .product__availability .rating {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 2px;
    background: rgba(var(--color-white-rgb), 0.6);
    border-radius: 4px;
    padding: 0 7px;
}
.productCard .product__availability .rating .star {
    font-size: 14px;
    color: var(--primary-300);
}
.productCard .product__availability span {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 20px;
    color: #687387;
}
.productCard .product__info {
    padding: 12px;
}
.productCard .product__meta a {
    font-family: var(--font-albert);
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 16px;
    color: var(--primary);
    display: block;
    margin-bottom: 4px;
}
.productCard .product__title {
    font-weight: var(--font-semibold);
    font-size: 16px;
    line-height: 24px;
    color: var(--primary-600);
    height: 48px;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.productCard .product__title a {
    color: currentColor;
    display: block;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.productCard .product__price {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 2px;
    font-weight: var(--font-bold);
    font-size: 16px;
    line-height: 24px;
    color: var(--primary);
}
.productCard .product__price .old__price {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 16px;
    color: #b3bac6;
}
.productCard .product__unit {
    font-weight: var(--font-bolf);
    font-size: 14px;
    line-height: 20px;
    color: var(--primary-50);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 2px;
}
.productCard .product__unit small {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 20px;
}
.productCard .product__actions {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
}
.productCard .product__actions .btn__group {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
}
.productCard .product__actions .btn__group .rs-btn {
    font-size: 14px;
    line-height: 22px;
    font-weight: var(--font-medium);
    padding: 4px 10px;
}
.productCard.v2 {
    background: white;
}
.productCard.v2:hover {
    background: #f3faf3;
    border: 1px solid #c9e9cc;
    -webkit-box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.2392156863);
    box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.2392156863);
}
.productCard.v2 .product__thumb {
    height: 242px;
}
.productCard.v2 .product__thumb img {
    -o-object-position: center;
    object-position: center;
}
.productCard.v2 .product__thumb .wishlist {
    width: 40px;
    height: 40px;
}
.productCard.v2 .product__title {
    font-size: 18px;
    line-height: 28px;
    margin-bottom: 6px;
}
.productCard.v2 .product__availability {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    margin-bottom: 12px;
}
.productCard.v2 .product__availability .rating {
    padding: 0;
}
.productCard.v2 .product__availability .text-heading {
    font-weight: var(--font-bold);
}
.productCard.v2 .product__price {
    font-size: 18px;
    line-height: 26px;
}
.productCard.v2 .product__actions {
    margin-top: 0;
}
.productCard.v2 .product__actions .rs-btn {
    font-size: 14px;
    line-height: 22px;
    font-weight: var(--font-medium);
    padding: 7px 20px;
    border-radius: 4px;
}
.productCard.v2 .discount__badge {
    font-size: 8px;
    line-height: 12px;
    padding: 1px 5px;
}
</style>
