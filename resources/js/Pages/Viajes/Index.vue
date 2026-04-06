<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    viajes: Array,
});
</script>

<template>
    <Head title="Mis Viajes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Viajes Guardados
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6 flex justify-end">
                    <Link
                        :href="route('viajes.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow"
                    >
                        + Planear Nuevo Viaje
                    </Link>
                </div>

                <div
                    v-if="viajes.length === 0"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500"
                >
                    Aún no has planeado ningún viaje.
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="viaje in viajes"
                        :key="viaje.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow"
                    >
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                {{ viaje.titulo }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                📍 {{ viaje.destino }}
                            </p>
                            <div
                                class="flex justify-between text-sm text-gray-500"
                            >
                                <span>🌙 {{ viaje.noches }} noches</span>
                                <span>💰 {{ viaje.presupuesto }}€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
