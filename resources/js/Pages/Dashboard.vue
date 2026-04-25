<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Recibimos los viajes desde el controlador
defineProps({
    viajes: Array,
});
</script>

<template>
    <Head title="Mis Viajes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Mis Itinerarios
                </h2>

                <Link
                    :href="route('viajes.create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md"
                >
                    + Nuevo Viaje
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    v-if="viajes.length === 0"
                    class="text-center bg-white p-12 rounded-xl shadow-sm border border-gray-100"
                >
                    <div class="text-5xl mb-4">🌍</div>
                    <h3 class="text-xl font-medium text-gray-900">
                        Aún no tienes viajes
                    </h3>
                    <p class="text-gray-500 mt-2">
                        Empieza a planificar tu próxima aventura con nuestra IA.
                    </p>
                    <Link
                        :href="route('viajes.create')"
                        class="mt-6 inline-block text-blue-600 font-semibold hover:underline"
                    >
                        Crear mi primer itinerario &rarr;
                    </Link>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="viaje in viajes"
                        :key="viaje.id"
                        class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 group"
                    >
                        <div class="relative h-48 overflow-hidden">
                            <img
                                :src="
                                    viaje.imagenes
                                        ? viaje.imagenes[0]
                                        : 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80&w=2070'
                                "
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                alt="Imagen del destino"
                            />
                            <div
                                class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-gray-800 shadow-sm"
                            >
                                🏨 {{ viaje.noches }} noches
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-blue-600"
                                    >{{ viaje.destino }}</span
                                >
                                <span class="text-xs text-gray-400">{{
                                    new Date(
                                        viaje.created_at,
                                    ).toLocaleDateString()
                                }}</span>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 mb-4 line-clamp-1 italic"
                            >
                                {{ viaje.titulo }}
                            </h3>

                            <div class="flex items-center justify-between mt-6">
                                <Link
                                    :href="route('viajes.show', viaje.id)"
                                    class="text-sm font-bold text-gray-900 hover:text-blue-600 transition-colors"
                                >
                                    Ver itinerario &rarr;
                                </Link>

                                <div class="flex gap-2">
                                    <button
                                        class="text-gray-400 hover:text-red-500 transition-colors"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
