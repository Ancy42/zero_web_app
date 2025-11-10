<template>
    <div class="shopCard">
        <div class="thumb z-0">
            <img :src="props.shop?.banner" alt="shop" />
            <div
                v-if="props.shop?.shop_status === 'Online'"
                class="badges badges-green"
            >
                {{ props.shop?.shop_status }}
            </div>
        </div>
        <div
            class="flex flex-col gap-2 w-full h-auto absolute left-0 bottom-0 z-10 p-4"
        >
            <div
                class="w-20 h-20 relative rounded-2xl overflow-hidden border border-white bg-white"
            >
                <img
                    :src="props.shop?.logo"
                    alt="s-logo"
                    class="w-full h-full"
                />
            </div>
            <div class="flex justify-between items-end">
                <div class="space-y-4">
                    <p
                        class="flex justify-start items-center gap-2 text-white text-xl font-bold font-['Lato'] leading-7"
                    >
                        {{ truncate(shop.name, 60) }}
                        <span
                            class="inline-block  rounded-lg text-xs px-1 py-0.5 font-['Lato'] text-white"
                            :class="shop.shop_status === 'Online' ? 'bg-green-500' : 'bg-red-500'"
                        >
                            {{ shop.shop_status }}
                        </span>
                    </p>

                    <div class="flex justify-start items-center gap-2">
                        <div
                            class="px-1 py-0.5 bg-white/20 rounded backdrop-blur-sm w-fit flex justify-start items-center gap-1"
                        >
                            <RiStarFill class="w-[14px] text-amber-500" />
                            <span class="text-white text-xs font-bold">{{
                                props.shop?.rating?.toFixed(1)
                            }}</span>
                            <span
                                class="text-white/50 text-xs font-normal font-['Lato'] leading-tight"
                            >
                                ({{ props.shop?.total_reviews }})
                            </span>
                        </div>

                        <div
                            class="px-1 py-0.5 bg-white/20 rounded backdrop-blur-sm w-fit flex justify-start items-center gap-1"
                        >
                            <span
                                class="text-white text-xs font-normal font-['Lato'] leading-tight"
                                >Sold:
                            </span>
                            <span
                                class="text-white/50 text-xs font-normal font-['Lato'] leading-tight"
                            >
                                -/{{ props.shop?.total_products }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <router-link
                        :to="`/shops/${props.shop?.id}`"
                        class="w-10 px-2 py-1.5 bg-white/25 rounded outline outline-1 outline-offset-[-1px] outline-white/30 backdrop-blur-sm inline-flex justify-center items-center gap-2"
                    >
                        <ArrowRightIcon class="w-6 h-6 text-white" />
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { StarIcon, ArrowRightIcon } from "@heroicons/vue/24/solid";
import {
    RiHomeLine,
    RiStarFill,
    RiHeart3Line,
    RiHeart3Fill,
    RiArrowRightLine,
} from "vue-remix-icons";
import { useMaster } from "../../stores/MasterStore";
import { useTruncateText } from "../../composables/useTruncateText";


const props = defineProps({
    shop: Object,
});

const master = useMaster();
const { truncate } = useTruncateText();
</script>

<style scoped>
.shopCard {
    position: relative;
}
.shopCard:hover .thumb img {
    -webkit-transform: scale(1.05);
    transform: scale(1.05);
}
.shopCard:hover .content .title a {
    color: var(--primary);
}
.shopCard .thumb {
    position: relative;
    height: 260px;
    border-radius: 16px;
    overflow: hidden;
    display: block;
}
.shopCard .thumb::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: -webkit-gradient(
        linear,
        left top,
        left bottom,
        from(rgba(0, 0, 0, 0)),
        to(rgba(0, 0, 0, 0.82))
    );
    background: linear-gradient(
        180deg,
        rgba(0, 0, 0, 0) 0%,
        rgba(0, 0, 0, 0.82) 100%
    );
    border-radius: inherit;
    z-index: 1;
}
.shopCard .thumb img {
    width: 100%;
    height: 100%;
    display: block;
    -o-object-fit: cover;
    object-fit: cover;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCard .thumb .badges {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 20px;
    color: white;
    background: var(--primary);
    -webkit-backdrop-filter: blur(8px);
    backdrop-filter: blur(8px);
    border-radius: 0px 0 0 16px;
    padding: 0px 9px;
    position: absolute;
    top: 0;
    right: 0;
    z-index: 2;
}
.shopCard__content {
    position: absolute;
    bottom: 0;
    padding: 12px;
    z-index: 2;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 8px;
    width: 100%;
}
@media screen and (max-width: 1199px) {
    .shopCard__content {
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
    }
}
.shopCard__content .shopLogo {
    width: 80px;
    height: 80px;
    aspect-ratio: 1/1;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    border-radius: var(--radius-16);
    position: relative;
}
@media screen and (max-width: 1199px) {
    .shopCard__content .shopLogo {
        width: 64px;
        height: 64px;
    }
}
.shopCard__content .shopLogo img {
    width: 100%;
    height: 100%;
    display: block;
    -o-object-fit: cover;
    object-fit: cover;
    border-radius: var(--radius-16);
}
.shopCard__content .content__flex {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    gap: 10px;
    width: 100%;
}
.shopCard__content .content .title {
    font-weight: var(--font-bold);
    font-size: 20px;
    line-height: 28px;
    color: white;
    -webkit-transition: var(--transition);
    transition: var(--transition);
    margin: 0;
}
@media screen and (max-width: 575px) {
    .shopCard__content .content .title {
        font-size: 18px;
        line-height: 24px;
    }
}
.shopCard__content .content .title a {
    color: currentColor;
    display: block;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCard__content .content .meta {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
}
.shopCard__content .content .meta .rating {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 2px;
    background: rgba(var(--color-white-rgb), 0.16);
    border-radius: 4px;
    padding: 0 7px;
    -webkit-backdrop-filter: blur(8px);
    backdrop-filter: blur(8px);
}
.shopCard__content .content .meta .rating .star {
    color: var(--primary-300);
}
.shopCard__content .content .meta span {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 20px;
    color: rgba(var(--color-white-rgb), 0.5);
}
.shopCard__content .btn__group .solid__btn {
    font-size: 14px;
    line-height: 22px;
    font-weight: var(--font-regular);
    color: var(--primary);
    background: rgba(var(--color-white-rgb), 0.24);
    -webkit-backdrop-filter: blur(8px);
    backdrop-filter: blur(8px);
    border: 1px solid var(--primary);
    padding: 4px 15px;
    border-radius: 4px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCard__content .btn__group .solid__btn:hover {
    background: var(--primary);
    color: white;
}

.shopCards {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCards:hover .thumb img {
    -webkit-transform: scale(1.05);
    transform: scale(1.05);
}
.shopCards:hover .content .title a {
    color: var(--color-primary-300);
}
.shopCards .thumb {
    position: relative;
    aspect-ratio: 16/4.2;
}
.shopCards .thumb img {
    width: 100%;
    height: 100%;
    display: block;
    -o-object-fit: cover;
    object-fit: cover;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCards .thumb .badges {
    position: absolute;
    top: 8px;
    right: 8px;
}
.shopCards__content {
    background: var(--color-primary-900);
    padding: 16px;
    padding-top: 0;
}
.shopCards__content .shopLogo {
    width: 64px;
    height: 64px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    background: var(--color-state-300);
    border-radius: 50%;
    position: relative;
    margin-top: -32px;
}
.shopCards__content .shopLogo img {
    width: 100%;
    height: 100%;
    display: block;
    border-radius: 50%;
}
.shopCards__content .content {
    margin-top: 12px;
}
.shopCards__content .content .title {
    font-weight: var(--font-bold);
    font-size: 16px;
    line-height: 24px;
    color: white;
    -webkit-transition: var(--transition);
    transition: var(--transition);
    margin: 0;
}
.shopCards__content .content .title a {
    color: currentColor;
    display: block;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.shopCards__content .content .meta {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-top: 10px;
    gap: 10px 36px;
}
.shopCards__content .content .meta span {
    position: relative;
    font-weight: var(--font-regular);
    font-size: 14px;
    line-height: 20px;
    color: var(--color-primary-300);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 8px;
}
.shopCards__content .content .meta span::after {
    content: "";
    position: absolute;
    height: 15px;
    width: 1px;
    background: #69bef1;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    right: -18px;
}
.shopCards__content .content .meta span:last-of-type::after {
    display: none;
}
.shopCards__content .content .meta .star {
    color: white;
}
.shopCards__content .content .meta .star i {
    color: var(--primary-300);
}
.shopCards__content .content .btn__group {
    margin-top: 12px;
}
.shopCards__content .content .btn__group .solid__btn {
    font-size: 14px;
    line-height: 20px;
    color: white;
}
.shopCards__content .content .btn__group .solid__btn:hover {
    color: var(--color-primary-300);
}
.shopCards.v2 {
    border: 1px solid var(--color-state-100);
}
.shopCards.v2 .thumb {
    position: relative;
    aspect-ratio: 16/5.4;
}
.shopCards.v2 .shopCard__content {
    background: white;
}
.shopCards.v2 .shopCard__content .shopLogo {
    height: 88px;
    width: 88px;
    margin-top: -44px;
    -webkit-box-shadow: 0px 10px 10px -5px rgba(0, 0, 0, 0.0196078431),
        0px 20px 25px -5px rgba(0, 0, 0, 0.0509803922);
    box-shadow: 0px 10px 10px -5px rgba(0, 0, 0, 0.0196078431),
        0px 20px 25px -5px rgba(0, 0, 0, 0.0509803922);
}
.shopCards.v2 .shopCard__content .content .title {
    font-weight: var(--font-bold);
    font-size: 18px;
    line-height: 24px;
    letter-spacing: 2%;
    color: var(--color-state-900);
}
.shopCards.v2 .shopCard__content .content .meta {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
}
.shopCards.v2 .shopCard__content .content .meta span {
    position: relative;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-state-500);
}
.shopCards.v2 .shopCard__content .content .meta span span {
    gap: 2px;
}
.shopCards.v2 .shopCard__content .content .meta span b {
    color: var(--color-state-800);
}
.shopCards.v2 .shopCard__content .content .meta span::after {
    background: var(--color-state-300);
    right: -18px;
}
.shopCards.v2 .shopCard__content .content .btn__group .rs-btn {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 4px;
    padding: 11px 30px;
}
.shopCards.v2 .shopCard__content .content .btn__group .rs-btn i {
    font-size: 18px;
}
.shopCards.v2:hover {
    border-color: var(--color-primary-200);
}
.shopCards.v2:hover .content .title a {
    color: var(--color-primary-700);
}
.shopCards.v2:hover .content .btn__group .rs-btn {
    background: var(--color-primary-900);
    color: white;
}
</style>
