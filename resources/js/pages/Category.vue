<template>
    <div class="main-container pt-8 pb-12">

        <div class="text-slate-800 text-lg lg:text-3xl font-bold">{{ $t('All Categories') }}</div>

        <!-- categories -->
        <div class="grid  gap-3 sm:gap-6 items-start mt-6"
        :class="master.themeName == 'UltraMart' ? 'grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4' : 'grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6'"
        >
            <CategoryCardToggler :categoryData="category" v-for="category in categories" :key="category.id" />
            <div v-if="categories.length == 0" class="text-slate-950 text-xl font-medium leading-7">
                {{ $t('No Categories Found') }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import CategoryCardToggler from '../components/CategoryCardToggler.vue';
import { useMaster } from '../stores/MasterStore';


const master = useMaster()

const categories = ref([]);

onMounted(() => {
    fetchCategories();
    window.scrollTo(0, 0);
});

const fetchCategories = async () => {
    axios.get('/categories').then((response) => {
        categories.value = response.data.data.categories;
    })
};

</script>
