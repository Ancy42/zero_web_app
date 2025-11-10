<template>
    <div>
        <!-- Registration Dialog Modal -->
        <TransitionRoot as="template" :show="registerDialog">
            <Dialog as="div" class="relative z-50" @close="closeRegisterDialog()">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" />
                </TransitionChild>
                <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all my-8 md:my-0 w-full sm:max-w-lg md:max-w-xl lg:max-w-2xl">
                                <div class="bg-white p-5 sm:p-8 relative" :class="master.langDirection==='rtl' ? 'text-right' : 'text-left'">
                                    <!-- close button -->
                                    <div class="w-9 h-9 bg-slate-100 rounded-[32px] absolute top-4 flex justify-center items-center cursor-pointer" :class="master.langDirection==='rtl' ? 'left-4' : 'right-4'" @click="closeRegisterDialog()">
                                        <XMarkIcon class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <!-- end close button -->

                                    <div class="text-slate-950 text-lg sm:text-2xl font-medium leading-loose">
                                        {{ $t('Welcome') }}!
                                    </div>

                                    <div class="text-slate-950 text-lg font-normal leading-7 tracking-tight mt-3">
                                        {{ $t('Create your account') }}
                                    </div>

                                    <form @submit.prevent="registerFormSubmit()">
                                        <!-- Full Name -->
                                        <div class="mt-8">
                                            <label
                                                class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Full Name') }}
                                            </label>

                                            <input type="text" v-model="registerFormData.name"
                                                :placeholder="$t('Enter full name')" class="text-base font-normal w-full p-3 placeholder:text-slate-400 rounded-lg border focus:border-primary outline-none" :class="registerErrors?.name ? 'border-red-500' : 'border-slate-200'">
                                            <span v-if="registerErrors && registerErrors?.name" class="text-red-500 text-sm">
                                                {{ registerErrors?.name[0] }}
                                            </span>
                                        </div>

                                        <div class="mt-4">
                                            <label for="name" class="form-label mb-2 text-slate-700">
                                                {{ $t('Country') }}
                                            </label>
                                            <v-select :options="countries" label="name"
                                                :reduce="country => country.name" v-model="registerFormData.country" :placeholder="$t('Select Country')" 
                                                class="text-slate-700 placeholder:text-slate-400" :class="registerErrors?.country ? 'border rounded-lg border-red-500' : 'border-slate-200'"
                                                aria-autocomplete="none" :dir="master.langDirection || 'ltr'" />
                                            <span v-if="registerErrors && registerErrors?.country" class="text-red-500 text-sm">
                                                {{ registerErrors?.country[0] }}
                                            </span>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="mt-4">
                                            <label class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Phone Number') }}
                                            </label>

                                            <div class="flex">
                                                <span class="text-slate-700 text-base font-normal leading-normal bg-slate-100 p-2 border-y flex items-center" :class="master.langDirection==='rtl' ? 'border-r rounded-tr-lg rounded-br-lg' : 'border-l rounded-tl-lg rounded-bl-lg'">
                                                    +{{ registerFormData.phone_code || '00' }}
                                                </span>
                                                <input type="text" v-model="registerFormData.phone"
                                                    :placeholder="$t('Enter phone number')" class="text-base font-normal w-full p-3 placeholder:text-slate-400 border focus:border-primary outline-none" :class="(registerErrors?.phone ? 'border-red-500' : 'border-slate-200') + (master.langDirection==='rtl' ? ' rounded-tl-lg rounded-bl-lg' : ' rounded-tr-lg rounded-br-lg')"
                                                    :minlength="master.phoneMinLength"
                                                    :maxlength="master.phoneMaxLength"
                                                    @input="registerFormData.phone = registerFormData.phone.replace(/[^\d]/g, '')">
                                            </div>
                                            <span v-if="registerErrors && registerErrors?.phone"
                                                class="text-red-500 text-sm">
                                                {{ registerErrors?.phone[0] }}
                                            </span>
                                        </div>

                                        <div class="mt-4">
                                            <label class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Email Address') }}
                                            </label>
                                            <input type="email" v-model="registerFormData.email"
                                                :placeholder="$t('Enter email address')"
                                                class="text-base font-normal w-full p-3 placeholder:text-slate-400 rounded-lg border focus:border-primary outline-none"
                                                :class="registerErrors?.email ? 'border-red-500' : 'border-slate-200'">
                                            <span v-if="registerErrors && registerErrors?.email"
                                                class="text-red-500 text-sm">
                                                {{ registerErrors?.email[0] }}
                                            </span>
                                        </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                            <label class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Create Password') }}
                                            </label>
                                            <div class="relative">
                                                <input :type="showRegisterPassword ? 'text' : 'password'"
                                                    v-model="registerFormData.password" :placeholder="$t('Enter Password')" class="text-base font-normal w-full p-3 placeholder:text-slate-400 rounded-lg border  focus:border-primary outline-none" :class="registerErrors?.password ? 'border-red-500' : 'border-slate-200'">
                                                <button type="button" @click="showRegisterPassword = !showRegisterPassword">
                                                    <EyeIcon v-if="showRegisterPassword"
                                                        class="w-6 h-6 text-slate-700 absolute top-1/2 -translate-y-1/2" :class="master.langDirection === 'rtl' ? 'left-4' : 'right-4'" />
                                                    <EyeSlashIcon v-else
                                                        class="w-6 h-6 text-slate-700 absolute top-1/2 -translate-y-1/2" :class="master.langDirection === 'rtl' ? 'left-4' : 'right-4'" />
                                                </button>
                                            </div>
                                            <span v-if="registerErrors && registerErrors?.password"
                                                class="text-red-500 text-sm">
                                                {{ registerErrors?.password[0] }}
                                            </span>
                                        </div>

                                        <!-- Forgot Password -->
                                        <div
                                            class="mt-5 text-slate-900 text-sm font-normal flex items-center gap-1 flex-wrap">
                                            <span>{{ $t('By clicking the ‘Sign up’ button, you agree with our')
                                                }}</span>
                                            <button class="text-primary" @click="showTerms">
                                                {{ $t('Terms & Conditions') }}
                                            </button>
                                            <span>{{ $t('and') }}</span>
                                            <button class="text-primary" @click="showPrivacy">
                                                {{ $t('Privacy Policy') }}
                                            </button>
                                        </div>

                                        <!-- login button -->
                                        <button v-if="!isLoading" type="submit"
                                            class="px-6 py-3 bg-primary mt-3 rounded-[10px] text-white text-base font-medium w-full">
                                            {{ $t('Sign up') }}
                                        </button>
                                        <button v-else type="button"
                                            class="px-6 py-3 bg-primary-200 mt-3 rounded-[10px] text-white text-base font-medium w-full flex justify-center items-center gap-1">
                                            {{ $t('Processing') }}
                                            <LoadingSpin />
                                        </button>

                                        <div class="px-4 pt-1 mt-4 flex flex-col md:flex-row items-center justify-center gap-2">
                                            <div class="text-slate-900 text-base font-normal">
                                                {{ $t('Already have an account') }}?
                                            </div>
                                            <button type="button" class="text-primary text-base font-normal" @click="showLoginDialog">
                                                {{ $t('Log in') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
        <!-- end Registration dialog -->

        <!-- OTP Dialog Modal -->
        <TransitionRoot as="template" :show="OTPDialog">
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
                                <div class="bg-white p-5 sm:p-8 relative" :class="master.langDirection === 'rtl' ? 'text-right' : 'text-left'">
                                    <!-- close button -->
                                    <div class="w-9 h-9 bg-slate-100 rounded-[32px] absolute top-4 flex justify-center items-center cursor-pointer" :class="master.langDirection==='rtl' ? 'left-4' : 'right-4'" @click="OTPDialog = false">
                                        <XMarkIcon class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <!-- end close button -->

                                    <div class="text-slate-950 text-lg sm:text-2xl font-medium leading-loose">
                                        {{ $t('Enter OTP') }}
                                    </div>

                                    <div v-if="!isSendOtpProcess">
                                        <div class="text-slate-950 mt-3 text-lg font-normal leading-7 tracking-tight">
                                            {{ sendMessage }} <br>
                                            {{ sendOtpEmailOrPhone }}
                                        </div>

                                        <div class="flex gap-3 mt-6">
                                            <input v-for="(input, index) in inputs" :key="index" :id="'input' + index"
                                                type="text" v-model="input.value" @input="handleInput(index)"
                                                @keydown="handleKeyDown(index, $event)" placeholder="-"
                                                class="text-base font-normal w-10 grow text-center p-3 placeholder:text-slate-400 rounded-lg border border-slate-200 focus:border-primary outline-none"
                                                maxlength="1">
                                        </div>

                                        <!-- Confirm button -->
                                        <button v-if="!isLoadingVerifyOTP" class="px-6 py-4 bg-primary mt-6 rounded-[10px] text-white text-base font-medium w-full"  @click="verifyOTP">
                                            {{ $t('Confirm OTP') }}
                                        </button>
                                        <button v-else type="button" class="px-6 py-4 bg-primary-200 mt-6 rounded-[10px] text-primary text-base font-medium w-full flex items-center justify-center gap-2" disabled>
                                            {{ $t('Processing') }}
                                            <LoadingSpin />
                                        </button>

                                        <div v-if="time > 0"
                                            class="px-4 py-2 mt-6 flex items-center justify-center gap-2">
                                            <div class="text-slate-900 text-base font-normal leading-normal">
                                                {{ $t('Resend code in') }}
                                            </div>

                                            <div class="text-primary text-base font-normal leading-normal">
                                                00:{{ time }} {{ $t('sec') }}
                                            </div>
                                        </div>
                                        <!-- Resend OTP -->
                                        <div v-else class="px-4 py-2 mt-6 flex items-center justify-center gap-2">
                                            <button v-if="!isLoadingOTP" type="button"
                                                class="text-primary text-base font-normal leading-normal"
                                                @click="sendOTP(sendOTPNumber, phoneCode)">
                                                {{ $t('Resend OTP') }}
                                            </button>
                                            <button v-else type="button"
                                                class="rounded-[10px] text-primary text-base font-medium w-full flex justify-center items-center gap-1">
                                                {{ $t('Sending') }}
                                                <LoadingSpin />
                                            </button>
                                        </div>
                                    </div>

                                    <!-- loading -->
                                    <div v-else class="mt-6">
                                        <SkeletonLoader class="w-8/12 h-3 rounded-lg" />
                                        <SkeletonLoader class="w-11/12 h-3 rounded-lg mt-2" />
                                        <div class="flex  gap-3 mt-6">
                                            <SkeletonLoader class="w-10/12 h-12 rounded-lg" />
                                            <SkeletonLoader class="w-10/12 h-12 rounded-lg" />
                                            <SkeletonLoader class="w-10/12 h-12 rounded-lg" />
                                            <SkeletonLoader class="w-10/12 h-12 rounded-lg" />
                                        </div>
                                        <SkeletonLoader class="w-full h-11 rounded-lg mt-6" />
                                        <SkeletonLoader class="w-52 mx-auto h-6 rounded-lg mt-5" />
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
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid'
import { nextTick, ref, watch } from 'vue'
import LoadingSpin from './LoadingSpin.vue'
import SkeletonLoader from './SkeletonLoader.vue'
import ToastSuccessMessage from './ToastSuccessMessage.vue'

import { useToast } from 'vue-toastification'
import { useAuth } from '../stores/AuthStore'
import { useMaster } from '../stores/MasterStore'

import axios from 'axios'
import { useRouter } from 'vue-router'
const router = useRouter();

const toast = useToast();
const master = useMaster();

const AuthStore = useAuth();
const isLoading = ref(false);

const emits = defineEmits(['hideRegisterDialog', 'showLogin']);

const props = defineProps({
    registerDialog: {
        type: Boolean,
        default: false
    },
    countries: {
        type: Array,
        default: () => []
    }
});

const OTPDialog = ref(false);
const showRegisterPassword = ref(false);

const showLoginDialog = () => {
    emits('showLogin')
}

const registerFormData = ref({
    name: null,
    phone: null,
    email: null,
    password: null,
    country: null,
    phone_code: null,
});

watch(() => registerFormData.value.country, () => {
    var findCountry = props.countries.find((country) => country.name == registerFormData.value.country);
    registerFormData.value.phone_code = findCountry?.phone_code
})

const registerMessage = {
    component: ToastSuccessMessage,
    props: {
        title: 'Register Successful',
        message: 'You have successfully registered.',
    },
};

const registerErrors = ref({});

const registerFormSubmit = () => {
    registerErrors.value = {}
    isLoading.value = true
    axios.post('/registration', registerFormData.value).then((response) => {
        AuthStore.setToken(response.data.data.access.token);
        AuthStore.setUser(response.data.data.user);
        isLoading.value = false;

        toast(registerMessage, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "top-right",
            toastClassName: "vue-toastification-alert",
            timeout: 3000
        });

        closeRegisterDialog()

        const emailOrPhone = registerFormData.value.phone ?? registerFormData.value.email;
        if (master.register_otp_verify) {
            OTPDialog.value = true;
            isSendOtpProcess.value = true
            sendOTP(emailOrPhone, registerFormData.value.phone_code)
        }

        registerFormData.value.name = null;
        registerFormData.value.password = null;
        registerFormData.value.country = null;
        registerFormData.value.phone_code = null;
        registerFormData.value.phone = null;
        registerFormData.value.email = null;

    }).catch((error) => {
        registerErrors.value = error.response.data.errors
        isLoading.value = false
    })
}

const sendOTPNumber = ref('');
const phoneCode = ref(null);
const sendOtpEmailOrPhone = ref('');
const sendMessage = ref('');
const isLoadingOTP = ref(false);
const isSendOtpProcess = ref(false);

const sendOTP = (phoneNumber = '', phone_code = null) => {
    if (phoneNumber) {
        sendOTPNumber.value = phoneNumber
        phoneCode.value = phone_code
    }
    isLoadingOTP.value = true
    axios.post('/send-otp', {
        phone: sendOTPNumber.value,
        phone_code: phoneCode.value,
    }).then((response) => {
        isLoadingOTP.value = false
        isSendOtpProcess.value = false
        OTPDialog.value = true
        time.value = 60
        onTimer();

        toast.success(response.data.message, {
           position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });

        sendMessage.value = response.data.message
        sendOtpEmailOrPhone.value = response.data.data.email_or_phone

    }).catch((error) => {
        isLoadingOTP.value = false
        isSendOtpProcess.value = false
        toast.error(error.response.data.message, {
           position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    })
}

const isLoadingVerifyOTP = ref(false);
const verifyOTP = () => {
    isLoadingVerifyOTP.value = true
    const otp = inputs.value.map(input => input.value).join('');
    axios.post('/verify-otp', {
        phone: sendOTPNumber.value,
        otp: otp
    }).then((response) => {
        isLoadingVerifyOTP.value = false
        toast.success(response.data.message, {
           position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });

        OTPDialog.value = false

    }).catch((error) => {
        isLoadingVerifyOTP.value = false
        toast.error(error.response.data.message, {
           position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    })
}

const closeRegisterDialog = () => {
    emits('hideRegisterDialog')
}

const showTerms = () => {
    closeRegisterDialog()
    router.push({ name: 'terms-and-conditions' })
}

const showPrivacy = () => {
    closeRegisterDialog()
    router.push({ name: 'privacy-policy' })
}

const time = ref(60);

const onTimer = () => {
    if (time.value > 0) {
        setTimeout(() => {
            time.value -= 1;
            onTimer();
        }, 1000);
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

</script>
