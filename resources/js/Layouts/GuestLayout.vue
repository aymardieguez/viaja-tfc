<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const page = usePage();

const tituloDinamico = computed(() => {
    if (page.url.startsWith("/login")) {
        return "Iniciar sesión";
    } else if (page.url.startsWith("/register")) {
        return "Registrarse";
    } else if (page.url.startsWith("/forgot-password")) {
        return "Recuperar contraseña";
    } else if (page.url.startsWith("/reset-password")) {
        return "Nueva contraseña";
    }
    return "Bienvenido";
});
</script>

<template>
    <div
        class="relative min-h-screen flex flex-col justify-center items-center py-6 bg-gray-900"
    >
        <div class="absolute inset-0 overflow-hidden z-0">
            <img
                src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop"
                alt="Fondo de viaje"
                class="w-full h-full object-cover opacity-30"
            />
        </div>

        <div
            class="relative z-10 w-[90%] sm:w-full sm:max-w-md px-6 sm:px-8 py-8 sm:py-10 bg-white shadow-2xl overflow-hidden rounded-2xl border border-gray-100"
        >
            <div class="flex flex-col items-center justify-center mb-8">
                <Link href="/" class="flex flex-col items-center group">
                    <ApplicationLogo
                        fetchpriority="high"
                        class="w-32 h-auto object-contain mix-blend-multiply transition-transform duration-300 group-hover:scale-105"
                    />
                    <h2
                        class="text-xl font-bold text-gray-600 tracking-tight text-center mt-4"
                    >
                        {{ tituloDinamico }}
                    </h2>
                </Link>
            </div>

            <slot />
        </div>
    </div>
</template>
