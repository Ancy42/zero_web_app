<template>
    <div>
        <div class="bg-white rounded-lg md:rounded-2xl border border-slate-100 p-3 md:p-4 xl:p-6">

            <div class="text-slate-950 text-lg md:text-xl font-medium">
                {{ $t('Order Summary') }}
            </div>

            <!-- Subtotal -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ $t('Items') }}
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ order.quantity }}
                </div>
            </div>

            <!-- Subtotal -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ $t('Subtotal') }}
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ order.currency_symbol +''+ order?.total_amount }}
                </div>
            </div>

            <!-- Discount -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-red-500 text-sm sm:text-base font-normal">
                    {{ $t('Discount') }}
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    -{{ order.currency_symbol +''+ order?.coupon_discount }}
                </div>
            </div>

            <!-- Shipping Charge -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ $t('Shipping Charge') }}
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ order.currency_symbol +''+ order?.delivery_charge }}
                </div>
            </div>

            <div v-if="order.all_vat_taxes?.length > 0 || order?.tax_amount > 0" class="p-3 bg-slate-100 text-black rounded-lg mb-2">
                <h2 class="text-sm sm:text-base font-medium mb-2">
                    {{ $t('VAT & Taxes Summary') }}
                </h2>

                <div class="space-y-2">
                    <div v-for="vatTax in order.all_vat_taxes" :key="vatTax.id" class="flex justify-between bg-slate-200 p-2 rounded-lg">
                        <span class="font-medium">
                            {{ vatTax.name }}
                            <small>({{ vatTax.percentage }}%)</small>
                        </span>
                        <span class="font-medium">
                            {{ master.showCurrency(vatTax.amount) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-slate-200 rounded-lg">
                    <h3 class="text-sm sm:text-base font-medium">
                        {{ $t('Total Tax Amount') }}:
                    </h3>
                    <p class="text-base font-bold">
                        {{ order.currency_symbol +''+ order?.tax_amount }}
                    </p>
                </div>
            </div>

            <div class="w-full h-[0px] border border-slate-400"></div>

            <!-- Total Payable -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-base sm:text-lg tracking-tight font-medium">
                    {{ $t('Total Amount') }}
                </div>
                <div class="text-slate-950 text-base sm:text-lg tracking-tight">
                    {{ order.currency_symbol +''+order?.payable_amount }}
                </div>
            </div>

            <div
                class="bg-slate-50 rounded md:rounded-lg border border-slate-100 p-2 lg:p-4 mt-2 flex flex-col gap-1 lg:gap-2">
                <div class="text-slate-500 text-xs font-normal">{{ $t('PAYMENT METHOD') }}</div>
                <div
                    class="text-slate-950 text-sm md:text-lg font-medium leading-tight flex items-center justify-between flex-wrap">
                    {{ order.payment_method }}

                    <span v-if="order.payment_status === 'Pending'"
                        class="text-red-500 text-sm font-normal bg-red-100 p-1 rounded-md flex gap-1 items-center">
                        <ExclamationTriangleIcon class="w-5 h-5" />
                        {{ order.payment_status }}
                    </span>

                    <span v-if="order.payment_status === 'Paid'"
                        class="bg-emerald-500 text-sm font-normal text-white p-1 rounded-md flex items-center">
                        <CheckCircleIcon class="w-5 h-5" />
                        {{ order.payment_status }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row gap-2 mt-2 items-center justify-center">
                <button class="p-2 xl:p-4 flex gap-1 lg:gap-2 items-center justify-center" @click="downloadPaymentSlip">
                    <img :src="'/assets/icons/cloud.svg'" alt="download" class="w-4 h-4 md:w-6 md:h-6" />
                    <span class="text-center text-primary text-xs md:text-sm font-normal underline leading-tight">
                        {{ $t('Payment Slip') }}
                    </span>
                </button>

                <button class="p-2 xl:p-4 flex gap-1 lg:gap-2 items-center justify-center" @click="downloadInvoice">
                    <img :src="'/assets/icons/cloud.svg'" alt="download" class="w-4 h-4 md:w-6 md:h-6" />
                    <span class="text-center text-primary text-xs md:text-sm font-normal underline leading-tight">
                        {{ $t('Download Invoice') }}
                    </span>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button v-if="order.order_status === 'Pending'"
                class="mt-2 px-4 py-3 md:py-4 bg-white rounded-lg text-slate-700 text-base font-medium grow"
                @click="cancelModal = true">
                {{ $t('Cancel Order') }}
            </button>

            <button v-if="props.order?.payment_status == 'Pending' && props.order?.payment_method == 'Online Payment'"
                class=" mt-2 px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium grow"
                @click="makePaymentModal = true">
                {{ $t('Make Payment') }}
            </button>
        </div>

        <button v-if="order.order_status === 'Delivered'"
            class="w-full mt-2 px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium"
            @click="againOrder = true">
            {{ $t('Order Again') }}
        </button>

        <!-- Cancel modal -->
        <TransitionRoot as="template" :show="cancelModal">
            <Dialog as="div" class="relative z-10" @close="cancelModal = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white p-5 sm:p-8 text-center">

                                    <div
                                        class="bg-red-500 w-14 h-14 md:w-20 md:h-20 rounded-full mx-auto flex justify-center items-center">
                                        <XMarkIcon class="w-8 h-8 md:w-12 md:h-12 text-white" />
                                    </div>

                                    <div class="mt-3 text-center text-gray-900 text-2xl md:text-3xl font-bold">
                                        {{ $t('Cancel the Order') }}!
                                    </div>

                                    <div class="mt-4 text-center text-slate-700 text-base md:text-xl font-normal">
                                        {{ $t('Are you sure want to cancel this order') }}?
                                    </div>

                                    <div class="flex justify-between items-center gap-4 mt-8">
                                        <button
                                            class="text-slate-800 grow text-base font-medium px-4 py-3 md:px-6 md:py-4 rounded-lg md:rounded-[10px] border border-slate-300"
                                            @click="cancelModal = false">
                                            {{ $t('Cancel') }}
                                        </button>

                                        <button
                                            class="text-white grow bg-red-500 text-base font-medium px-4 py-3 md:px-6 md:py-4 rounded-lg md:rounded-[10px]"
                                            @click="cancelOrder">
                                            {{ $t('Yes') }}
                                        </button>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <OrderDetailsPaymentAndAgainOrder :order="order" :makePayment="makePaymentModal" :againOrder="againOrder"
            @update:makePayment="makePaymentModal = false" @update:orderAgain="againOrder = false"
            @update:paymentSuccess="orderPaymentSuccess" />

    </div>
</template>

<script setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { CheckCircleIcon, ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import { useAuth } from '../stores/AuthStore';
import { useMaster } from '../stores/MasterStore';
import OrderDetailsPaymentAndAgainOrder from './OrderDetailsPaymentAndAgainOrder.vue';

const master = useMaster();
const toast = useToast();
const authStore = useAuth();

const cancelModal = ref(false);
const againOrder = ref(false);

const makePaymentModal = ref(false);

const showPaymentButton = ref(false);

const emit = defineEmits(['update:paymentSuccess']);

const orderPaymentSuccess = () => {
    emit('update:paymentSuccess', true);
}

const props = defineProps({
    order: Object
});

const cancelOrder = () => {
    axios.post('/orders/cancel', {
        order_id: props.order.id
    }, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        toast.success(response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
        authStore.orderCancel = true;
    })
    cancelModal.value = false;
}

const downloadInvoice = () => {
    if (props.order.invoice_url) {
        window.open(props.order.invoice_url, '_blank');
    } else {
        toast.error('Invoice not found', {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    }
}

const downloadPaymentSlip = () => {
    if (props.order?.payment_receipt_url) {
        window.open(props.order.payment_receipt_url, '_blank');
    } else {
        toast.error('Payment Slip not found', {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    }
}

</script>
