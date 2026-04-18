<script setup>
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent",
);
</script>

<template>
    <GuestLayout>
        <Head title="Revisa tu correo" />

        <div class="flex justify-center mb-6">
            <div class="bg-blue-100 p-4 rounded-full">
                <svg
                    class="w-12 h-12 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    ></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-900 mb-4">
            ¡Casi hemos terminado!
        </h2>

        <div class="mb-6 text-center text-gray-600 leading-relaxed">
            Hemos enviado un correo electrónico a tu cuenta con un enlace de
            seguridad.
            <strong>Por favor, revisa tu bandeja de entrada</strong> y haz clic
            en el enlace para activar tu cuenta y empezar a crear itinerarios.
            <br /><br />
            <span class="text-sm text-gray-500"
                >¿No lo encuentras? Revisa también tu carpeta de correo no
                deseado (SPAM) o promociones.</span
            >
        </div>

        <div
            class="mb-4 p-4 bg-green-50 rounded-lg text-sm font-medium text-green-700 text-center border border-green-200"
            v-if="verificationLinkSent"
        >
            ¡Hecho! Te acabamos de enviar un nuevo enlace de seguridad.
        </div>

        <form @submit.prevent="submit">
            <div
                class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="w-full sm:w-auto justify-center"
                >
                    Reenviar correo
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-gray-500 hover:text-gray-900 transition-colors font-medium"
                    >Usar otra cuenta</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
