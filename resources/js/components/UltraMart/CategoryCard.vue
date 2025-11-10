<template>
    <router-link :to="routeUrl"
        class="w-full p-1 lg:p-2 bg-primary-50 rounded-xl outline outline-1 outline-offset-[-1px] outline-neutral-100 inline-flex justify-start items-center gap-4"
    >
        <div
            class="flex-1  bg-white rounded-lg flex justify-start items-center gap-1 lg:gap-3"
        >
            <img
                class="w-16 h-16 lg:w-36 lg:h-36 p-2 rounded-lg"
                :src="props.category?.thumbnail"
            />
            <div
                class="flex-1 inline-flex flex-col justify-center items-start gap-[5px]"
            >
                <p
                    class="self-stretch justify-start text-primary text-sm lg:text-lg font-semibold lg:font-bold font-['Inter'] leading-snug lg:leading-relaxed"
                >
                    {{ category?.name }}
                </p>
                <p
                    class="justify-start text-slate-500 text-xs lg:text-sm font-normal font-['Inter'] leading-tight lg:leading-snug"
                >
                    {{ category?.total_products }} {{$t('Items')}}
                </p>
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { useRoute } from "vue-router";
import { ref, onMounted } from "vue";

const route = useRoute();

const props = defineProps({
    category: Object,
});

const routeUrl = ref(`/categories/${props.category?.id}`);

onMounted(() => {
    if (route.name === "shop-detail") {
        routeUrl.value = `/shops/${route.params.id}/categories/${props.category?.id}`;
    }
});
</script>

<style scoped>
.category__item {
    background: var(--primary-500);
    border: 1px solid var(--primary-500);
    padding: 8px;
    display: block;
    border-radius: var(--radius-12);
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.category__item:hover {
    background: #f3faf3;
    border-color: var(--primary);
    -webkit-box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.3215686275);
    box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.3215686275);
}
.category__item:hover .category__thumb img {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
.category__item .category__wrap {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    background: white;
    border-radius: var(--radius);
    gap: 8px;
}
.category__item .category__thumb {
    width: 100%;
    height: 140px;
    display: inline-block;
    border-radius: var(--radius);
    overflow: hidden;
    -webkit-transition: var(--transition);
    transition: var(--transition);
    background: #efefef;
}
.category__item .category__thumb img {
    width: 100%;
    height: 100%;
    -o-object-fit: contain;
    object-fit: contain;
    border-radius: var(--radius);
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.category__item .category__content {
    background: white;
    padding: 8px;
    border-radius: var(--radius);
}
.category__item .category__content h4 {
    font-weight: var(--font-semibold);
    font-size: 16px;
    line-height: 24px;
    color: var(--color-heading);
    margin-bottom: 2px;
    -webkit-transition: var(--transition);
    transition: var(--transition);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}
/* .category__item .category__content p {
    font-weight: var(--font-semibold);
    font-size: 12px;
    line-height: 20px;
    color: var(--color-heading);
    margin: 0;
} */
</style>
