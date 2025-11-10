<template>
    <div>
        <!-- login modal-->
        <TransitionRoot as="template" :show="AuthStore.loginModal">
            <Dialog as="div" class="relative z-50" @close="AuthStore.hideLoginModal()">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" />
                </TransitionChild>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full justify-center p-4 text-center items-center sm:p-0">
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
                                        @click="AuthStore.hideLoginModal()">
                                        <XMarkIcon class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <!-- end close button -->

                                    <div class="text-slate-950 text-lg sm:text-2xl font-medium leading-loose">
                                        {{ $t('Welcome') }}!
                                    </div>

                                    <div class="text-slate-950 text-lg font-normal leading-7 tracking-tight mt-3">
                                        {{ $t('Please Login to continue') }}
                                    </div>

                                    <!-- social login -->
                                    <div
                                        v-if="master.socialAuths?.facebook?.is_active || master.socialAuths?.google?.is_active || master.socialAuths?.apple?.is_active">
                                        <div class="font-['Inter'] mt-6 flex flex-col gap-3 items-center text-center">
                                            <button v-if="master.socialAuths.google?.is_active" type="button"
                                                @click="googleLogin()"
                                                class="px-4 py-3 w-full flex items-center rounded-full text-black  text-sm sm:text-base tracking-wider font-semibold outline-none border border-slate-200 bg-white hover:bg-gray-50 active:bg-gray-50">
                                                <GoogleIcon />
                                                <span class="leading-none m-0">{{ $t('Sign up with Google') }}</span>
                                            </button>

                                            <button v-if="master.socialAuths.facebook?.is_active" type="button"
                                                @click="loginWithFacebook()"
                                                class="px-4 py-3 w-full flex items-center rounded-full text-white text-xs xs:text-sm sm:text-base tracking-wider font-semibold border border-blue-500 outline-none bg-blue-700 hover:bg-blue-800 active:bg-blue-600">
                                                <font-awesome-icon :icon="faFacebook" class="mr-2 text-2xl m-0" />
                                                <span class="leading-none m-0">{{ $t('Sign up with Facebook') }}</span>
                                            </button>

                                            <button v-if="master.socialAuths.apple?.is_active" type="button"
                                                @click="loginWithApple('apple')"
                                                class="px-4 py-3 w-full flex items-center rounded-full text-white text-sm sm:text-base tracking-wider font-semibold border border-black outline-none bg-black hover:bg-[#333] active:bg-black">
                                                <font-awesome-icon :icon="faApple" class="mr-2 text-2xl m-0" />
                                                <span class="leading-none m-0">
                                                    {{ $t('Sign up with Apple') }}
                                                </span>
                                            </button>
                                        </div>
                                        <div class="mt-6">
                                            <div class="text-[#687387] text-sm font-normal border-b relative">
                                                <span
                                                    class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 bg-white px-2 rounded-full uppercase">
                                                    {{ $t('OR CONTINUE WITH') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <form @submit.prevent="loginFormSubmit()">
                                        <!-- Phone Number -->
                                        <div class="mt-8">
                                            <label
                                                class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Email / Phone Number') }}
                                            </label>

                                            <input type="text" v-model="loginFormData.phone"
                                                :placeholder="$t('Enter email or phone number')"
                                                class="text-base font-normal w-full p-3 placeholder:text-slate-400 rounded-lg border  focus:border-primary outline-none"
                                                :class="errors && errors?.phone ? 'border-red-500' : 'border-slate-200'">
                                            <span v-if="errors && errors?.phone" class="text-red-500 text-sm">
                                                {{ errors?.phone[0] }}
                                            </span>
                                        </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                            <label
                                                class="text-slate-700 text-base font-normal leading-normal mb-2 block">
                                                {{ $t('Password') }}
                                            </label>

                                            <div class="relative">
                                                <input :type="showLoginPassword ? 'text' : 'password'"
                                                    v-model="loginFormData.password" :placeholder="$t('Enter Password')"
                                                    class="text-base font-normal w-full p-3 placeholder:text-slate-400 rounded-lg border focus:border-primary outline-none"
                                                    :class="errors && errors?.password ? 'border-red-500' : 'border-slate-200'">
                                                <button @click="showLoginPassword = !showLoginPassword" type="button">
                                                    <EyeIcon v-if="showLoginPassword"
                                                        class="w-6 h-6 text-slate-700 absolute top-1/2 -translate-y-1/2"
                                                        :class="master.langDirection === 'rtl' ? 'left-4' : 'right-4'" />
                                                    <EyeSlashIcon v-else
                                                        class="w-6 h-6 text-slate-700 absolute top-1/2 -translate-y-1/2"
                                                        :class="master.langDirection === 'rtl' ? 'left-4' : 'right-4'" />
                                                </button>
                                            </div>
                                            <span v-if="errors && errors?.password" class="text-red-500 text-sm">
                                                {{ errors?.password[0] }}
                                            </span>
                                        </div>

                                        <!-- Forgot Password -->
                                        <div class="mt-2 text-right">
                                            <button type="button"
                                                class="text-right text-slate-700 text-base font-normal leading-normal hover:text-primary transition-all duration-300"
                                                @click="showForgetPasswordDialog()">
                                                {{ $t('Forgot Password') }}?
                                            </button>
                                        </div>

                                        <!-- login button -->
                                        <button v-if="!isLoading" type="submit"
                                            class="px-6 py-3 bg-primary mt-5 rounded-[10px] text-white text-base font-medium w-full">
                                            {{ $t('Log in') }}
                                        </button>
                                        <button v-else type="button"
                                            class="px-6 py-3 bg-primary-200 mt-5 rounded-[10px] text-white text-base font-medium w-full flex justify-center items-center gap-1"
                                            disabled>
                                            {{ $t('Processing') }}
                                            <LoadingSpin />
                                        </button>
                                    </form>

                                    <!-- register button -->
                                    <div
                                        class="px-4 pt-1 mt-4 flex flex-col md:flex-row items-center justify-center gap-2">
                                        <div class="text-slate-900 text-base font-normal">
                                            {{ $t('Don’t have an account') }}?
                                        </div>
                                        <button class="text-primary text-base font-normal" @click="showRegisterDialog">
                                            {{ $t('Sign Up') }}
                                        </button>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- forget password dialog -->
        <ForgetPasswordDialogModal :forgetPasswordDialog="forgetPasswordDialog" :countries="countries"
            @closeForget="forgetPasswordDialog = false" />

        <!-- registration dialog -->
        <RegistrationDialogModal :registerDialog="registerDialog" :countries="countries"
            @hideRegisterDialog="registerDialog = false" @showLogin="showLoginDialog" />

    </div>
</template>

<script setup>
import { faApple, faFacebook } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid'
import { onMounted, ref } from 'vue'
import GoogleIcon from '../icons/Google.vue'
import ForgetPasswordDialogModal from './ForgetPasswordDialogModal.vue'
import LoadingSpin from './LoadingSpin.vue'
import RegistrationDialogModal from './RegistrationDialogModal.vue'
import ToastSuccessMessage from './ToastSuccessMessage.vue'

import { jwtDecode } from "jwt-decode"
import { useToast } from 'vue-toastification'
import { googleSdkLoaded } from 'vue3-google-login'
import { useAuth } from '../stores/AuthStore'
import { useBasketStore } from '../stores/BasketStore'
import { useMaster } from '../stores/MasterStore'

const toast = useToast();
const basketStore = useBasketStore();
const master = useMaster();

const AuthStore = useAuth();

const showLoginPassword = ref(false);

const forgetPasswordDialog = ref(false);
const registerDialog = ref(false);
const isLoading = ref(false);

const loginFormData = ref({
    phone: '',
    password: ''
});

onMounted(async () => {
    if (master.app_environment == 'local') {
        loginFormData.value.phone = 'user@readygrocery.com';
        loginFormData.value.password = 'secret';
    }

    fetchCountries();

    await loadFacebookSDK();
    initializeFB();
});

const showForgetPasswordDialog = () => {
    forgetPasswordDialog.value = true
    AuthStore.hideLoginModal();
}

const errors = ref({});

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Login Successful',
        message: 'You have successfully logged in.',
    },
};

