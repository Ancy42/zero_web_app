<template>
    <div class="rounded-lg md:rounded-2xl  border border-slate-200 transition-all duration-300 group bg-white overflow-hidden relative">
        <div class="w-full h-60 overflow-hidden relative">
            <div class="cursor-pointer w-full h-full" @click="goToBlogDetails()">
                <img :src="blog?.thumbnail"
                    class="w-full h-full group-hover:scale-110 transition duration-500 object-cover" loading="lazy" />
            </div>
            <div v-if="blog?.is_new" class="text-white px-1.5 py-0.5 rounded-lg bg-primary text-sm font-normal absolute top-2 left-2">
                {{ $t('New') }}
            </div>
        </div>

        <div class="p-4 cursor-pointer" @click="goToBlogDetails()">
            <div class="text-primary text-sm leading-tight">
                {{ blog?.category?.name }}
            </div>
            <div class="text-slate-900 text-xl font-bold mt-2 truncate">
                {{ blog?.title }}
            </div>

            <div class="text-slate-600 text-base font-normal mt-2 truncate">
                {{ blog?.description }}
            </div>

            <div class="flex items-center justify-between gap-2 mt-2">
                <div class="flex items-center gap-1">
                    <span class="text-slate-400 text-sm leading-tight">
                        {{ $t('By') }}
                    </span>
                    <span class="text-slate-900 text-sm">
                        {{ blog?.post_by?.name }}
                    </span>
                    <span class="text-slate-400 text-xs">
                        - {{ blog?.created_at }}
                    </span>
                </div>
                <div class="flex items-center gap-1 text-slate-800">
                    <EyeIcon class="w-5 h-5 text-slate-600" />
                    <span class="text-sm">
                        {{ blog?.total_views }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { EyeIcon } from '@heroicons/vue/24/outline';

const router = useRouter();

const props = defineProps({
    blog: Object
});

const goToBlogDetails = () => {
    router.push('/blog/' + props.blog?.slug);
}
</script>
