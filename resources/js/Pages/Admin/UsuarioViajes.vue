<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { ref } from "vue";

defineProps({
    usuario: Object,
    viajes: Array,
});

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleDateString("es-ES");
};

//vista móvil para mostrar detalles adicionales al hacer click en un viaje
const viajeExpandido = ref(null);

const toggleDetalles = (id) => {
    if (viajeExpandido.value === id) {
        viajeExpandido.value = null;
    } else {
        viajeExpandido.value = id;
    }
};

const mostrarModal = ref(false);
const viajeIdSeleccionado = ref(null);

const confirmarBorrado = (id) => {
    viajeIdSeleccionado.value = id;
    mostrarModal.value = true;
};

const cancelarBorrado = () => {
    mostrarModal.value = false;
    viajeIdSeleccionado.value = null;
};

const ejecutarBorrado = () => {
    if (viajeIdSeleccionado.value) {
        router.delete(
            route("admin.viajes.destroy", viajeIdSeleccionado.value),
            {
                preserveScroll: true,
                onSuccess: () => {
                    mostrarModal.value = false;
                    viajeIdSeleccionado.value = null;
                },
            },
        );
    }
};
</script>

<template>
    <Head :title="'Viajes de ' + usuario.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Viajes de:
                    <span class="text-purple-600">{{ usuario.name }}</span>
                </h2>
                <Link
                    :href="route('admin.dashboard')"
                    class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition"
                >
                    Volver al Panel de control
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white shadow-sm sm:rounded-lg w-full overflow-hidden"
                >
                    <div class="block sm:hidden divide-y divide-gray-100">
                        <div
                            v-for="viaje in viajes"
                            :key="'mob-' + viaje.id"
                            class="bg-white"
                        >
                            <button
                                @click="toggleDetalles(viaje.id)"
                                class="w-full text-left px-4 py-4 flex justify-between items-center focus:outline-none transition-colors hover:bg-gray-50"
                            >
                                <span
                                    class="font-bold text-gray-900 truncate pr-4"
                                    >{{ viaje.destino }}</span
                                >
                                <svg
                                    :class="{
                                        'rotate-180':
                                            viajeExpandido === viaje.id,
                                    }"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>

                            <div
                                v-show="viajeExpandido === viaje.id"
                                class="px-4 pb-5 pt-1 bg-gray-50"
                            >
                                <div
                                    class="space-y-2 text-sm text-gray-600 mb-4 border-t border-gray-200 pt-3"
                                >
                                    <p class="flex justify-between">
                                        <span class="font-bold text-gray-700"
                                            >Duración:</span
                                        >
                                        <span>{{ viaje.noches }} noches</span>
                                    </p>
                                    <p class="flex justify-between">
                                        <span class="font-bold text-gray-700"
                                            >Grupo:</span
                                        >
                                        <span
                                            >{{ viaje.personas }} personas</span
                                        >
                                    </p>
                                    <p class="flex justify-between">
                                        <span class="font-bold text-gray-700"
                                            >Creado:</span
                                        >
                                        <span>{{
                                            formatearFecha(viaje.created_at)
                                        }}</span>
                                    </p>
                                    <p
                                        class="flex justify-between items-center"
                                    >
                                        <span class="font-bold text-gray-700"
                                            >Modo Pro:</span
                                        >
                                        <span
                                            v-if="viaje.modo_pro"
                                            class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-purple-100 text-purple-800 uppercase tracking-wide"
                                            >Sí</span
                                        >
                                        <span
                                            v-else
                                            class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-200 text-gray-600 uppercase tracking-wide"
                                            >No</span
                                        >
                                    </p>
                                </div>

                                <button
                                    @click="confirmarBorrado(viaje.id)"
                                    type="button"
                                    class="w-full bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-800 font-bold py-2.5 rounded-lg text-xs transition-colors border border-red-100 shadow-sm"
                                >
                                    Eliminar Viaje
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="viajes.length === 0"
                            class="px-6 py-10 text-center text-gray-500 text-sm"
                        >
                            Este usuario todavía no ha generado ningún viaje.
                        </div>
                    </div>

                    <div class="hidden sm:block overflow-x-auto w-full">
                        <table
                            class="min-w-full divide-y divide-gray-200 whitespace-nowrap"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Destino
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Detalles
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Fecha de Creación
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Modo Pro
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="viaje in viajes"
                                    :key="viaje.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 font-bold text-gray-900"
                                    >
                                        {{ viaje.destino }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ viaje.noches }} noches •
                                        {{ viaje.personas }} personas
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ formatearFecha(viaje.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            v-if="viaje.modo_pro"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800"
                                            >Sí</span
                                        >
                                        <span
                                            v-else
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                                            >No</span
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button
                                            @click="confirmarBorrado(viaje.id)"
                                            type="button"
                                            class="text-red-500 hover:text-red-800 font-bold text-xs transition-colors"
                                        >
                                            Borrar Viaje
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="viajes.length === 0">
                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        Este usuario todavía no ha generado
                                        ningún viaje.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="mostrarModal"
            title="¿Borrar este itinerario?"
            message="Esta acción borrará el viaje permanentemente de la base de datos."
            confirmText="Eliminar viaje"
            @close="cancelarBorrado"
            @confirm="ejecutarBorrado"
        />
    </AuthenticatedLayout>
</template>
