<template>
    <router-link
        :to="routeUrl"
        class="w-full h-full rounded-xl bg-gray-100 p-2 transition-all duration-200 group relative overflow-hidden"
    >
        <div
            class="w-full h-full max-h-36 rounded-lg relative overflow-hidden z-10 bg-white"
        >
            <img
                v-if="!isDefault"
                class="w-full h-full object-cover relative z-0 scale-100 transition-all duration-700 ease-in-out group-hover:scale-110"
                :src="props.category?.thumbnail"
                alt="icon"
            />
            <img
                v-else
                class="w-full h-full object-cover relative z-0 scale-100 transition-all duration-700 ease-in-out group-hover:scale-110"
                src="../../../../public/assets/images/homepage/default-cat.png"
                alt="icon"
            />

            <span
                v-if="!isDefault"
                class="absolute top-0 right-0 w-full h-full bg-gradient-from-t bg-gradient-to-b from-transparent to-gray-800/70 z-10 flex justify-start items-end p-2"
            >
                <p
                    class="text-white text-sm md:text-base font-bold font-['Lexend'] leading-tight md:leading-normal"
                >
                    {{ category?.name }}
                </p>
            </span>

            <span
                v-else
                class="absolute top-0 right-0 w-full h-full bg-gradient-from-t bg-gradient-to-b from-transparent to-gray-800/70 z-10 flex flex-col justify-end items-center gap-2 p-2"
            >
                <div v-if="isDefault">
                    <img
                        src="../../../../public/assets/icons/widgets.svg"
                        alt=""
                    />
                </div>
                <p
                    class="text-white text-sm md:text-base font-bold font-['Lexend']  leading-tight md:leading-normal inline-flex justify-between items-center w-full"
                >
                    View All Category
                    <ArrowRightIcon class="w-5 h-5 text-white" />
                </p>
            </span>
        </div>

        <div
            class="absolute bottom-0 right-0 w-full h-full bg-gradient-from-t bg-gradient-to-b from-white to-red-400 transition-all duration-200 translate-y-full group-hover:translate-y-0 z-0"
        ></div>
    </router-link>
</template>

<script setup>
import { useRoute } from "vue-router";
import { ref, onMounted } from "vue";
import { ArrowRightIcon } from "@heroicons/vue/24/outline";

const route = useRoute();

const props = defineProps({
    category: Object,
    isDefault: {
        type: Boolean,
        default: false,
    },
});

const routeUrl = ref(`/categories/${props.category?.id}`);

onMounted(() => {
    if (route.name === "shop-detail") {
        routeUrl.value = `/shops/${route.params.id}/categories/${props.category?.id}`;
    }
    if (props.isDefault) {
        routeUrl.value = '/categories'
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
