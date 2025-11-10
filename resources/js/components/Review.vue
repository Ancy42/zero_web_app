<template>
    <div
        class="w-full p-4 bg-primary-500 rounded-2xl inline-flex flex-col justify-start items-start gap-4"
    >
        <div class="w-full inline-flex justify-start items-center gap-4">
            <img
                class="w-20 md:w-24 h-20 md:h-24 rounded-xl"
                :src="props.review?.customer_profile"
            />
            <div
                class="w-full flex flex-col md:flex-row justify-between items-start gap-3"
            >
                <div class="flex flex-col justify-center items-start gap-2">
                    <p
                        class="text-zinc-800 text-lg md:text-2xl font-semibold font-['Lato'] leading-tight md:leading-loose"
                    >
                        {{ props.review?.customer_name }}
                    </p>

                    <p
                        class="block md:hidden text-right text-zinc-800 text-sm font-normal font-['Inter'] leading-snug"
                    >
                        {{ props.review?.created_at }}
                    </p>

                    <div class="inline-flex justify-start items-center gap-1">
                        <!-- <RiStarFill
                            v-for="i in 5"
                            :key="i"
                            class="w-7 h-7"
                            :class="
                                i <= props.review?.rating
                                    ? 'text-primary-300'
                                    : 'text-[#e8e8e8]'
                            "
                        /> -->

                        <StarIcon
                            v-for="i in 5"
                            :key="i"
                            class="w-4 h-4 md:w-7 md:h-7"
                            :class="
                                i <= props.review?.rating
                                    ? 'text-primary-300'
                                    : 'text-[#e8e8e8]'
                            "
                        />
                        <p
                            class="text-slate-500 text-base md:text-xl font-semibold font-['Lato'] md:font-['Poppins'] leading-7 ml-[6px]"
                        >
                            {{ props.review?.rating?.toFixed(1) }}
                        </p>
                    </div>
                </div>
                <p
                    class="hidden md:block text-right justify-start text-zinc-800 text-sm font-normal font-['Inter'] leading-snug"
                >
                    {{ props.review?.created_at }}
                </p>
            </div>
        </div>
        <div
            class="w-full h-0 outline outline-1 outline-offset-[-0.50px] outline-gray-300"
        ></div>
        <div class="inline-flex justify-start items-start gap-2">
            <p
                class="flex-1 justify-start text-zinc-900 text-base font-medium font-['Lato'] leading-normal"
            >
                {{ props.review?.description }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { StarIcon } from "@heroicons/vue/24/solid";
import { RiStarFill } from "vue-remix-icons";

const props = defineProps({
    review: Object,
});
</script>

<style scoped>
.review__item {
    position: relative;
    background: var(--primary-500);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 24px;
}
.product__review .review__item:last-of-type {
    margin-bottom: 0;
}
.review__item .reviewer__info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 16px;
    border-bottom: 1px solid var(--primary-100);
    padding-bottom: 16px;
    margin-bottom: 16px;
}
.review__item .reviewer__info .thumb {
    width: 100px;
    height: 100px;
    border-radius: var(--radius-12);
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    background: #d9d9d9;
}
@media screen and (max-width: 991px) {
    .review__item .reviewer__info .thumb {
        width: 80px;
        height: 80px;
    }
}
@media screen and (max-width: 575px) {
    .review__item .reviewer__info .thumb {
        width: 70px;
        height: 70px;
    }
}
.review__item .reviewer__info .thumb img {
    width: 100%;
    height: 100%;
    border-radius: var(--radius-12);
    display: block;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center;
}
.review__item .reviewer__info .info .title {
    font-family: var(--font-default);
    font-weight: var(--font-semibold);
    font-size: 24px;
    line-height: 32px;
    color: var(--primary-600);
    margin-bottom: 8px;
}
@media screen and (max-width: 991px) {
    .review__item .reviewer__info .info .title {
        font-size: 18px;
        line-height: 20px;
    }
}
.review__item .reviewer__info .info .ratings {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 6px;
    font-size: 20px;
    color: #e8e8e8;
}
@media screen and (max-width: 991px) {
    .review__item .reviewer__info .info .ratings {
        font-size: 18px;
    }
}
.review__item .reviewer__info .info .ratings i.active {
    color: var(--color-yellow);
}
.review__item .reviewer__info .info .ratings small {
    font-family: var(--font-inter);
    font-weight: 600;
    font-size: 18px;
    line-height: 28px;
    color: var(--primary-50);
}
@media screen and (max-width: 991px) {
    .review__item .reviewer__info .info .ratings small {
        font-size: 16px;
    }
}
.review__item .reviewer__info .info .date {
    font-family: var(--font-inter);
    font-weight: 400;
    font-size: 14px;
    line-height: 22px;
    color: var(--primary-600);
    position: absolute;
    right: 16px;
    top: 16px;
}
@media screen and (max-width: 479px) {
    .review__item .reviewer__info .info .date {
        position: static;
        margin-top: 4px;
    }
}
.review__item .content {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
}
.review__item .content p {
    font-weight: var(--font-medium);
    font-size: 16px;
    line-height: 24px;
    margin: 0;
    color: var(--primary-600);
}
</style>
