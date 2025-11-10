<template>
    <TransitionRoot as="template" :show="mobileMenuCategoryOpen">
        <Dialog as="div" class="relative z-50" @close="closeModal">
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
                        class="pointer-events-none fixed inset-y-0 flex max-w-72"
                        :class="
                            master.langDirection == 'rtl'
                                ? 'left-0 '
                                : 'right-0 '
                        "
                    >
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            :enter-from="
                                master.langDirection == 'rtl'
                                    ? '-translate-x-[200%]'
                                    : 'translate-x-[200%]'
                            "
                            enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0"
                            :leave-to="
                                master.langDirection == 'rtl'
                                    ? '-translate-x-[200%]'
                                    : 'translate-x-[200%]'
                            "
                        >
                            <DialogPanel
                                class="pointer-events-auto relative w-screen max-w-md"
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
                                    class="h-full bg-white shadow-xl py-4 px-3"
                                >
                                    <div
                                        class="flex justify-between items-cente pb-4"
                                    >
                                        <div
                                            class="text-black text-lg font-bold leading-normal tracking-tight font-['Roboto']"
                                        >
                                            {{ $t("All Categories") }}
                                        </div>

                                        <button
                                            class="w-7 h-7 flex justify-center items-center bg-slate-100 rounded-full"
                                            @click="closeModal"
                                        >
                                            <XMarkIcon
                                                class="w-5 h-5 text-slate-700"
                                            />
                                        </button>
                                    </div>

                                    <div
                                        class="w-full flex flex-col gap-[5px] overflow-y-scroll h-[95vh] pb-10 scrollbar-hide"
                                    >
                                        <div
                                            @click="() => {
                                                router.push(
                                                    `/categories/${category.id}`
                                                )
                                                closeModal()
                                            }"
                                            v-for="category in categories"
                                            class="self-stretch h-14 p-2 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-gray-100 flex justify-start items-center gap-2"
                                        >
                                            <img
                                                class="w-10 h-10 rounded-lg object-cover"
                                                :src="category.thumbnail"
                                            />
                                            <div
                                                class="flex-1 inline-flex flex-col justify-center items-start"
                                            >
                                                <p
                                                    class="self-stretch justify-start text-primary text-sm font-semibold font-['Inter'] leading-snug"
                                                >
                                                    {{ category.name }}
                                                </p>
                                                <p
                                                    class="justify-start text-slate-500 text-xs font-normal font-['Inter'] leading-tight"
                                                >
                                                    {{
                                                        category.total_products
                                                    }}
                                                    Items
                                                </p>
                                            </div>
                                        </div>
                                    </div>
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

const props = defineProps({
    mobileMenuCategoryOpen: Boolean,
    categories: {
        type: Array,
        default: [],
    },
});

// emits
const emits = defineEmits(["close"]);
// store
const master = useMaster();

const router = useRouter();

const closeModal = () => {
    emits("close");
};
</script>
