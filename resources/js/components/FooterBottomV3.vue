<template>
    <div class="container-2 pb-6 lg:pb-0 space-y-6">
        <!--==== Copyright ====-->
        <!-- Footer bottom Start -->
        <div class="footer__bottom">
            <div class="bar"></div>
            <div
                class="flex justify-center items-center flex-wrap md:flex-nowrap gap-x-10 gap-y-4 lg:gap-10"
            >
                <a
                    v-for="socialLink in master.socialLinks"
                    :href="socialLink.link"
                    class="w-10 h-10 bg-white/20 rounded-full cursor-pointer"
                >
                    <img
                        :src="socialLink.logo"
                        alt=""
                        class="w-full h-full object-cover"
                    />
                </a>
            </div>
            <div class="bar"></div>
        </div>
        <div class="flex justify-center items-center">
            <p
                class="text-gray-400 text-xs font-normal font-['Inter'] leading-tight"
            >
                © {{ new Date().getFullYear() }}
                {{ master.footerText || "All right reserved by RazinSoft" }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { useMaster } from "../stores/MasterStore";

const master = useMaster();
</script>

<style scoped>
.footer__bottom {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    gap: 20px;
    margin-top: 32px;
}
@media screen and (max-width: 575px) {
    .footer__bottom {
        -webkit-box-orient: vertical;
        -webkit-box-direction: reverse;
        -ms-flex-direction: column-reverse;
        flex-direction: column-reverse;
        margin-top: 24px;
    }
}
.footer__bottom .footer__copyright p {
    font-weight: var(--font-regular);
    font-size: 12px;
    line-height: 20px;
    color: #b3bac6;
    text-transform: capitalize;
    margin-bottom: 0;
}
.footer__bottom .bar {
    width: 100%;
    height: 1px;
    background: #363a44;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
}
@media screen and (max-width: 575px) {
    .footer__bottom .bar {
        display: none;
    }
}
.footer__bottom .apps__thumb {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 25px;
}
.footer__bottom .apps__thumb img {
    width: auto;
    height: 52px;
    max-width: 212px;
    display: block;
    border-radius: 10px;
    -webkit-box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.2509803922);
    box-shadow: 0px 0px 8px 0px rgba(81, 175, 91, 0.2509803922);
}
@media screen and (max-width: 1399px) {
    .footer__bottom .apps__thumb img {
        height: 52px;
    }
}
@media screen and (max-width: 991px) {
    .footer__bottom .apps__thumb img {
        height: 42px;
    }
}

@media screen and (max-width: 320px) {
    .footer__bottom .apps__thumb {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}

@media screen and (max-width: 320px) {
    .footer__bottom .apps__thumb img {
        @apply w-52 h-16;
    }
}
</style>
