<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="max-w-xl mx-auto text-center">
            <h1 class="text-9xl font-bold text-slate-500">404</h1>
            <h2 class="mt-4 text-2xl md:text-3xl font-semibold text-gray-800">
                Page Not Found
            </h2>
            <p class="mt-4 text-gray-500">
                Oops! The page you're looking for doesn't exist or has been moved.
            </p>

            <div class="mt-8">
                <button class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-emerald-600 focus:outline-none transition ease-in-out duration-300" @click="goHome">
                    Go to Homepage
                </button>
            </div>

            <div class="mt-12">
                <img src="https://placehold.co/500x300?text=Oops+404&font=open+Sans" alt="404 illustration"
                    class="w-full h-auto rounded-lg" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

function goHome() {
    router.push('/')
}
</script>
