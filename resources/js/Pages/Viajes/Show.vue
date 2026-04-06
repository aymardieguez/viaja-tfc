<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    viaje: Object,
});
</script>

<template>
    <Head :title="viaje.titulo" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ viaje.titulo }}
                </h2>
                <Link
                    :href="route('viajes.index')"
                    class="text-blue-600 hover:underline text-sm font-semibold"
                >
                    &larr; Volver a Mis Viajes
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 flex justify-between items-center"
                >
                    <div>
                        <p class="text-gray-500 text-sm">Destino</p>
                        <p class="text-xl font-bold">📍 {{ viaje.destino }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Duración</p>
                        <p class="text-xl font-bold">
                            🌙 {{ viaje.noches }} noches
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Presupuesto</p>
                        <p class="text-xl font-bold text-green-600">
                            💰 {{ viaje.presupuesto }}€
                        </p>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    Tu Itinerario
                </h3>

                <div
                    v-if="viaje.dias.length === 0"
                    class="bg-yellow-50 border-l-4 border-yellow-400 p-4"
                >
                    <p class="text-yellow-700">
                        Hubo un problema generando los días de este viaje o la
                        IA tardó demasiado.
                    </p>
                </div>

                <div v-else class="space-y-6">
                    <div
                        v-for="dia in viaje.dias"
                        :key="dia.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500"
                    >
                        <h4 class="text-lg font-bold text-gray-900 mb-4">
                            Día {{ dia.numero_dia }}: {{ dia.titulo }}
                        </h4>
                        <p
                            class="text-gray-700 whitespace-pre-wrap leading-relaxed"
                        >
                            {{ dia.descripcion }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
