<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const mostrarFiltros = ref(false);

const tipoPresupuesto = ref("total");
const cantidadInput = ref("");

const meses = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
];
const rangosEdad = [
    "Jóvenes (18-26)",
    "Adultos (27-60)",
    "Tercera edad (+60)",
    "Familia con niños",
    "Familia con adolescentes",
];

const listaNecesidades = [
    "Sin Gluten (Celíaco)",
    "Vegetariano",
    "Vegano",
    "Sin Lactosa",
    "Silla de Ruedas",
    "Movilidad Reducida",
    "Viaja con Mascotas",
];

const form = useForm({
    destino: "",
    presupuesto: "",
    noches: 3,
    personas: 2,
    mes: "",
    rango_edad: "",
    modo_pro: false,
    intereses: [],
    filtros: [],
    filtros_extra: "",
});

watch(
    [cantidadInput, tipoPresupuesto, () => form.personas],
    ([nuevaCantidad, nuevoTipo, numPersonas]) => {
        const cantidad = parseFloat(nuevaCantidad) || 0;
        if (nuevoTipo === "persona") {
            form.presupuesto = cantidad * numPersonas;
        } else {
            form.presupuesto = cantidad;
        }
    },
);

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
                                    >Mes del viaje</label
                                >
                                <select
                                    v-model="form.mes"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                >
                                    <option value="" disabled>
                                        Seleccionar...
                                    </option>
                                    <option
                                        v-for="mes in meses"
                                        :key="mes"
                                        :value="mes"
                                    >
                                        {{ mes }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Perfil del grupo</label
                                >
                                <select
                                    v-model="form.rango_edad"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                >
                                    <option value="" disabled>
                                        Elige un perfil...
                                    </option>
                                    <option
                                        v-for="rango in rangosEdad"
                                        :key="rango"
                                        :value="rango"
                                    >
                                        {{ rango }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Presupuesto (€)</label
                                >

                                <div
                                    class="inline-flex bg-gray-100 p-1 rounded-md mb-2 w-fit"
                                >
                                    <button
                                        type="button"
                                        @click="tipoPresupuesto = 'total'"
                                        :class="
                                            tipoPresupuesto === 'total'
                                                ? 'bg-white shadow-sm text-blue-600 font-bold'
                                                : 'text-gray-500 hover:text-gray-700'
                                        "
                                        class="px-3 py-1 text-xs rounded transition-all"
                                    >
                                        Total
                                    </button>
                                    <button
                                        type="button"
                                        @click="tipoPresupuesto = 'persona'"
                                        :class="
                                            tipoPresupuesto === 'persona'
                                                ? 'bg-white shadow-sm text-blue-600 font-bold'
                                                : 'text-gray-500 hover:text-gray-700'
                                        "
                                        class="px-3 py-1 text-xs rounded transition-all"
                                    >
                                        Por persona
                                    </button>
                                </div>

                                <input
                                    v-model="cantidadInput"
                                    type="number"
                                    required
                                    :placeholder="
                                        tipoPresupuesto === 'total'
                                            ? 'Ej: 1000'
                                            : 'Ej: 500'
                                    "
                                    class="mt-auto block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />

                                <div class="h-5 mt-1">
                                    <p
                                        v-if="
                                            tipoPresupuesto === 'persona' &&
                                            cantidadInput
                                        "
                                        class="text-xs text-blue-600 font-medium"
                                    >
                                        Total calculado:
                                        {{ form.presupuesto }} €
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Número de noches</label
                                >
                                <input
                                    v-model="form.noches"
                                    type="number"
                                    min="1"
                                    max="15"
                                    required
                                    class="mt-auto block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                />
                                <div class="h-5 mt-1"></div>
                            </div>
                        </div>

                        <div class="pt-2 border-t">
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
                                        /><span>Cultura</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Naturaleza"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        /><span>Naturaleza</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Gastronomía"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        /><span>Gastronomía</span></label
                                    >
                                    <label class="flex items-center space-x-2"
                                        ><input
                                            v-model="form.intereses"
                                            value="Vida Nocturna y Fiesta"
                                            type="checkbox"
                                            class="rounded text-blue-600"
                                        /><span>Fiesta</span></label
                                    >
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h3
                                    class="text-sm font-bold text-gray-700 mb-3"
                                >
                                    Necesidades y Requisitos (Opcional)
                                </h3>

                                <div
                                    class="grid grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-4 mb-4"
                                >
                                    <label
                                        v-for="necesidad in listaNecesidades"
                                        :key="necesidad"
                                        class="flex items-center space-x-2"
                                    >
                                        <input
                                            v-model="form.filtros"
                                            :value="necesidad"
                                            type="checkbox"
                                            class="rounded text-blue-600 border-gray-300"
                                        />
                                        <span class="text-sm text-gray-700">{{
                                            necesidad
                                        }}</span>
                                    </label>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 mb-1"
                                        >Otras necesidades específicas:</label
                                    >
                                    <input
                                        v-model="form.filtros_extra"
                                        type="text"
                                        placeholder="Ej: Alergia a los frutos secos, fobia a las alturas..."
                                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 flex items-center justify-between mt-6"
                        >
                            <div>
                                <h4 class="text-sm font-bold text-indigo-900">
                                    🚀 Modo Pro
                                </h4>
                                <p class="text-xs text-indigo-700 mt-1">
                                    La IA generará un itinerario más completo y
                                    profesional.
                                </p>
                            </div>
                            <label
                                class="relative inline-flex items-center cursor-pointer"
                            >
                                <input
                                    type="checkbox"
                                    v-model="form.modo_pro"
                                    class="sr-only peer"
                                />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"
                                ></div>
                            </label>
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
