<template>
    <div>
        <BlogHeader :categories="categories" :isLoading="isLoadingCategory" :categoryId="categoryID"
            @changeCategory="changeCategory" />

        <div class="main-container py-4 pb-8 md:pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-3 sm:gap-6 items-start">
                <BlogCard v-if="!isLoading" v-for="blog in blogs" :key="blog.id" :blog="blog" />

                <!-- loading -->
                <SkeletonLoader v-else v-for="i in 8" :key="i" class="w-full h-[300px] sm:h-[340px] rounded-lg md:rounded-2xl" />
            </div>

            <div v-if="blogs.length == 0" class="flex justify-center items-center w-full mt-8">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    <div class="flex items-center bg-slate-100 justify-center w-40 h-40 rounded-full">
                        <DocumentTextIcon class="w-20 h-20 text-slate-400" />
                    </div>
                    <p class="mt-3 md:mt-6 text-xl md:text-2xl text-slate-600 italic text-center">
                        {{ $t("No blogs found") }}
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="blogs.length > 0" class="flex justify-between items-center w-full mt-8 gap-4 flex-wrap">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    {{ $t("Showing") }} {{ perPage * (currentPage - 1) + 1 }}
                    {{ $t("to") }}
                    {{ perPage * (currentPage - 1) + blogs.length }}
                    {{ $t("of") }} {{ total }} {{ $t("results") }}
                </div>
                <div>
                    <vue-awesome-paginate :total-items="total" :items-per-page="perPage" type="button"
                        :max-pages-shown="5" v-model="currentPage" :hide-prev-next-when-ends="true"
                        @click="onClickHandler" />
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useMaster } from '../stores/MasterStore';
import BlogHeader from '../components/BlogHeader.vue';
import BlogCard from '../components/BlogCard.vue';
import { DocumentTextIcon } from '@heroicons/vue/24/outline';
import SkeletonLoader from '../components/SkeletonLoader.vue';

const master = useMaster();
const isLoading = ref(true);
const isLoadingCategory = ref(false);
const changeItem = ref(false);

onMounted(() => {
    fetchBlogs();
    scrollToTop();
});

const blogs = ref([]);
const categories = ref([]);

const currentPage = ref(1);
const perPage = 12;
const categoryID = ref(null);
const total = ref(0);

const changeCategory = (id) => {
    categoryID.value = id;
    currentPage.value = 1;
    changeItem.value = true;
    fetchBlogs();
}

const onClickHandler = (page) => {
    currentPage.value = page;
    changeItem.value = true;
    fetchBlogs();
};

const fetchBlogs = () => {
    isLoading.value = true;
    if (!changeItem.value) {
        isLoadingCategory.value = true;
    }
    axios.get('/blogs', {
        params: {
            page: currentPage.value,
            per_page: perPage,
            category_id: categoryID.value
        },
        headers: {
            'Accept-Language': master.locale || 'en',
        }
    }).then((response) => {
        total.value = response.data.data.total;
        blogs.value = response.data.data.blogs;
        categories.value = response.data.data.categories;
        setTimeout(() => {
            isLoading.value = false;
            isLoadingCategory.value = false;
            changeItem.value = false;
            scrollToTop();
        }, 100);
    }).catch((error) => {
        isLoading.value = false;
        changeItem.value = false;
        isLoadingCategory.value = false;
    });
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}

</script>
