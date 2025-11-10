<template>
    <div class="main-container flex items-center justify-between md:gap-3 lg:gap-4 border-t border-b border-slate-100 flex-wrap md:flex-nowrap relative">

        <div class="xl:w-[220px] flex">
            <!--==== Categories dropdown menu ====-->
            <Popover v-slot="{ open }">
                <div class="border-r border-slate-100 p-1">
                    <PopoverButton class="h-10 lg:h-11 flex items-center gap-2 outline-none rounded-lg transition-all"
                        :class="open ? 'bg-primary-100 text-primary' : 'text-slate-600'">
                        <div class="w-12 md:w-auto xl:w-12 flex items-center justify-center">
                            <MenuIcon :colorClass="open ? 'text-primary' : ''" />
                        </div>
                        <div class="hidden xs:block text-sm lg:w-20 xl:w-36 lg:text-base font-normal leading-normal" :class="master.langDirection === 'rtl' ? 'text-right' : 'text-left'">
                            {{ $t('Categories') }}
                        </div>
                    </PopoverButton>
                </div>

                <transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-1">

                    <PopoverPanel class="absolute pb-6 left-0 right-0 z-10 mt-0 flex main-container">
                        <PopoverButton as="div" class="w-screen p-6 bg-white shadow-md grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-10 gap-4 rounded-br-xl rounded-bl-xl">
                            <div v-for="category in master.categories" :key="category.id" class="">
                                <MenuCategory :category="category" @update:click="hiddenPopover" />
                            </div>
                        </PopoverButton>
                    </PopoverPanel>
                </transition>
            </Popover>

        </div>

        <!-- Main menu -->
        <div class="hidden md:inline-flex justify-start items-center gap-2.5 lg:gap-3 xl:gap-5  2xl:gap-6 grow overflow-x-auto">

            <div v-for="(menu, index) in master.menus" :key="menu.id" class="flex items-center gap-2.5 lg:gap-3 xl:gap-5  2xl:gap-6" >
                <router-link v-if="!menu.is_external" :to="menu.url" :target="menu.target"
                    class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-normal text-slate-600 whitespace-nowrap">
                    {{ menu.name }}
                </router-link>
                <a v-else :href="menu.url" :target="menu.target"
                    class="h-9 py-2 border-b-2 border-transparent text-sm lg:text-base font-normal text-slate-600">
                    {{ menu.name }}
                </a>
                <div v-if="index !== master.menus.length - 1" class="w-[0px] h-4 border border-slate-200"></div>
            </div>

        </div>

        <!-- Download our app -->
        <div v-if="master.showDownloadApp" class="inline-block">
            <Menu as="div" class="relative text-left" v-slot="{ open }">
                <div>
                    <MenuButton class="flex items-center gap-1 lg:gap-2 pr-1 lg:pr-3 p-3 rounded-lg"
                        :class="open ? 'bg-primary-100 text-primary' : 'text-slate-600'">
                        <DevicePhoneMobileIcon class="w-4 h-5" />
                        <div class="text-sm xl:text-base font-normal leading-normal">{{ $t('Download our app') }}</div>
                        <ChevronDownIcon class="w-4 h-4 transition" :class="open ? 'rotate-180' : ''" />
                    </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-10 mt-0 lg:w-full origin-top-right p-3 bg-white rounded-xl shadow border border-primary-300  ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="flex-col flex gap-2">
                            <MenuItem v-slot="{ active }">
                            <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="playStore">
                                <img :src="'/assets/icons/playStore.png'" alt="">
                            </button>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                            <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="appStore">
                                <img :src="'/assets/icons/appleStore.png'" alt="">
                            </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

        </div>

    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { DevicePhoneMobileIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import MenuCategory from './MenuCategory.vue';
import MenuIcon from '../icons/Menu.vue';

import { useMaster } from "../stores/MasterStore";
const master = useMaster();

const appStore = () => {
    if (master.appStoreLink) {
        window.open(master.appStoreLink, '_blank');
    }
}

const playStore = () => {
    if (master.playStoreLink) {
        window.open(master.playStoreLink, '_blank');
    }
}

const hiddenPopover = () => {
   open = false
}

</script>

<style scoped>
.router-link-active {
    @apply border-b-2 border-primary text-primary
}
</style>
