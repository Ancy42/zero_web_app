<template>
    <section
        class="h-full"
        :class="
            flattenedCategories.length > 0
                ? 'grid grid-cols-4 gap-[30px]'
                : 'flex justify-center items-center'
        "
    >
        <p
            v-if="flattenedCategories.length === 0"
            class="text-primary-200 text-sm"
        >
            {{$t("No category to show")}}
        </p>

        <div v-for="category in flattenedCategories" :key="category.id">
            <h2
                @click="router.push(`/categories/${category.id}`)"
                class="text-primary-600 border-b border-primary-100 mb-4 py-[7px] capitalize cursor-pointer transition-all duration-200 hover:text-primary-200"
            >
                {{ category.name }}
            </h2>
            <ul
                v-if="category.sub_categories?.length > 0"
                class="list-inside list-disc"
            >
                <li
                    v-for="subCategory in category.sub_categories"
                    @click="router.push(`/categories/${subCategory.id}`)"
                    :key="subCategory.id"
                    class="cursor-pointer transition-all duration-200 capitalize hover:text-primary-200 text-primary-50 leading-[30px]"
                >
                    <span class="">{{ subCategory.name }}</span>
                </li>
            </ul>

            <p v-else class="text-primary-200 text-sm">
            {{$t("No category to show")}}</p>
        </div>
    </section>
</template>

<script setup>
import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";

// Define props with validation and default value
const props = defineProps({
    subcategories: {
        type: Array,
        default: () => [],
        required: false,
    },
});

const router = useRouter();

// Function to flatten sub-categories
function flattenSubCategories(categories) {
    // Guard clause for invalid input
    if (!Array.isArray(categories)) return [];

    return categories.map((category) => {
        const flatSubs = [];

        function recurse(subs) {
            if (!Array.isArray(subs)) return;
            subs.forEach((sub) => {
                flatSubs.push({
                    id: sub.id,
                    name: sub.name,
                    thumbnail: sub.thumbnail,
                    total_products: sub.total_products,
                });
                if (sub.sub_categories?.length) {
                    recurse(sub.sub_categories);
                }
            });
        }

        recurse(category.sub_categories);

        return {
            ...category,
            sub_categories: flatSubs,
        };
    });
}

// Computed property for flattened categories
const flattenedCategories = computed(() =>
    flattenSubCategories(props.subcategories)
);
</script>
