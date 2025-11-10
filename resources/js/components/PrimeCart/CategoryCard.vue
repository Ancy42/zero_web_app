<template>
    <router-link
        :to="routeUrl"
        class="w-full inline-flex flex-col justify-start items-center gap-2"
    >
        <div
            class="h-36 w-full rounded-tl-3xl rounded-tr-3xl rounded-bl-md rounded-br-md overflow-hidden border border-gray-300"
        >
            <img
                v-if="!isDefault"
                class="w-full h-full object-cover"
                :src="props.category?.thumbnail"
                alt="icon"
            />
            <img
                v-else
                class="w-full h-full object-cover"
                src="../../../../public/assets/images/homepage/default-cat.png"
                alt="icon"
            />
        </div>
        <div
            class="w-full text-center justify-start text-zinc-900 text-base font-medium font-['Montserrat'] leading-normal"
        >
            {{ truncate(category?.name, 30) }}
        </div>
    </router-link>
</template>

<script setup>
import { useRoute } from "vue-router";
import { ref, onMounted } from "vue";
import { useTruncateText } from "../../composables/useTruncateText";

const route = useRoute();
const { truncate } = useTruncateText();

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
