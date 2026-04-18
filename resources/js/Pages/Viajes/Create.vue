<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const mostrarFiltros = ref(false);

const form = useForm({
    destino: "",
    presupuesto: "",
    noches: 3,
    personas: 2,
    intereses: [],
    filtros: {
        celiaco: false,
        silla_ruedas: false,
    },
});

const submit = () => {
    form.post(route("viajes.store"));
};
</script>

<template>
    <Head title="Planear Viaje" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Planear Nuevo Viaje
            </h2>
        </template>

        <div
            v-if="form.processing"
            class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gray-900 bg-opacity-80 backdrop-blur-sm text-white"
        >
            <svg
                class="animate-spin h-16 w-16 text-blue-500 mb-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
            <h2 class="text-2xl font-bold animate-pulse">
                Se está creando tu viaje...
            </h2>
            <p class="mt-2 text-gray-300">
                Esto puede tardar hasta dos minutos. Buscando los mejores sitios
                en {{ form.destino }}...
            </p>
        </div>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >¿A dónde quieres ir?</label
                                >
                                <input
                                    v-model="form.destino"
                                    type="text"
                                    required
                                    placeholder="Ej: Roma, Italia"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nº de Personas</label
                                >
                                <input
                                    v-model="form.personas"
                                    type="number"
                                    min="1"
                                    max="20"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Presupuesto total (€)</label
                                >
                                <input
                                    v-model="form.presupuesto"
                                    type="number"
                                    required
                                    placeholder="Ej: 1000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Número de noches</label
                                >
                                <input
                                    v-model="form.noches"
                                    type="number"
                                    min="1"
                                    max="15"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />
                            </div>
                        </div>

                        <div class="pt-4 border-t">
                            <button
                                type="button"
                                @click="mostrarFiltros = !mostrarFiltros"
                                class="text-blue-600 hover:text-blue-800 font-medium flex items-center"
                            >
                                <span class="mr-2">{{
                                    mostrarFiltros
                                        ? "Ocultar Opciones Avanzadas ▲"
                                        : "Mostrar Opciones Avanzadas ▼"
                                }}</span>
                            </button>
                        </div>

                        <div
                            v-show="mostrarFiltros"
                            class="space-y-6 bg-gray-50 p-6 rounded-lg"
                        >
                            <div>
                                <h3
                                    class="text-sm font-bold text-gray-700 mb-3"
                                >
                                    ¿Qué tipo de viaje buscas?
                                </h3>
                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-4"
                                >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Cultura y Museos"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        />
                                        <span>Cultura</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Naturaleza"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        />
                                        <span>Naturaleza</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Gastronomía"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        />
                                        <span>Gastronomía</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Vida Nocturna y Fiesta"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        />
                                        <span>Fiesta</span></label
                                    >
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h3
                                    class="text-sm font-bold text-gray-700 mb-3"
                                >
                                    Otros filtros
                                </h3>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"
                                        ><input
                                            v-model="form.filtros.celiaco"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        /><span class="ml-2 text-sm"
                                            >Menú Celíaco</span
                                        ></label
                                    >
                                    <label class="flex items-center"
                                        ><input
                                            v-model="form.filtros.silla_ruedas"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        /><span class="ml-2 text-sm"
                                            >Silla de Ruedas</span
                                        ></label
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg disabled:opacity-50 transition-all"
                            >
                                Generar Itinerario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
