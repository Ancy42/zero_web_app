<template>
    <router-link :to="routeUrl"
        class="w-full p-2 transition-all duration-300 border border-transparent group hover:border-green-500 bg-neutral-100 hover:bg-green-300/10 rounded-xl inline-flex justify-center items-center gap-4"
    >
        <div
            class="flex-1 pb-2 bg-white rounded-lg inline-flex flex-col justify-center items-center gap-2"
        >
            <img
                class="h-36 w-full rounded-lg object-cover"
                :src="props.category?.thumbnail"
                alt="icon"
            />
            <div
                class="w-full flex flex-col justify-center items-center gap-0.5"
            >
                <div
                    class="w-full text-center justify-start text-zinc-900 text-base font-semibold font-['Lato'] leading-normal"
                >
                    {{ category?.name }}
                </div>
                <div
                    class="text-slate-500 text-xs font-semibold font-['Lato'] leading-tight"
                >
                    {{ category?.total_products }} {{$t('Items')}}
                </div>
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
