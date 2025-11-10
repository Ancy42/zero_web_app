<template>
    <TransitionRoot as="template" :show="showSearch">
        <Dialog as="div" class="relative z-50" @close="closeSearchModal">
            <TransitionChild
                as="template"
                enter="ease-in-out duration-500"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in-out duration-500"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="pointer-events-none fixed inset-y-0 flex w-full h-fit"
                    >
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            :enter-from="'-translate-y-full'"
                            enter-to="translate-y-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-y-0"
                            :leave-to="'-translate-y-full'"
                        >
                            <DialogPanel
                                class="pointer-events-auto relative w-full"
                            >
                                <TransitionChild
                                    as="template"
                                    enter="ease-in-out duration-500"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="ease-in-out duration-500"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <div
                                        class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"
                                    ></div>
                                </TransitionChild>
                                <div
                                    class="min-h-[150px] w-full bg-white shadow-xl p-4 flex justify-center items-center"
                                >
                                    <!-- Header Search -->
                                    <form @submit.prevent="goToSearch" class="search__form">
                                        <input
                                            type="search"
                                            name="search"
                                            class="form-control w-full"
                                            :placeholder="
                                                $t('Search for products') +
                                                '...'
                                            "
                                        />
                                        <button type="submit">
                                            <RiSearchLine class="w-5" />
                                        </button>
                                    </form>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { RiHomeLine, RiSearchLine } from "vue-remix-icons";
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3Icon,
    ChevronRightIcon,
    UserIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useMaster } from "../stores/MasterStore";
import { useRouter } from "vue-router";

const master = useMaster();
const router = useRouter()

const props = defineProps({
    showSearch: Boolean,
});

const emit = defineEmits(["closeModal"]);

const closeSearchModal = () => {
    emit("closeModal", false);
};

const goToSearch = (e) => {
       e.preventDefault(); // Prevent default just in case
    const formData = new FormData(e.target);
    const searchValue = formData.get("search");
    console.log(searchValue); // The typed search text
    master.search = searchValue;
    router.push({ name: "products" });
    closeSearchModal()
};
</script>
