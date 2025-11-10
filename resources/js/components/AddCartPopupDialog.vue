<template>
    <div class="flex gap-3">
        <img :src="product?.thumbnail ? product?.thumbnail : product?.thumbnails[0]?.thumbnail" alt=""
            class="w-24 h-24 object-cover rounded-md">
        <div class="flex flex-col gap-1">
            <div class="text-slate-950 text-base font-medium leading-normal max-w-64 truncate">{{ product?.name }}</div>
            <div class="text-slate-600 text-sm font-normal leading-tight">{{ t('has been added to cart') }}</div>
            <button @click.stop="clicked"
                class="py-2 px-3 h-10 bg-primary rounded-lg text-white text-sm font-normal w-fit">
                {{ t('View In Cart') }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { useMaster } from '../stores/MasterStore';
import localization from '../localization';
const t = localization.i18n.global.t;

const masterStore = useMaster();

const props = defineProps({
    product: Object
});

const clicked = () => {
    masterStore.basketCanvas = true
}

</script>

<style>
.vue-toastification-alert {
    background-color: #fff !important;
    padding: 12px !important;
}

.vue-toastification-alert .Vue-Toastification__close-button {
    color: black;
}
</style>
