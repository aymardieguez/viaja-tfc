<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

</script>

<template>
    <Head title="Bienvenido a Viaja" />

    <div
        class="relative min-h-screen bg-gray-900 flex items-center justify-center"
    >
        <div class="absolute inset-0 overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop"
                alt="Fondo de viaje"
                class="w-full h-full object-cover opacity-40"
            />
        </div>

        <div
            class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto"
        >
            <h1
                class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6 drop-shadow-lg"
            >
                Descubre el mundo con <br />
                <span class="text-blue-400">Inteligencia Artificial</span>
            </h1>

            <p
                class="mt-4 text-xl md:text-2xl text-gray-200 mb-10 drop-shadow-md"
            >
                Crea itinerarios hiper-personalizados en segundos. Tú eliges el
                destino, nuestras IA hace la magia adaptándose a tus
                necesidades.
            </p>

            <div
                v-if="canLogin"
                class="flex flex-col sm:flex-row justify-center gap-4"
            >
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('viajes.index')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-lg transition-all text-lg hover:scale-105"
                >
                    Ir a Mis Viajes ✨
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-lg transition-all text-lg hover:scale-105"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="bg-white/20 hover:bg-white/30 border border-white/50 text-white font-bold py-4 px-8 rounded-full backdrop-blur-sm transition-all text-lg hover:scale-105"
                    >
                        Crear Cuenta Gratis
                    </Link>
                </template>
            </div>
        </div>

        <div class="absolute bottom-5 w-full text-center text-gray-400 text-sm">
            <p>Proyecto TFC - Aymar Salgado Diéguez</p>
        </div>
    </div>
</template>
