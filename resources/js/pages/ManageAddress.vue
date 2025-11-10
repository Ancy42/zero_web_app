<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <AuthPageHeader title="Manage Address" />

        <div class="p-3 md:p-4 xl:p-6 h-full flex flex-col justify-between gap-4">
            <!-- Selected Address -->
            <div class="space-y-4">

                <div v-for="address in authStore.addresses" :key="address.id"
                    class="p-2 md:p-3 lg:p-4 flex gap-2 md:gap-4 xl:gap-6 rounded-lg border border-slate-200 w-full bg-white relative">
                    <div
                        class="flex w-[60px] sm:w-[88px] bg-slate-50 rounded-lg flex-col gap-2 justify-center items-center shrink-0">
                        <MapPinIcon class="w-6 h-6 text-primary-600" />
                        <div class="px-1.5 py-[3px] bg-slate-800 rounded-md text-white text-xs font-medium uppercase">
                            {{ address?.address_type }}
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                            {{ address?.name }}
                        </div>
                        <div class="text-slate-500 text-base font-normal leading-normal">
                            {{ address?.phone }}
                        </div>
                        <div class="text-slate-500 text-base font-normal leading-normal truncate">
                            {{ (address?.flat_no ? address?.flat_no  + ', ' : '') + address?.address_line + ', ' +
                    (address?.address_line2 ? address?.address_line2 + ', ' : '') }} {{ address?.area + '-' +
                    address?.post_code }}
                        </div>

                        <div v-if="address.is_default"
                            class="text-blue-500 mt-1 text-sm font-normal leading-[18px] italic">
                            {{ $t('Default Address') }}
                        </div>
                    </div>

                    <button class="absolute top-2 md:top-3 lg:top-4 px-1 py-0.5 md:px-2 md:py-1 rounded-md border border-primary hover:bg-primary text-primary hover:text-white transition-all flex items-center gap-0.5" type="button" :class="master.langDirection == 'rtl' ? 'left-2 md:left-3 lg:left-4' : 'right-2 md:right-3 lg:right-4'" @click="updateAddress(address)">
                        <PencilSquareIcon class="w-4 h-4 md:w-5 md:h-5" />
                        <div class="text-xs md:text-sm font-normal">{{ $t('Edit') }}</div>
                    </button>

                </div>

                <div v-if="authStore.addresses.length === 0" class="w-full bg-white p-2 md:p-3 lg:p-4 rounded md:rounded-lg border border-slate-200">
                    <div class="text-slate-600 text-xl font-medium leading-normal italic">
                        {{ $t('Address list is empty') }}
                    </div>
                </div>
            </div>

            <!-- Add New Address -->
            <div class="w-full flex justify-end">
                <router-link to="/manage-address/new"
                    class="p-2 md:p-3 lg:p-4 flex gap-2 rounded md:rounded-lg  bg-primary text-white items-center">
                    <PlusIcon class="w-5 h-5" />
                    <div class="text-base font-medium leading-normal">
                        {{ $t('New Address') }}
                    </div>
                </router-link>
            </div>

        </div>
    </div>
</template>

<script setup>
import { MapPinIcon, PencilSquareIcon, PlusIcon } from '@heroicons/vue/24/solid';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AuthPageHeader from '../components/AuthPageHeader.vue';
import { useAuth } from "../stores/AuthStore";
import { useMaster } from '../stores/MasterStore';

const authStore = useAuth();
const router = useRouter();
const master = useMaster();

onMounted(() => {
    fetchAddresses();
    window.scrollTo(0, 0);
});

const fetchAddresses = () => {
    authStore.fetchAddresses();
}

const updateAddress = (address) => {
    router.push({ name: 'edit-address', params: { id: address.id } })
}

</script>
