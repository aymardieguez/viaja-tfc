<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";

const mostrarBanner = ref(false);

onMounted(() => {
    // Buscamos si la cookie existe en el navegador
    if (!document.cookie.includes("viaja_cookies_accepted=true")) {
        mostrarBanner.value = true;
    }
});

const aceptarCookies = () => {
    const fecha = new Date();
    fecha.setTime(fecha.getTime() + 365 * 24 * 60 * 60 * 1000);
    // Guardamos la cookie con parámetros de seguridad
    document.cookie =
        "viaja_cookies_accepted=true;expires=" +
        fecha.toUTCString() +
        ";path=/;SameSite=Lax";

    mostrarBanner.value = false;
};
</script>

<template>
    <div
        v-if="mostrarBanner"
        class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-50 px-2 sm:px-4"
    >
        <div
            class="max-w-7xl mx-auto rounded-lg bg-gray-900 border border-gray-700 shadow-2xl p-3 sm:p-4 flex flex-col sm:flex-row items-center justify-between gap-4"
        >
            <div class="flex items-center text-white text-xs sm:text-sm">
                <span class="text-2xl mr-3">🍪</span>
                <p>
                    Utilizamos cookies para garantizarte la mejor experiencia en
                    nuestra IA.
                    <span class="hidden sm:inline"
                        >Al continuar navegando, consideramos que aceptas su
                        uso.</span
                    >
                </p>
            </div>

            <div class="flex flex-shrink-0 w-full sm:w-auto gap-2">
                <Link
                    :href="route('privacidad')"
                    class="flex-1 sm:flex-none text-center px-4 py-2 border border-gray-600 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition"
                >
                    Leer más
                </Link>
                <button
                    @click="aceptarCookies"
                    class="flex-1 sm:flex-none text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-sm text-white hover:bg-blue-500 transition shadow-md"
                >
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</template>
