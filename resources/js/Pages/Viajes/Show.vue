<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3"; // Añadimos router aquí
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    viaje: Object,
    shareUrl: String,
});

// --- COMPARTIR ENLACE ---
const copiado = ref(false);
const copiarEnlace = async () => {
    try {
        await navigator.clipboard.writeText(props.shareUrl);
        copiado.value = true;
        setTimeout(() => {
            copiado.value = false;
        }, 3000);
    } catch (err) {
        console.error("Error al copiar: ", err);
    }
};

// --- FAVORITOS Y VALORACIÓN (NUEVO) ---
const toggleFavorito = () => {
    router.post(
        route("viajes.favorito.toggle", props.viaje.id),
        {},
        {
            preserveScroll: true,
        },
    );
};

const estrellaHover = ref(0);
const valorar = (puntos) => {
    router.post(
        route("viajes.valorar", props.viaje.id),
        {
            valoracion: puntos,
        },
        {
            preserveScroll: true,
        },
    );
};

// --- CARRUSEL ---
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
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
                <div class="flex items-center gap-3">
                    <button
                        @click="toggleFavorito"
                        class="p-2 rounded-full hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-red-200"
                        :title="
                            viaje.favorito
                                ? 'Quitar de favoritos'
                                : 'Añadir a favoritos'
                        "
                    >
                        <svg
                            v-if="viaje.favorito"
                            class="w-7 h-7 text-red-500 drop-shadow-sm"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-7 h-7 text-gray-400 hover:text-red-400"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                            />
                        </svg>
                    </button>
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        {{ viaje.titulo }}
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <a
                        :href="route('viajes.pdf', viaje.id)"
                        class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-sm"
                    >
                        <svg
                            class="w-4 h-4 text-red-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Descargar PDF
                    </a>

                    <button
                        @click="copiarEnlace"
                        class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg shadow-sm transition-all text-sm"
                    >
                        <svg
                            v-if="!copiado"
                            class="w-4 h-4 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        {{ copiado ? "¡Enlace copiado!" : "Copiar enlace" }}
                    </button>

                    <Link
                        :href="route('dashboard')"
                        class="text-blue-600 hover:underline text-sm font-semibold transition-colors"
                    >
                        &larr; Volver al Panel
                    </Link>
                </div>
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

                <div
                    class="mt-12 bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100"
                >
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{
                            viaje.valoracion
                                ? "¡Gracias por tu valoración!"
                                : "¿Qué te ha parecido este itinerario?"
                        }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Ayuda a la IA de VIAJA a mejorar dándole una puntuación
                        a este viaje.
                    </p>

                    <div class="flex justify-center gap-2">
                        <button
                            v-for="n in 5"
                            :key="n"
                            @click="valorar(n)"
                            @mouseenter="estrellaHover = n"
                            @mouseleave="estrellaHover = 0"
                            class="focus:outline-none transition-transform hover:scale-110"
                        >
                            <svg
                                :class="[
                                    'w-10 h-10 transition-colors duration-200',
                                    n <= (estrellaHover || viaje.valoracion)
                                        ? 'text-yellow-400 drop-shadow-sm'
                                        : 'text-gray-200',
                                ]"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                />
                            </svg>
                        </button>
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
