<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
    viaje: Object,
});

const carruselRef = ref(null);
let intervalo = null;

onMounted(() => {
    intervalo = setInterval(() => {
        if (carruselRef.value) {
            const maxScroll =
                carruselRef.value.scrollWidth - carruselRef.value.clientWidth;
            if (carruselRef.value.scrollLeft >= maxScroll - 10) {
                carruselRef.value.scrollTo({ left: 0, behavior: "smooth" });
            } else {
                carruselRef.value.scrollBy({
                    left: carruselRef.value.clientWidth,
                    behavior: "smooth",
                });
            }
        }
    }, 3500);
});

onUnmounted(() => {
    clearInterval(intervalo);
});

const formatearDescripcion = (texto) => {
    if (!texto) return "";
    let html = texto.replace(
        /\*\*(.*?)\*\*/g,
        '<strong class="text-blue-700 font-extrabold">$1</strong>',
    );
    html = html.replace(/\n/g, "<br>");
    return html;
};
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
                    :href="route('dashboard')"
                    class="text-blue-600 hover:underline text-sm font-semibold transition-colors"
                >
                    &larr; Volver al Panel de Control
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="mb-8 w-full overflow-hidden rounded-2xl shadow-xl relative h-64 sm:h-96"
                >
                    <div
                        ref="carruselRef"
                        class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar h-full w-full bg-gray-200"
                    >
                        <template
                            v-if="viaje.imagenes && viaje.imagenes.length > 0"
                        >
                            <div
                                v-for="(imgUrl, index) in viaje.imagenes"
                                :key="index"
                                class="snap-center shrink-0 w-full h-full relative"
                            >
                                <img
                                    :src="imgUrl"
                                    class="w-full h-full object-cover"
                                    :alt="`Foto de ${viaje.destino}`"
                                    loading="lazy"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"
                                ></div>
                            </div>
                        </template>

                        <template v-else>
                            <div
                                v-for="n in 3"
                                :key="'fallback-' + n"
                                class="snap-center shrink-0 w-full h-full relative"
                            >
                                <img
                                    :src="`https://picsum.photos/seed/${viaje.id}${n}viaje/1200/600`"
                                    class="w-full h-full object-cover"
                                    alt="Paisaje inspiracional"
                                    loading="lazy"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"
                                ></div>
                            </div>
                        </template>
                    </div>

                    <div
                        class="absolute bottom-0 left-0 w-full p-6 text-white pointer-events-none z-10"
                    >
                        <h1
                            class="text-3xl sm:text-4xl font-extrabold drop-shadow-md"
                        >
                            {{ viaje.titulo }}
                        </h1>
                        <p
                            class="text-sm sm:text-base opacity-90 mt-2 font-medium drop-shadow-sm flex gap-3"
                        >
                            <span>👥 {{ viaje.personas }} Personas</span>
                            <span>|</span>
                            <span>🌙 {{ viaje.noches }} Noches</span>
                            <span>|</span>
                            <span>💰 {{ viaje.presupuesto }}€</span>
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 mb-8 flex justify-between items-center border border-gray-100"
                >
                    <div>
                        <p
                            class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1"
                        >
                            Destino
                        </p>
                        <p class="text-xl font-bold text-gray-800">
                            📍 {{ viaje.destino }}
                        </p>
                    </div>
                    <div class="text-center border-x border-gray-100 px-8">
                        <p
                            class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1"
                        >
                            Duración
                        </p>
                        <p class="text-xl font-bold text-gray-800">
                            🌙 {{ viaje.noches }} noches
                        </p>
                    </div>
                    <div class="text-right">
                        <p
                            class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1"
                        >
                            Presupuesto
                        </p>
                        <p class="text-xl font-bold text-green-600">
                            💰 {{ viaje.presupuesto }}€
                        </p>
                    </div>
                </div>

                <h3
                    class="text-2xl font-bold text-gray-900 mb-6 flex items-center"
                >
                    <span class="bg-blue-100 p-2 rounded-lg mr-3"
                        >Tu Itinerario Diario</span
                    >
                </h3>

                <div
                    v-if="viaje.dias.length === 0"
                    class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg"
                >
                    <p class="text-yellow-700 font-medium">
                        Hubo un problema generando los días de este viaje o la
                        IA tardó demasiado.
                    </p>
                </div>

                <div v-else class="space-y-6">
                    <div
                        v-for="dia in viaje.dias"
                        :key="dia.id"
                        class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden"
                    >
                        <div
                            class="bg-blue-50/50 border-b border-gray-100 px-6 py-4 flex items-center"
                        >
                            <span
                                class="bg-blue-600 text-white font-black rounded-lg w-10 h-10 flex items-center justify-center shadow-inner"
                            >
                                {{ dia.numero_dia }}
                            </span>
                            <h4 class="text-xl font-bold text-gray-900 ml-4">
                                {{ dia.titulo }}
                            </h4>
                        </div>

                        <div
                            class="p-6 md:p-8 text-gray-700 leading-relaxed text-base md:text-lg"
                        >
                            <div
                                v-html="formatearDescripcion(dia.descripcion)"
                                class="prose max-w-none"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

:deep(.prose br + ☀️),
:deep(.prose br + 🌇),
:deep(.prose br + 🌙),
:deep(.prose br + 🏨) {
    display: block;
    margin-top: 1.5rem;
}
</style>
