<template>
    <Menu
    v-if="master.languages.length > 0"
        as="div"
        class="relative inline-block text-left"
    >
        <div>
            <MenuButton
                class="flex justify-center items-center gap-1 w-28 h-8 lg:h-10 px-2 bg-white/10 rounded-lg outline outline-1 outline-offset-[-1px] outline-zinc-700 text-primary"
            >
                {{ currentLanguage.title }}
                <ChevronDownIcon class="w-4 h-4" aria-hidden="true" />
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
                            <img :src="language.flag" alt="flag" class="w-5" />
                            {{ language.title }}
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
import localization from "../localization";

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

const reloadPage = () => {
    window.location.reload();
};
</script>
