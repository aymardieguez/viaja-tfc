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
            class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto flex flex-col items-center -mt-24 lg:-mt-32"
        >
            <img
                src="/images/logo-viaja-apaisado.png"
                alt="Logo VIAJA"
                class="w-full max-w-[280px] md:max-w-[500px] lg:max-w-[700px] object-contain mb-4 md:mb-8 transition-transform duration-500 hover:scale-105 drop-shadow-[0_0_30px_rgba(255,255,255,0.4)]"
            />

            <h1
                class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 drop-shadow-lg"
            >
                Descubre el mundo con <br />
                <span class="text-blue-400">Inteligencia Artificial</span>
            </h1>

            <p
                class="mt-4 text-lg md:text-2xl text-gray-200 mb-10 drop-shadow-md max-w-2xl mx-auto"
            >
                Crea itinerarios hiper-personalizados en segundos. Tú eliges el
                destino, nuestra IA hace la magia adaptándose a tus necesidades.
            </p>

            <div
                v-if="canLogin"
                class="flex flex-col sm:flex-row justify-center gap-4 w-full sm:w-auto"
            >
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all text-lg hover:scale-105 w-full sm:w-auto text-center"
                >
                    Ver mis viajes ✨
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all text-lg hover:scale-105 w-full sm:w-auto text-center"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold py-4 px-8 rounded-full backdrop-blur-sm transition-all text-lg hover:scale-105 w-full sm:w-auto text-center"
                    >
                        Crear Cuenta Gratis
                    </Link>
                </template>
            </div>
        </div>

        <div
            class="absolute bottom-5 w-full text-center text-gray-400 text-sm font-medium tracking-wide"
        >
            <p>Proyecto TFC - Aymar Salgado Diéguez</p>
        </div>
    </div>
</template>
