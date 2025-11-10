<template>
    <footer
        class="relative bg-[#17181D] py-6 px-4 lg:py-[64px] overflow-hidden"
    >
        <div class="container-2 flex flex-col gap-6">
            <section class="w-full grid grid-cols-12 gap-6">
                <div class="col-span-12 xl:col-span-4">
                    <div class="h-full w-fit relative">
                        <img
                            src="../../../../public/assets/images/footer/app-img.png"
                            alt="h-full w-auto"
                        />

                        <div
                        v-if="master.showDownloadApp"
                            class="absolute bottom-0 right-0 z-10 w-full flex flex-col justify-center items-center gap-3 py-4 text-white"
                        >
                            <div
                                class="text-base font-semibold font-['Inter'] leading-normal"
                            >
                                Download Our App
                            </div>
                            <div
                                class="flex justify-center items-center gap-[15.5px]"
                            >
                                <a
                                    :href="master.playStoreLink"
                                    target="_blank"
                                    class="w-28 h-9 cursor-pointer inline-block"
                                >
                                    <img
                                        src="../../../../public/assets/images/footer/googlePlay.png"
                                        alt="google-play"
                                        class="w-full h-full"
                                    />
                                </a>
                                <a
                                    :href="master.appStoreLink"
                                    target="_blank"
                                    class="w-28 h-9 cursor-pointer inline-block"
                                >
                                    <img
                                        src="../../../../public/assets/images/footer/apple.png"
                                        alt="apple-store"
                                        class="w-full h-full"
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block lg:hidden col-span-12 space-y-4 -mb-4">
                    <RouterLink to="/" class="w-40 h-12 inline-block">
                        <img
                            v-if="master.footerLogo"
                            :src="master.footerLogo"
                            loading="lazy"
                            class="h-full w-full"
                        />
                        <img
                            v-else
                            src="../../../../public/assets/logo.png"
                            loading="lazy"
                            class="h-full w-full"
                        />
                    </RouterLink>

                    <div class="flex justify-start items-center gap-4">
                        <CurrencyBtn />

                        <LanguageBtn />
                    </div>
                </div>

                <div
                    class="col-span-12 xl:col-span-8 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10"
                >
                    <!-- sections  -->
                    <div
                        v-for="subsection in footerSubsections"
                        class="self-stretch inline-flex flex-col justify-start items-start gap-2"
                    >
                        <div
                            class="self-stretch pb-1 lg:pb-2 border-b-2 border-zinc-700"
                        >
                            <p
                                class="justify-start text-white text-2xl font-semibold leading-loose"
                            >
                                {{ $t(subsection.title) }}
                            </p>
                        </div>

                        <ul
                            class="flex flex-col justify-start items-start gap-3 list-disc list-inside text-white ml-2"
                        >
                            <li v-for="item in subsection.items">
                                <a
                                    v-if="item.target == '_blank'"
                                    :href="item.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-white text-base font-normal leading-normal"
                                >
                                    {{ $t(item.title) }}
                                </a>
                                <RouterLink
                                    v-else
                                    :to="item.url"
                                    class="text-white text-base font-normal leading-normal"
                                >
                                    {{ $t(item.title) }}
                                </RouterLink>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="inline-flex flex-col justify-start items-start gap-2"
                    >
                        <div class="w-full pb-2 border-b-2 border-zinc-700">
                            <p
                                class="text-white text-2xl font-semibold leading-loose"
                            >
                                {{ $t("Contact") }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col justify-start items-start gap-3"
                        >
                            <div
                                class="flex flex-col justify-start items-start gap-4"
                            >
                                <div
                                    class="inline-flex justify-start items-center gap-3.5 cursor-pointer"
                                >
                                    <MapPin
                                        width="20"
                                        height="20"
                                        :colorClass="'fill-primary'"
                                    />

                                    <p
                                        class="justify-start text-white text-base font-normal font-['Inter'] leading-normal"
                                    >
                                        {{ master.address }}
                                    </p>
                                </div>

                                <template
                                    v-for="aboutCompany in master?.footers[0]
                                        ?.items"
                                >
                                    <div
                                        class="inline-flex justify-start items-center gap-3.5 cursor-pointer"
                                        v-if="
                                            aboutCompany.type == 'email' ||
                                            aboutCompany.type == 'phone'
                                        "
                                    >
                                        <Phone
                                            v-if="aboutCompany.type == 'phone'"
                                            width="24"
                                            height="24"
                                            :colorClass="'fill-primary'"
                                        />

                                        <Message
                                            v-if="aboutCompany.type == 'email'"
                                            width="24"
                                            height="24"
                                            :colorClass="'fill-primary'"
                                        />
                                        <a
                                            :href="aboutCompany.url"
                                            class="justify-start text-white text-base font-normal font-['Inter'] leading-normal"
                                        >
                                            {{ aboutCompany.title }}
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- social sections  -->
            <section class="w-full flex justify-between items-center gap-6">
                <span
                    class="hidden md:inline-block border w-full h-[0.5px] border-zinc-700"
                ></span>

                <div
                    class="flex justify-center items-center flex-wrap md:flex-nowrap gap-x-10 gap-y-4 lg:gap-10"
                >
                    <div
                        v-for="socialLink in master.socialLinks"
                        class="w-10 h-10 bg-white/20 rounded-full"
                    >
                        <img
                            :src="socialLink.logo"
                            alt=""
                            class="w-full h-full object-cover"
                        />
                    </div>
                </div>

                <span
                    class="hidden md:inline-block border w-full h-[0.5px] border-zinc-700"
                ></span>
            </section>

            <section class="w-full grid grid-cols-1 lg:grid-cols-3">
                <RouterLink to="/" class="hidden lg:block w-52 h-16">
                    <img
                        v-if="master.footerLogo"
                        :src="master.footerLogo"
                        loading="lazy"
                        class="h-full w-full object-contain"
                    />
                    <img
                        v-else
                        src="../../../../public/assets/logo.png"
                        loading="lazy"
                        class="h-full w-full object-contain"
                    />
                </RouterLink>

                <div class="flex justify-center items-center">
                    <p
                    v-if="master.showFooter"
                        class="text-gray-400 text-sm font-normal font-['Inter'] leading-snug"
                    >
                        © {{ new Date().getFullYear() }}
                        {{
                            master.footerText ||
                            "All right reserved by RazinSoft"
                        }}
                    </p>
                </div>

                <div class="hidden lg:flex justify-end items-center gap-4">
                    <CurrencyBtn />

                    <LanguageBtn />
                </div>
            </section>
        </div>
    </footer>
</template>

<script setup>
import { useMaster } from "../../stores/MasterStore";
import { computed } from "vue";
import MapPin from "../../icons/MapPin.vue";
import Phone from "../../icons/Phone.vue";
import Message from "../../icons/Message.vue";
import CurrencyBtn from "../CurrencyBtn.vue";
import LanguageBtn from "../LanguageBtn.vue";

const master = useMaster();

// footer sub section
const footerSubsections = computed(() => {
    let otherFooterSection = master.footers.slice(1, -1);
    return otherFooterSection;
});
</script>
