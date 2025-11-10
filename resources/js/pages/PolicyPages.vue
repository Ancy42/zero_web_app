<template>
    <div class="main-container py-6">
        <div class="text-xl md:text-2xl font-bold text-slate-800 pb-2 md:pb-3 border-b">
            {{ content?.title }}
        </div>
        <div class="mt-6 prose max-w-none w-full" v-html="content?.description"></div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const slug = ref(route.params.slug);

const content = ref({});

watch(route, () => {
    slug.value = route.params.slug;
    fetchData();
});

onMounted(() => {
    fetchData();
    window.scrollTo(0, 0);
});

const fetchData = () => {
    window.scrollTo(0, 0);
    axios.get('/legal-pages/'+slug.value).then((response) => {
        content.value = response.data.data.content;
    });
}

</script>
