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
                    class="mb-8 w-full overflow-hidden rounded-xl shadow-lg relative h-64 sm:h-80"
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
                                class="snap-center shrink-0 w-full h-full"
                            >
                                <img
                                    :src="imgUrl"
                                    class="w-full h-full object-cover"
                                    :alt="`Foto real de ${viaje.destino}`"
                                    loading="lazy"
                                />
                            </div>
                        </template>

                        <template v-else>
                            <div
                                v-for="n in 3"
                                :key="'fallback-' + n"
                                class="snap-center shrink-0 w-full h-full"
                            >
                                <img
                                    :src="`https://picsum.photos/seed/${viaje.id}${n}viaje/1200/600`"
                                    class="w-full h-full object-cover"
                                    alt="Paisaje inspiracional"
                                    loading="lazy"
                                />
                            </div>
                        </template>
                    </div>

                    <div
                        class="absolute bottom-0 left-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent w-full p-6 text-white pointer-events-none"
                    >
                        <h1 class="text-3xl font-extrabold">
                            {{ viaje.titulo }}
                        </h1>
                        <p class="text-sm opacity-90 mt-1 font-medium">
                            👥 {{ viaje.personas }} Personas | 🌙
                            {{ viaje.noches }} Noches | 💰
                            {{ viaje.presupuesto }}€
                        </p>
                    </div>
                </div>
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

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
