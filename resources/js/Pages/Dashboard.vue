<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { ref } from "vue";

// Recibimos los viajes desde el controlador
defineProps({
    viajes: Array,
});

const mostrarModal = ref(false);
const viajeSeleccionado = ref(null);

const confirmarBorrado = (id) => {
    viajeSeleccionado.value = id;
    mostrarModal.value = true;
};

const cancelarBorrado = () => {
    mostrarModal.value = false;
    viajeSeleccionado.value = null;
};

const borrarViaje = () => {
    if (viajeSeleccionado.value) {
        router.delete(route("viajes.destroy", viajeSeleccionado.value), {
            preserveScroll: true,
            onSuccess: () => {
                mostrarModal.value = false;
                viajeSeleccionado.value = null;
            },
        });
    }
};
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-8"
                >
                    <div
                        v-for="viaje in viajes"
                        :key="viaje.id"
                        class="bg-white rounded-xl md:rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col group"
                    >
                        <div
                            class="relative h-24 md:h-48 overflow-hidden shrink-0"
                        >
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
                                class="absolute top-2 right-2 md:top-3 md:right-3 bg-white/90 backdrop-blur px-2 py-0.5 md:px-3 md:py-1 rounded-full text-[10px] md:text-xs font-bold text-gray-800 shadow-sm"
                            >
                                🏨 {{ viaje.noches }}
                                <span class="hidden md:inline">noches</span
                                ><span class="md:hidden">n.</span>
                            </div>
                        </div>

                        <div class="p-3 md:p-6 flex flex-col flex-grow">
                            <div
                                class="flex items-center justify-between mb-1 md:mb-2 gap-2"
                            >
                                <span
                                    class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-blue-600 truncate"
                                    >{{ viaje.destino }}</span
                                >
                                <span
                                    class="text-[9px] md:text-xs text-gray-400 whitespace-nowrap"
                                    >{{
                                        new Date(
                                            viaje.created_at,
                                        ).toLocaleDateString()
                                    }}</span
                                >
                            </div>

                            <h3
                                class="text-sm md:text-xl font-bold text-gray-900 mb-2 md:mb-4 line-clamp-2 md:line-clamp-1 italic"
                            >
                                {{ viaje.titulo }}
                            </h3>

                            <div
                                class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50 md:border-none md:pt-0"
                            >
                                <Link
                                    :href="route('viajes.show', viaje.id)"
                                    class="text-[11px] md:text-sm font-bold text-gray-900 hover:text-blue-600 transition-colors"
                                >
                                    Ver
                                    <span class="hidden md:inline"
                                        >itinerario</span
                                    >
                                    &rarr;
                                </Link>

                                <button
                                    @click="confirmarBorrado(viaje.id)"
                                    type="button"
                                    title="Eliminar viaje"
                                    class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none p-1 md:p-0"
                                >
                                    <svg
                                        class="w-4 h-4 md:w-5 md:h-5"
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

        <ConfirmModal
            :show="mostrarModal"
            title="¿Eliminar viaje?"
            message="Se borrará todo el itinerario y las imágenes asociadas a este destino. Esta acción no se puede deshacer."
            confirmText="Sí, eliminar"
            @close="cancelarBorrado"
            @confirm="borrarViaje"
        />
    </AuthenticatedLayout>
</template>
