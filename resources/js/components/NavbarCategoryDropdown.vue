<template>
    <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-1"
    >
        <PopoverPanel
            class="absolute pb-6 top-16 left-0  z-10 mt-0 flex "
        >
            <PopoverButton
                as="div"
                class="w-auto p-5 bg-white "
            >
                <!-- categories  -->
                <div
                    class="flex flex-col p-4 bg-primary-500 rounded-md gap-[10px] max-h-[505px] overflow-y-scroll scrollbar-style"
                >
                    <RouterLink
                        v-for="category in allCategories"
                        :to="`/categories/${category.id}`"
                        :key="category.id"
                        class="flex justify-between items-center p-[11px] gap-[10px] cursor-pointer transition-all duration-300 border border-transparent hover:border-primary rounded-[8px] bg-white group"
                        @mouseenter="
                            currentSubcategory = category.sub_categories
                        "
                    >
                        <div class="w-10 h-10 rounded overflow-hidden">
                            <img
                                :src="category.thumbnail"
                                alt=""
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <p class="text-sm leading-[22px] mr-auto">
                            {{ category.name }}
                        </p>

                        <div
                            class="-ml-auto"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'rotate-180'
                                    : ''
                            "
                        >
                            <ChevronRightIcon
                                class="w-6 h-6 text-primary-600"
                            />
                        </div>
                    </RouterLink>
                </div>
            </PopoverButton>
        </PopoverPanel>
    </transition>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { useMaster } from "../stores/MasterStore";
import { ChevronRightIcon } from "@heroicons/vue/20/solid";
import { onMounted, ref } from "vue";
import { getCategories } from "../lib/dummyData";
import SubcategoryList from "./SubcategoryList.vue";
import axios from "axios";

// apis
const categoryApi = "/categories";

// stores
const master = useMaster();

// variables
const isCategoryLoading = ref(true);
const currentSubcategory = ref(null);
const allCategories = ref();

// functions
const fetchCategories = async () => {
    await axios
        .get(categoryApi)
        .then((response) => {
            console.log(response.data.data.categories);
            allCategories.value = response.data.data.categories;
            isCategoryLoading.value = false;
        })
        .catch((error) => {
            console.log("error", error);
            isCategoryLoading.value = false;
        });
};

// on mount calls
onMounted(() => {
    fetchCategories();
    // allCategories.value = getCategories().data.categories;
    console.log(getCategories().data.categories);
});
</script>
