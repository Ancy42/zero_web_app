<template>
    <div class="bg-black">
        <div
            class="main-container py-1.5 gap-[6px] text-white"
            :class="
                master.langDirection == 'rtl'
                    ? 'flex flex-col md:flex-row justify-between items-start md:items-center flex-row-reverse'
                    : 'flex flex-col md:flex-row justify-between items-start md:items-center'
            "
        >
            <div
                class="flex items-center justify-between md:justify-start gap-4 w-full"
            >
                <div
                    class="text-white text-sm font-normal leading-tight flex items-center gap-2"
                >
                    <span
                        class="w-[14px] h-[14px] md:w-6 md:h-6 flex justify-center items-center"
                    >
                        <Phone
                            width="24"
                            height="24"
                            :colorClass="'fill-primary'"
                        />
                    </span>
                    <span
                        class="text-[8px] lg:text-sm font-normal lg:font-light font-['Inter'] leading-[8plg"
                        >{{ $t("Hotline") }}: {{ master.mobile }}</span
                    >
                </div>

                <span class="hidden md:block text-sm font-thin">|</span>

                <a
                    v-if="master.getMultiVendor"
                    href="/shop/register"
                    class="text-white text-sm font-normal leading-tight flex items-center gap-2"
                >
                    <span
                        class="w-[14px] h-[14px] md:w-6 md:h-6 flex justify-center items-center"
                    >
                        <Message
                            width="24"
                            height="24"
                            :colorClass="'fill-primary'"
                        />
                    </span>
                    <template v-for="aboutCompany in master?.footers[0]?.items">
                        <a
                            :href="aboutCompany.url"
                            v-if="aboutCompany.type == 'email'"
                            class="text-white text-[8px] lg:text-sm font-normal lg:font-light font-['Inter'] leading-[8px] lg:leading-snug"
                        >
                            Mail: {{ aboutCompany.title }}
                        </a>
                    </template>
                </a>
            </div>

            <div
                class="flex items-center justify-between md:justify-end gap-3 w-full"
            >
                <Menu
                    as="div"
                    class="relative inline-block text-left bg-[#202227] px-[4px] p-1 rounded"
                >
                    <div>
                        <MenuButton
                            class="flex justify-between items-center text-white gap-2 text-xs md:text-sm font-normal leading-snug"
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
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'left-0'
                                    : 'right-0'
                            "
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
                                            active
                                                ? ' text-primary'
                                                : 'text-gray-700',
                                            'block py-[7.5px] text-xs',
                                        ]"
                                    >
                                        {{
                                            currency.name +
                                            ", " +
                                            currency.symbol
                                        }}
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>

                <div
                    class="w-0 h-3 origin-top-left outline outline-1 outline-offset-[-0.50px] outline-white/50"
                ></div>

                <Menu
                    as="div"
                    class="relative inline-block text-left bg-[#202227] px-1 py-1.5 rounded"
                >
                    <div>
                        <MenuButton
                            class="flex justify-between items-center text-white gap-2 text-xs md:text-sm font-normal leading-tight"
                        >
                            <!-- {{ currentLanguage }} -->
                            <img
                                :src="currentLanguage.flag"
                                alt="flag"
                                class="w-5 h-4"
                            />
                            <ChevronDownIcon
                                class="w-4 h-4"
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
                            class="absolute z-20 w-24 mt-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'left-0'
                                    : 'right-0'
                            "
                        >
                            <div class="py-1">
                                <MenuItem
                                    v-for="language in master.languages"
                                    v-slot="{ active }"
                                    :key="language.id"
                                >
                                    <button
                                        type="button"
                                        @click="
                                            setCurrentLanguage(language.name);
                                            reloadPage();
                                        "
                                        class="w-full text-left"
                                        :class="[
                                            active
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700',
                                            'flex justify-between items-center px-4 py-2 text-xs',
                                        ]"
                                    >
                                        <img
                                            :src="language.flag"
                                            alt="flag"
                                            class="w-5"
                                        />
                                        {{ language.title }}
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import localization from "../localization";

import { useMaster } from "../stores/MasterStore";
import { onMounted, ref, watch } from "vue";
import Phone from "../icons/Phone.vue";
import Message from "../icons/Message.vue";
const master = useMaster();

const currentLanguage = ref("English");

onMounted(() => {
    setCurrentLanguage(master.locale);
});

watch(
    () => master.locale,
    (oldValue, newValue) => {
        if (oldValue !== newValue) {
            setCurrentLanguage(master.locale);
        }
    }
);

const setCurrentLanguage = (lang) => {
    master.locale = lang;
    localStorage.setItem("locale", lang);

    const language = master.languages.find(
        (lang) => lang.name === master.locale
    );
    if (language) {
        currentLanguage.value = language;
        master.langDirection = language.direction || "ltr";
    }
    localization.fetchLocalizationData();
};

const setCurrentCurrency = (currency) => {
    master.selectedCurrency = currency;
};

const reloadPage = () => {
    window.location.reload();
};
</script>
