<template>
    <div>
        <!-- OTP Dialog Modal -->
        <TransitionRoot as="template" :show="showModal">
            <Dialog as="div" class="relative z-10">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" />
                </TransitionChild>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all my-8 md:my-0 w-full sm:max-w-lg md:max-w-xl">
                                <div class="bg-white p-5 sm:p-8 relative"
                                    :class="master.langDirection === 'rtl' ? 'text-right' : 'text-left'">
                                    <!-- close button -->
                                    <div class="w-9 h-9 bg-slate-100 rounded-[32px] absolute top-4 flex justify-center items-center cursor-pointer"
                                        :class="master.langDirection === 'rtl' ? 'left-4' : 'right-4'"
                                        @click="closeModal()">
                                        <XMarkIcon class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <!-- end close button -->

                                    <div class="text-slate-950 text-lg sm:text-2xl font-medium leading-loose">
                                        {{ $t('Enter OTP') }}
                                    </div>

                                    <div v-if="sendMessage && sendOtpEmailOrPhone"
                                        class="text-slate-950 mt-3 text-lg font-normal leading-7 tracking-tight">
                                        {{ sendMessage }} <br>
                                        {{ sendOtpEmailOrPhone }}
                                    </div>
                                    <div v-else>
                                        <SkeletonLoader class="w-8/12 h-4 rounded-lg mt-2" />
                                        <SkeletonLoader class="w-11/12 h-4 rounded-lg mt-2" />
                                    </div>

                                    <div class="flex gap-3 mt-6">
                                        <input v-for="(input, index) in inputs" :key="index" :id="'input' + index"
                                            type="text" v-model="input.value" @input="handleInput(index)"
                                            @keydown="handleKeyDown(index, $event)" placeholder="-"
                                            class="text-base font-normal w-10 grow text-center p-3 placeholder:text-slate-400 rounded-lg border border-slate-200 focus:border-primary outline-none"
                                            maxlength="1">
                                    </div>

                                    <div v-if="sendOtpEmailOrPhone">
                                        <!-- Confirm button -->
                                        <button v-if="!isLoadingVerifyOTP"
                                            class="px-6 py-4 bg-primary mt-6 rounded-[10px] text-white text-base font-medium w-full"
                                            @click="verifyOTP">
                                            {{ $t('Confirm OTP') }}
                                        </button>
                                        <button v-else type="button"
                                            class="px-6 py-4 bg-primary-200 mt-6 rounded-[10px] text-primary text-base font-medium w-full flex items-center justify-center gap-2"
                                            disabled>
                                            {{ $t('Processing') }}
                                            <LoadingSpin />
                                        </button>
                                    </div>
                                    <div v-else>
                                        <SkeletonLoader class="w-full h-12 rounded-lg mt-6" />
                                    </div>

                                    <div v-if="time > 0" class="px-4 py-2 mt-6 flex items-center justify-center gap-2">
                                        <div class="text-slate-900 text-base font-normal leading-normal">
                                            {{ $t('Resend code in') }}
                                        </div>

                                        <div v-if="sendOtpEmailOrPhone"
                                            class="text-primary text-base font-normal leading-normal">
                                            00:{{ time }} {{ $t('sec') }}
                                        </div>
                                        <div v-else>
                                            <SkeletonLoader class="w-16 h-4 rounded-lg" />
                                        </div>
                                    </div>
                                    <!-- Resend OTP -->
                                    <div v-else class="px-4 py-2 mt-6 flex items-center justify-center gap-2">
                                        <button v-if="!isLoadingOTP" type="button"
                                            class="text-primary text-base font-normal leading-normal"
                                            @click="sendOTP()">
                                            {{ $t('Resend OTP') }}
                                        </button>
                                        <button v-else type="button"
                                            class="rounded-[10px] text-primary text-base font-medium w-full flex justify-center items-center gap-1">
                                            {{ $t('Sending') }}
                                            <LoadingSpin />
                                        </button>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
        <!-- end OTP dialog -->

    </div>
