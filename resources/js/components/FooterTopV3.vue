<template>
    <footer class="container-2 px-4 pt-6 xl:px-0 lg:pt-0 relative z-10">
        <div class="footer__top">
            <div class="footer__logo w-full md:w-auto">
                <RouterLink to="/">
                    <img
                        v-if="master.footerLogo"
                        :src="master.footerLogo"
                        loading="lazy"
                    />
                    <img
                        v-else
                        src="../../../public/assets/logo.png"
                        loading="lazy"
                    />
                </RouterLink>
            </div>

            <!-- currency and language selection -->
            <div
                class="w-full md:w-auto flex md:hidden justify-center items-center gap-4"
            >
                <LanguageSelectBtnV3 />
                <CurrencySelectBtnV3 />
            </div>

            <div class="flex justify-between items-center gap-[27px]">
                <a
                    :href="master.google_playstore_link"
                    class="w-full md:w-32 h-10 lg:w-52 lg:h-16 relative rounded-xl shadow-[0px_2px_4px_0px_rgba(0,0,0,1.00)] shadow-[inset_0px_0px_13px_0px_rgba(0,0,0,1.00)] overflow-hidden cursor-pointer"
                >
                    <img
                        src="../../../public/assets/images/playstore_icon.png"
                        alt="google"
                    />
                </a>
                <a
                    :href="master.app_store_link"
                    class="w-full md:w-32 h-10 lg:w-52 lg:h-16 relative rounded-xl shadow-[0px_2px_4px_0px_rgba(0,0,0,1.00)] shadow-[inset_0px_0px_13px_0px_rgba(0,0,0,1.00)] overflow-hidden cursor-pointer"
                >
                    <img
                        src="../../../public/assets/images/appstore_icon.png"
                        alt="ios"
                    />
                </a>
            </div>
        </div>

        <!-- Footer Wrapper Start -->
        <div class="footer__wrapper">
            <!-- Footer Widget -->
            <div v-for="subsection in footerSubsections" class="footer__widget">
                <h4 class="widget__title">{{ $t(subsection.title) }}</h4>
                <ul class="space-y-3">
                    <li
                        v-for="item in subsection.items"
                        class="text-white/80 text-sm lg:text-base font-light leading-snug lg:leading-normal"
                    >
                        <RouterLink :to="item.url">{{
                            $t(item.title)
                        }}</RouterLink>
                    </li>
                </ul>
            </div>

            <!-- Footer Widget -->
            <div class="footer__widget">
                <h4 class="widget__title">{{ $t("Contact") }}</h4>
                <ul class="widget__list">
                    <li>
                        <span class="icon">
                            <MapPin
                                width="20"
                                height="20"
                                :colorClass="'fill-primary'"
                            />
                        </span>
                        <p
                            class="justify-start text-white/80 text-base font-light leading-normal"
                        >
                            {{ master.address }}
                        </p>
                    </li>

                    <template v-for="aboutCompany in master?.footers[0]?.items">
                        <li
                            v-if="
                                aboutCompany.type == 'email' ||
                                aboutCompany.type == 'phone'
                            "
                            class="cursor-pointer"
                        >
                            <span class="icon">
                                <Phone
                                    v-if="aboutCompany.type == 'phone'"
                                    width="24"
                                    height="24"
                                    :colorClass="'fill-primary'"
                                />
                                <Message
                                    v-else
                                    width="24"
                                    height="24"
                                    :colorClass="'fill-primary'"
                                />
                            </span>
                            <a
                                class="text-white/80 text-sm lg:text-base font-light font-['Inter'] leading-snug lg:leading-normal"
                                :href="aboutCompany.url"
                                >{{ aboutCompany.title }}</a
                            >
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </footer>
</template>

<script setup>
import {
    RiHomeLine,
    RiMailLine,
    RiMapPinLine,
    RiPhoneLine,
} from "vue-remix-icons";
import { onMounted, onBeforeUnmount, ref } from "vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { PhoneIcon, EnvelopeIcon } from "@heroicons/vue/24/solid";
import { ChevronDownIcon } from "@heroicons/vue/24/solid";

