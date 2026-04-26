<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    usuario: Object,
    viajes: Array,
});

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleDateString("es-ES");
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
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
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="viaje in viajes"
                                :key="viaje.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap font-bold text-gray-900"
                                >
                                    {{ viaje.destino }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ viaje.noches }} noches •
                                    {{ viaje.personas }} personas
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ formatearFecha(viaje.created_at) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right"
                                >
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
                            </tr>
                            <tr v-if="viajes.length === 0">
                                <td
                                    colspan="4"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Este usuario todavía no ha generado ningún
                                    viaje.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
