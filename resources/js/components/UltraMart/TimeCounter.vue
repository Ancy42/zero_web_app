<template>
    <div class="flex justify-between md:justify-center items-center w-full gap-2 text-white">
        <div
            class="w-full h-full md:w-20 md:h-20 p-2 bg-yellow-400 rounded-lg shadow-[inset_0px_0px_8px_0px_rgba(184,189,191,1.00)] backdrop-blur-lg inline-flex flex-col justify-center items-center overflow-hidden"
        >
            <div
                class="text-center text-zinc-900 text-xl md:text-4xl font-bold leading-7 lg:leading-[48px]"
            >
                {{ endDay }}
            </div>
            <div
                class="text-center justify-start text-zinc-900 text-xs md:text-sm font-normal leading-tight md:leading-snug"
            >
                {{ $t("Days") }}
            </div>
        </div>

        <span class="text-3xl hidden lg:block">:</span>

        <div
            class="w-full h-full md:w-20 md:h-20 p-2 bg-yellow-400 rounded-lg shadow-[inset_0px_0px_8px_0px_rgba(184,189,191,1.00)] backdrop-blur-lg inline-flex flex-col justify-center items-center overflow-hidden"
        >
            <div
                class="text-center text-zinc-900 text-xl md:text-4xl font-bold leading-7 lg:leading-[48px]"
            >
                {{ endHour }}
            </div>
            <div
                class="text-center justify-start text-zinc-900 text-xs md:text-sm font-normal leading-tight md:leading-snug"
            >
                {{ $t("Hours") }}
            </div>
        </div>

        <span class="text-3xl hidden lg:block">:</span>

        <div
            class="w-full h-full md:w-20 md:h-20 p-2 bg-yellow-400 rounded-lg shadow-[inset_0px_0px_8px_0px_rgba(184,189,191,1.00)] backdrop-blur-lg inline-flex flex-col justify-center items-center overflow-hidden"
        >
            <div
                class="text-center text-zinc-900 text-xl md:text-4xl font-bold leading-7 lg:leading-[48px]"
            >
                {{ endMinute }}
            </div>
            <div
                class="text-center justify-start text-zinc-900 text-xs md:text-sm font-normal leading-tight md:leading-snug"
            >
                {{ $t("Minutes") }}
            </div>
        </div>

        <span class="text-3xl hidden lg:block">:</span>

        <div
            class="w-full h-full md:w-20 md:h-20 p-2 bg-yellow-400 rounded-lg shadow-[inset_0px_0px_8px_0px_rgba(184,189,191,1.00)] backdrop-blur-lg inline-flex flex-col justify-center items-center overflow-hidden"
        >
            <div
                class="text-center text-zinc-900 text-xl md:text-4xl font-bold leading-7 lg:leading-[48px]"
            >
                {{ endSecond }}
            </div>
            <div
                class="text-center justify-start text-zinc-900 text-xs md:text-sm font-normal leading-tight md:leading-snug"
            >
                {{ $t("Seconds") }}
            </div>
        </div>

        <!-- <div
            v-if="endDay > 0"
            class="w-auto h-16 lg:w-24 lg:h-10 px-3 py-2 md:px-3 md:py-2 bg-white/20 rounded-lg shadow-[0px_0px_4px_0px_rgba(0,0,0,0.60)] backdrop-blur-blur flex justify-center items-center flex-col md:flex-row gap-1 md:gap-2 overflow-hidden"
        >
            <div
                class="text-primary text-2xl font-semibold font-['Lato'] leading-loose relative w-full h-full flex justify-center items-center overflow-hidden md:overflow-visible"
            >
                <Transition name="slide-up" mode="out-in">
                    <span :key="endDay" class="absolute">{{ endDay }}</span>
                </Transition>
            </div>
            <div
                class="text-white text-xs md:text-xl lg:text-xs font-normal font-['Lato'] leading-tight"
            >
                {{ $t("Days") }}
            </div>
        </div>

        <div
            class="w-auto h-16 lg:w-24 lg:h-10 px-3 py-2 md:px-3 md:py-2 bg-white/20 rounded-lg shadow-[0px_0px_4px_0px_rgba(0,0,0,0.60)] backdrop-blur-blur flex justify-center items-center flex-col md:flex-row gap-1 md:gap-2 overflow-hidden"
        >
            <div
                class="text-primary text-2xl font-semibold font-['Lato'] leading-loose relative w-full h-full flex justify-center items-center overflow-hidden md:overflow-visible"
            >
                <Transition name="slide-up" mode="out-in">
                    <span :key="endHour" class="absolute">{{ endHour }}</span>
                </Transition>
            </div>
            <div
                class="text-white text-xs md:text-xl lg:text-xs font-normal font-['Lato'] leading-tight"
            >
                {{ $t("Hours") }}
            </div>
        </div>

        <div
            class="w-auto h-16 lg:w-24 lg:h-10 px-3 py-2 md:px-3 md:py-2 bg-white/20 rounded-lg shadow-[0px_0px_4px_0px_rgba(0,0,0,0.60)] backdrop-blur-blur flex justify-center items-center flex-col md:flex-row gap-1 md:gap-2 overflow-hidden"
        >
            <div
                class="text-primary text-2xl font-semibold font-['Lato'] leading-loose relative w-full h-full flex justify-center items-center overflow-hidden md:overflow-visible"
            >
                <Transition name="slide-up" mode="out-in">
                    <span :key="endMinute" class="absolute">{{
                        endMinute
                    }}</span>
                </Transition>
            </div>
            <div
                class="text-white text-xs md:text-xl lg:text-xs font-normal font-['Lato'] leading-tight"
            >
                {{ $t("Minutes") }}
            </div>
        </div>

        <div
            class="w-auto h-16 lg:w-24 lg:h-10 px-3 py-2 md:px-3 md:py-2 bg-white/20 rounded-lg shadow-[0px_0px_4px_0px_rgba(0,0,0,0.60)] backdrop-blur-blur flex justify-center items-center flex-col md:flex-row gap-1 md:gap-2 overflow-hidden"
        >
            <div
                class="text-primary text-2xl font-semibold font-['Lato'] leading-loose relative w-full h-full flex justify-center items-center overflow-hidden md:overflow-visible"
            >
                <Transition name="slide-up" mode="out-in">
                    <span :key="endSecond" class="absolute">{{
                        endSecond
                    }}</span>
                </Transition>
            </div>
            <div
                class="text-white text-xs md:text-xl lg:text-xs font-normal font-['Lato'] leading-tight"
            >
                {{ $t("Seconds") }}
            </div>
        </div> -->
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import { useMaster } from "../../stores/MasterStore";

const props = defineProps({
    end_date: String,
});

const master = useMaster();

const endDay = ref("");
const endHour = ref("");
const endMinute = ref("");
const endSecond = ref("");
let countdownInterval = null;

const startCountdown = () => {
    const endDate = new Date(props.end_date).getTime();

    countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const timeLeft = endDate - now;

        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            endDay.value = "00";
            endHour.value = "00";
            endMinute.value = "00";
            endSecond.value = "00";
        } else {
            endDay.value = String(
                Math.floor(timeLeft / (1000 * 60 * 60 * 24))
            ).padStart(2, "0");
            endHour.value = String(
                Math.floor(
                    (timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                )
            ).padStart(2, "0");
            endMinute.value = String(
                Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))
            ).padStart(2, "0");
            endSecond.value = String(
                Math.floor((timeLeft % (1000 * 60)) / 1000)
            ).padStart(2, "0");
        }
    }, 1000);
};

onMounted(startCountdown);

onUnmounted(() => {
    clearInterval(countdownInterval);
});
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease-out;
}

.slide-up-enter-from {
    transform: translateY(30px);
}

.slide-up-leave-to {
    transform: translateY(-30px);
}
</style>