import { useMaster } from "../stores/MasterStore";
import { RouterLink } from "vue-router";
import { computed } from "vue";
import LanguageSelectBtnV3 from "./LanguageSelectBtnV3.vue";
import CurrencyBtn from "./CurrencyBtn.vue";
import CurrencySelectBtnV3 from "./CurrencySelectBtnV3.vue";
import Phone from "../icons/Phone.vue";
import Message from "../icons/Message.vue";
import MapPin from "../icons/MapPin.vue";
const master = useMaster();

const [item0, item1, item2, item3] = [
    ref(false),
    ref(false),
    ref(false),
    ref(false),
];

const isLargeScreen = ref(window.innerWidth >= 640);

const footerSubsections = computed(() => {
    let otherFooterSection = master.footers.slice(1, -1);
    return otherFooterSection;
});

onMounted(() => {
    window.addEventListener("resize", handleResize);
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", handleResize);
});

const checkOpen = (index) => {
    const items = [item0, item1, item2, item3];
    return items[index]?.value || false;
};

const handleResize = () => {
    isLargeScreen.value = window.innerWidth >= 640;
    if (isLargeScreen.value) {
        item0.value = item1.value = item2.value = item3.value = true;
    } else {
        item0.value = item1.value = item2.value = item3.value = false;
    }
};

const toggleLinks = (index) => {
    switch (index) {
        case 0:
            item0.value = !item0.value;
            break;
        case 1:
            item1.value = !item1.value;
            break;
        case 2:
            item2.value = !item2.value;
            break;
        case 3:
            item3.value = !item3.value;
            break;
    }
};

const appStore = () => {
    if (master.appStoreLink) {
        window.open(master.appStoreLink, "_blank");
    }
};

const playStore = () => {
    if (master.playStoreLink) {
        window.open(master.playStoreLink, "_blank");
    }
};
</script>
<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    max-height: 500px;
    opacity: 1;
}

.footer__top {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: end;
    -ms-flex-align: end;
    align-items: end;
    gap: 30px;
    padding-bottom: 24px;
    border-bottom: 1px solid #363a44;
    margin-bottom: 24px;
}
@media screen and (max-width: 479px) {
    .footer__top {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 16px;
    }
}
.footer__top .footer__logo img {
    display: block;
    @apply w-48 h-auto lg:w-60 lg:h-auto;
}
@media screen and (max-width: 991px) {
    .footer__top .footer__logo img {
        height: 52px;
    }
}
.footer__top .social__icon {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 20px;
}
@media screen and (max-width: 575px) {
    .footer__top .social__icon {
        justify-content: center;
        gap: 20px;
    }
}
.footer__top .social__icon a {
    background: var(--primary-600);
    color: var(--color-primary);
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 20px;
    -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2509803922),
        0px 0px 8px 0px rgba(81, 175, 91, 0.2509803922);
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2509803922),
        0px 0px 8px 0px rgba(81, 175, 91, 0.2509803922);
    -webkit-transition: var(--transition);
    transition: var(--transition);
    @apply w-10 h-10;
}
.footer__top .social__icon a:hover {
    -webkit-transform: translateY(-2px);
    transform: translateY(-2px);
}

.footer__wrapper {
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: start;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    gap: 30px;
}
@media screen and (max-width: 320px) {
    .footer__wrapper {
        flex-direction: column;
    }
}
.footer__wrapper .footer__widget .widget__title {
    @apply text-white text-lg lg:text-2xl font-semibold  uppercase leading-relaxed mb-4;
}
.footer__wrapper .footer__widget .widget__list li {
    list-style: none;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: baseline;
    -ms-flex-align: baseline;
    align-items: baseline;
    margin-bottom: 12px;
    font-family: var(--font-default);
    font-weight: var(--font-regular);
    font-size: 16px;
    line-height: 24px;
    color: rgba(var(--color-white-rgb), 0.7);
    display: flex;
    align-items: center;
    gap: 14px;
}
.footer__wrapper .footer__widget .widget__list li:last-of-type {
    margin-bottom: 0;
}
.footer__wrapper .footer__widget .widget__list li a {
    color: currentColor;
    text-decoration: none;
    -webkit-transition: var(--transition);
    transition: var(--transition);
}
.footer__wrapper .footer__widget .widget__list li a:hover {
    color: var(--primary);
}
.footer__wrapper .footer__widget .widget__list li i {
    font-size: 20px;
    width: 24px;
    height: 24px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    color: var(--primary);
}
</style>