const countries = ref([]);

const fetchCountries = () => {
    axios.get('/countries').then((response) => {
        countries.value = response.data.data.countries
    })
}

const loginFormSubmit = () => {
    errors.value = {}
    isLoading.value = true
    axios.post('/login', loginFormData.value).then((response) => {
        AuthStore.setToken(response.data.data.access.token);
        AuthStore.setUser(response.data.data.user);
        AuthStore.hideLoginModal();
        basketStore.fetchCart()
        isLoading.value = false;
        toast(content, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "top-right",
            toastClassName: "vue-toastification-alert",
            timeout: 3000
        });
        AuthStore.fetchFavoriteProducts()
    }).catch((error) => {
        isLoading.value = false
        toast.error(error.response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
        errors.value = error.response.data.errors
    })
}

const showRegisterDialog = () => {
    AuthStore.hideLoginModal();
    registerDialog.value = true
}

const showLoginDialog = () => {
    registerDialog.value = false
    AuthStore.showLoginModal();
}

/**
 * Initiates the Google login process.
 *
 * Uses the Google Accounts JavaScript library to initialize a client that
 * requests authorization to access the user's email and profile information.
 * Once authorized, the client receives an authorization code which is then
 * sent to the backend to exchange for an access token.
 */
const googleLogin = () => {
    googleSdkLoaded((google) => {
        google.accounts.oauth2.initCodeClient({
            client_id: master.socialAuths.google.client_id,
            scope: 'email profile openid',
            redirect_uri: 'postmessage',
            callback: (response) => {
                if (response.code) {
                    sendCodeToBackend(response.code, 'google');
                }
            },
        }).requestCode();
    });
};

