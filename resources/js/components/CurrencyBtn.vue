<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="flex justify-center items-center gap-1 w-28 h-8 lg:h-10 px-2 bg-white/10 rounded-lg outline outline-1 outline-offset-[-1px] outline-zinc-700 text-primary"
            >
                {{
                    (master.selectedCurrency?.name || "USD") +
                    ", " +
                    (master.selectedCurrency?.symbol || "$")
                }}
                <ChevronDownIcon
                    class="w-4 h-4 md:w-4 md:h-4"
                    aria-hidden="true"
                />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems
                class="absolute z-20 w-full mt-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                :class="master.langDirection == 'rtl' ? 'left-0' : 'right-0'"
            >
                <div class="px-[13px] py-[5px]">
                    <MenuItem
                        v-for="currency in master.currencies"
                        v-slot="{ active }"
                        :key="currency.id"
                    >
                        <button
                            type="button"
                            @click="setCurrentCurrency(currency)"
                            class="w-full text-left"
                            :class="[
                                active ? ' text-primary' : 'text-gray-700',
                                'block py-[7.5px] text-xs',
                            ]"
                        >
                            {{ currency.name + ", " + currency.symbol }}
                        </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { onMounted, ref, watch } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { useMaster } from "../stores/MasterStore";

const master = useMaster();

const setCurrentCurrency = (currency) => {
    master.selectedCurrency = currency;
};
</script>
