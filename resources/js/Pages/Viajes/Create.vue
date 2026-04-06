<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    destino: "",
    presupuesto: "",
    noches: 3,
    filtros: {
        celiaco: false,
        silla_ruedas: false,
    },
});

//se ejecuta al darle a "Generar Viaje"
const submit = () => {
    // envía datos por POST a la ruta que creamos en web.php
    form.post(route("viajes.store"));
};
</script>

<template>
    <Head title="Planear Viaje" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Planear Nuevo Viaje con IA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >¿A dónde quieres ir?</label
                            >
                            <input
                                v-model="form.destino"
                                type="text"
                                required
                                placeholder="Ej: Roma, Italia"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Presupuesto aproximado (€)</label
                                >
                                <input
                                    v-model="form.presupuesto"
                                    type="number"
                                    required
                                    placeholder="Ej: 500"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">
                                Necesidades especiales (Opcional)
                            </h3>
                            <div class="flex space-x-6">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.filtros.celiaco"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Menú Celíaco</span
                                    >
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="form.filtros.silla_ruedas"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Silla de Ruedas</span
                                    >
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50"
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
