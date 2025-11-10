<template>
    <div>
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2 leading-normal">
            <HomeIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/manage-address" class="hover:text-primary">
                {{ $t('Manage Address') }}
            </router-link>
            <span>/ {{ $t('Add New Address') }}</span>
        </div>
        <!-- Header -->
        <AuthPageHeader :title="$t('Add New Address')" />

        <div class="p-3 md:p-4 xl:p-6">

            <div class="max-w-5xl mx-auto">
                <AddressForm />
            </div>

        </div>
    </div>
</template>

<script setup>
import { HomeIcon } from '@heroicons/vue/24/solid';
import AddressForm from '../components/AddressForm.vue';
import AuthPageHeader from '../components/AuthPageHeader.vue';

</script>