</template>

<script setup>
import { ref, nextTick, watch } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import { useAuth } from '../stores/AuthStore'
import { useMaster } from '../stores/MasterStore'

import LoadingSpin from './LoadingSpin.vue'
import SkeletonLoader from './SkeletonLoader.vue'

const emits = defineEmits(['hideModal']);

const toast = useToast();
const authStore = useAuth();
const master = useMaster();

const isLoadingVerifyOTP = ref(false);
const isLoadingOTP = ref(false);

const sendOtpEmailOrPhone = ref('');
const sendMessage = ref('');

const props = defineProps({
    showModal: {
        type: Boolean,
        default: false
    }
});

watch(props, () => {
    if (props.showModal) {
        sendOTP()
    }
});

const setTimeout = ref(null);

const closeModal = () => {
    emits('hideModal', false);
    inputs.value = [{ value: '' }, { value: '' }, { value: '' }, { value: '' }];
    isLoadingVerifyOTP.value = false;
    isLoadingOTP.value = false;
    props.showModal = false;
    clearTimeout(setTimeout.value)
    time.value = 60;
}

const time = ref(60);
const onTimer = () => {
    if (time.value > 0) {
        setTimeout.value = window.setTimeout(() => {
            time.value -= 1;
            onTimer();
        }, 1000);
    }else {
        clearTimeout(setTimeout.value)
    }
}

const inputs = ref([
    { value: '' },
    { value: '' },
    { value: '' },
    { value: '' }
]);

const handleInput = (index) => {
    let nextIndex = index + 1;
    if (nextIndex < inputs.value.length && inputs.value[index].value != '') {
        nextTick(() => {
            const inputElement = document.getElementById('input' + nextIndex);
            if (inputElement) {
                inputElement.focus();
            }
        });
    }
};

const handleKeyDown = (index, event) => {
    if (event.key === 'Backspace' && index > 0 && inputs.value[index].value === '') {
        let previousIndex = index - 1;
        if (previousIndex >= 0) {
            nextTick(() => {
                const inputElement = document.getElementById('input' + previousIndex);
                if (inputElement) {
                    inputElement.focus();
                }
            })
        }
    }
};

const emailOrPhone = ref('');

const sendOTP = () => {
    if (master.register_otp_type == 'email') {
        emailOrPhone.value = authStore.user?.email;
    } else {
        emailOrPhone.value = authStore.user?.phone;
    }

    if (!emailOrPhone.value) {
        toast.error('Your account is not complete. Please complete your account first', {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
        return;
    }

    isLoadingOTP.value = true
    axios.post('/send-otp', {
        phone: emailOrPhone.value,
        phone_code: authStore.user?.phone_code,
    }).then((response) => {
        isLoadingOTP.value = false
        time.value = 60
        onTimer();

        toast.success(response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });

        sendMessage.value = response.data.message
        sendOtpEmailOrPhone.value = response.data.data.email_or_phone

    }).catch((error) => {
        console.log(error.response);

        isLoadingOTP.value = false
        toast.error(error.response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    })
}

const verifyOTP = () => {
    isLoadingVerifyOTP.value = true
    const otp = inputs.value.map(input => input.value).join('');
    axios.post('/verify-otp', {
        phone: sendOtpEmailOrPhone.value,
        otp: otp
    }).then((response) => {
        isLoadingVerifyOTP.value = false
        toast.success(response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });

        closeModal()
        fetchUserDetails()

    }).catch((error) => {
        isLoadingVerifyOTP.value = false
        toast.error(error.response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    })
}

const fetchUserDetails = () => {
    axios.get('/profile', {
        headers: {
            Authorization: authStore.token,
            'Accept-Language': master.locale || 'en',
        },
    }).then((response) => {
        authStore.setUser(response.data.data.user);
    }).catch((error) => {
        console.log(error);
    });
}

</script>
