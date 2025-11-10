<template>
    <div v-if="master.showFooter" class="container-2 px-4  pb-6 2xl:px-0  lg:pb-0 relative z-10">
        <!--==== Copyright ====-->
        <!-- Footer bottom Start -->
        <div class="footer__bottom">
            <div class="footer__copyright">
                <p>
                    © {{ new Date().getFullYear() }}
                    {{ master.footerText || "All right reserved by RazinSoft" }}
                </p>
            </div>
            <div class="bar"></div>
            <div v-if="master.showDownloadApp" class="apps__thumb">
                <a :href="master.playStoreLink" class="cursor-pointer">
                    <img
                        src="../../../public/assets/images/playstore_icon.png"
                        alt="google"
                    />
                </a>
                <a :href="master.appStoreLink" class="cursor-pointer">
                    <img
                        src="../../../public/assets/images/appstore_icon.png"
                        alt="ios"
                    />
                </a>
            </div>
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
        gap: 12px
    }
}


@media screen and (max-width: 320px) {
    .footer__bottom .apps__thumb img {
        @apply w-52 h-16;
    }
}
</style>
