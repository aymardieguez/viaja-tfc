<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

const props = defineProps({
    stats: Object,
    usuarios: Array,
    chartData: Array,
});

const dataGrafica = {
    labels: props.chartData.map((d) => d.mes),
    datasets: [
        {
            label: "Viajes Generados",
            data: props.chartData.map((d) => d.cantidad),
            backgroundColor: "#8b5cf6",
        },
    ],
};
</script>

<template>
    <Head title="Administración" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-purple-800">
                Panel de Control
            </h2>
        </template>

        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500"
                    >
                        <p class="text-gray-500 text-sm font-bold uppercase">
                            Usuarios Registrados
                        </p>
                        <p class="text-3xl font-black">
                            {{ stats.total_usuarios }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500"
                    >
                        <p class="text-gray-500 text-sm font-bold uppercase">
                            Total Itinerarios
                        </p>
                        <p class="text-3xl font-black">
                            {{ stats.total_viajes }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500"
                    >
                        <p class="text-gray-500 text-sm font-bold uppercase">
                            Tasa Modo Pro
                        </p>
                        <p class="text-3xl font-black">
                            {{
                                (
                                    (stats.viajes_modo_pro /
                                        stats.total_viajes) *
                                    100
                                ).toFixed(1)
                            }}%
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-bold text-gray-700 mb-4">
                            Crecimiento de Itinerarios
                        </h3>
                        <Bar :data="dataGrafica" />
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow overflow-hidden">
                        <h3 class="font-bold text-gray-700 mb-4">
                            Gestión de Usuarios
                        </h3>
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Nombre</th>
                                    <th class="px-4 py-2 text-left">Viajes</th>
                                    <th class="px-4 py-2 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in usuarios"
                                    :key="user.id"
                                    class="border-t"
                                >
                                    <td class="px-4 py-2">
                                        <p class="font-bold">{{ user.name }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ user.email }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ user.viajes_count }}
                                    </td>
                                    <td class="px-4 py-2 text-right space-x-3">
                                        <Link
                                            :href="
                                                route(
                                                    'admin.usuarios.viajes',
                                                    user.id,
                                                )
                                            "
                                            class="text-blue-600 hover:text-blue-900 font-bold text-xs"
                                        >
                                            Ver Viajes
                                        </Link>

                                        <button
                                            class="text-red-500 hover:underline text-xs font-bold"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
