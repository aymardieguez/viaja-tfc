<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

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
            <div class="flex justify-center mb-8">
                <Link href="/">
                    <span
                        class="text-3xl font-extrabold text-gray-900 tracking-tight text-center"
                    >
                        {{ tituloDinamico }}
                    </span>
                </Link>
            </div>

            <slot />
        </div>
    </div>
</template>