/**
 * Loads the Facebook SDK by appending a script tag to the document body.
 * @returns {Promise<void>}
 */
const loadFacebookSDK = () => {
    return new Promise((resolve) => {
        if (window.FB) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://connect.facebook.net/en_US/sdk.js';
        script.async = true;
        script.defer = true;
        script.onload = () => resolve();
        document.body.appendChild(script);
    });
};

/**
 * Initializes the Facebook SDK.
 * This function is called after the Facebook SDK has been loaded.
 * @see loadFacebookSDK
 */
const initializeFB = () => {
    window.fbAsyncInit = () => {
        FB.init({
            appId: master.socialAuths?.facebook?.client_id, // Replace with your Facebook App ID
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v20.0', // Use the latest Graph API version
        });
    };
};

/**
 * Logs the user in with their Facebook account.
 * @returns {void}
 * @private
 */
const loginWithFacebook = () => {
    FB.login((response) => {
        if (response.authResponse) {
            FB.api('/me', { fields: 'name,email' }, (userInfo) => {
                console.log('User Info:', userInfo);
                // Handle login success here, such as sending info to your backend
                sendCodeToBackend(response.authResponse?.accessToken, 'facebook', userInfo);
            });
        } else {
            console.error('User cancelled login or did not fully authorize.');
        }
    },
        { scope: 'public_profile,email' }
    );
};

/**
 * Loads the Apple ID SDK by appending a script tag to the document body.
 * @returns {Promise<void>}
 */
const loadAppleSDK = () => {
    return new Promise((resolve, reject) => {
        if (window.AppleID) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Failed to load Apple ID SDK'));
        document.body.appendChild(script);
    });
};

/**
 * Signs in with Apple using the Apple ID SDK.
 *
 * @returns {Promise<void>}
 */
const loginWithApple = async () => {
    try {
        await loadAppleSDK();

        window.AppleID.auth.init({
            clientId: master.socialAuths.apple?.client_id,
            scope: 'name email',
            redirectURI: master.socialAuths.apple.redirect_url,
            state: '123456',
            usePopup: true,
        });

        // Sign in with Apple
        const data = await window.AppleID.auth.signIn();
        const { authorization: { id_token: token, code } } = data;

        if (token && code) {
            const decoded = jwtDecode(token);
            sendCodeToBackend('1122', 'apple', decoded);
        } else {
            console.error('Token or code is missing');
        }
    } catch (error) {
        console.error('Error during sign in:', error);
    }
};

/**
 * Sends the authorization code to the backend to get an access token.
 *
 * @param {String} code - The authorization code
 * @param {String} provider - The provider ('google' or 'apple'), defaults to 'google'
 * @param {Object} data - Additional data to send with the request, defaults to empty object
 *
 * @returns {Promise<void>}
 */
async function sendCodeToBackend(code, provider = 'google', data = {}) {
    try {
        const response = await axios.post('/auth/' + provider + '/token', {
            code,
            data,
        });

        if (response.data?.data?.user) {
            AuthStore.setToken(response.data.data.access.token);
            AuthStore.setUser(response.data.data.user);
            AuthStore.hideLoginModal();
            toast.success('Login Successful', {
                position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
            });
            basketStore.fetchCart();
        }
    } catch (error) {
        toast.error(error.response.data.message, {
            position: master.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
        });
    }
}

</script>
