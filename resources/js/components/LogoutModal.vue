<template>
    <TransitionRoot as="template" :show="showLogoutModal">
        <Dialog as="div" class="relative z-50" @close="hideModal">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg"
                        >
                            <div class="bg-white p-5 sm:p-8 text-center">
                                <div
                                    class="bg-red-500 w-20 h-20 rounded-full mx-auto flex justify-center items-center"
                                >
                                    <img
                                        src="../../../public/assets/icons/logoutWhite.svg"
                                        alt="icon"
                                        loading="lazy"
                                    />
                                </div>

                                <div
                                    class="mt-3 text-center text-gray-900 text-3xl font-bold font-['Roboto'] leading-9"
                                >
                                    {{ $t("Log Out") }}!
                                </div>

                                <div
                                    class="mt-4 text-center text-slate-700 text-xl font-normal font-['Roboto'] leading-7"
                                >
                                    {{ $t("Logout Confirmation") }}
                                </div>

                                <div
                                    class="flex justify-between items-center gap-4 mt-8"
                                >
                                    <button
                                        class="text-slate-800 grow text-base font-medium px-6 py-4 rounded-[10px] border border-slate-300"
                                        @click="hideModal"
                                    >
                                        {{ $t("Cancel") }}
                                    </button>

                                    <button
                                        class="text-white grow bg-red-500 text-base font-medium px-6 py-4 rounded-[10px]"
                                        @click="logout"
                                    >
                                        {{ $t("Yes") }}
                                    </button>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useAuth } from "../stores/AuthStore";
import { useBasketStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";
import { useToast } from "vue-toastification";

const props = defineProps({
    showLogoutModal: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(["hideLogoutModal"]);

const master = useMaster();
const authStore = useAuth();
const basketStore = useBasketStore();

const toast = useToast();

const logout = () => {
    authStore.logout();
    basketStore.total = 0;
    basketStore.checkoutProducts = [];
    basketStore.products = [];
    basketStore.address = null;
    basketStore.selectedShopIds = [];
    basketStore.coupon_code = "";
    basketStore.payable_amount = 0;
    basketStore.delivery_charge = 0;
    basketStore.coupon_discount = 0;

    toast.success("Logout successfully", {
        position:
            master.langDirection === "rtl" ? "bottom-right" : "bottom-left",
    });
    hideModal();
};

const hideModal = () => {
    emits("hideLogoutModal");
};
</script>
