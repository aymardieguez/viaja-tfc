<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
    viaje: Object,
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
    <Head>
        <title>Viaje a {{ viaje.destino }} - VIAJA</title>

        <meta property="og:title" :content="viaje.titulo" />
        <meta
            property="og:description"
            :content="`Increíble itinerario de ${viaje.noches} noches para ${viaje.personas} personas. ¡Descúbrelo!`"
        />
        <meta
            property="og:image"
            v-if="viaje.imagenes && viaje.imagenes.length > 0"
            :content="viaje.imagenes[0]"
        />
        <meta property="og:type" content="website" />
    </Head>

    <nav
        class="bg-white border-b border-gray-100 py-4 px-6 flex justify-between items-center shadow-sm relative z-20"
    >
        <Link href="/" class="flex items-center gap-3 group">
            <ApplicationLogo
                class="block h-11 w-auto object-contain transition-transform group-hover:scale-105"
            />

            <div
                class="flex flex-col border-l-2 border-gray-200 pl-3 leading-none"
            >
                <span
                    class="text-2xl sm:text-3xl font-black tracking-tighter bg-gradient-to-r from-blue-600 to-gray-900 bg-clip-text text-transparent font-sans"
                >
                    VIAJA
                </span>
                <span
                    class="hidden sm:inline-block text-[10px] font-black tracking-widest text-gray-400 uppercase mt-1"
                >
                    Itinerarios Inteligentes
                </span>
            </div>
        </Link>

        <Link
            href="/register"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-full transition text-sm shadow-md hover:shadow-lg"
        >
            Crear mi propio viaje con IA ✨
        </Link>
    </nav>
    <div class="relative w-full h-32 sm:h-48 bg-gray-900">
        <img
            v-if="viaje.imagenes && viaje.imagenes.length > 0"
            :src="viaje.imagenes[0]"
            class="w-full h-full object-cover opacity-70"
            :alt="`Foto de ${viaje.destino}`"
        />
        <img
            v-else
            :src="`https://picsum.photos/seed/${viaje.id}viaje/1200/600`"
            class="w-full h-full object-cover opacity-70"
            alt="Paisaje inspiracional"
        />
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"
        ></div>

        <div class="absolute bottom-8 left-0 w-full p-4 sm:p-6 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <p
                    class="text-white/80 font-bold uppercase tracking-widest text-xs sm:text-sm mb-1"
                >
                    ✈️ Itinerario Compartido
                </p>
                <h1
                    class="text-2xl md:text-3xl font-extrabold drop-shadow-lg line-clamp-2"
                >
                    {{ viaje.titulo }}
                </h1>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div
            class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10"
        >
            <div
                class="bg-white shadow-xl rounded-2xl p-6 mb-8 flex flex-col sm:flex-row justify-between items-center border border-gray-100 gap-4"
            >
                <div class="text-center sm:text-left">
                    <p
                        class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1"
                    >
                        Destino
                    </p>
                    <p class="text-xl font-bold text-gray-800">
                        📍 {{ viaje.destino }}
                    </p>
                </div>
                <div class="text-center sm:border-x border-gray-100 sm:px-8">
                    <p
                        class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1"
                    >
                        Duración
                    </p>
                    <p class="text-xl font-bold text-gray-800">
                        🌙 {{ viaje.noches }} noches
                    </p>
                </div>
                <div class="text-center sm:text-right">
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

            <div class="space-y-8">
                <div
                    v-for="dia in viaje.dias"
                    :key="dia.id"
                    class="bg-white shadow-md rounded-2xl border border-gray-100 overflow-hidden"
                >
                    <div
                        class="bg-blue-50/50 border-b border-gray-100 px-6 py-4 flex items-center"
                    >
                        <span
                            class="bg-blue-600 text-white font-black rounded-lg min-w-[2.5rem] h-10 flex items-center justify-center shadow-inner"
                        >
                            {{ dia.numero_dia }}
                        </span>
                        <h4 class="text-xl font-bold text-gray-900 ml-4">
                            {{ dia.titulo }}
                        </h4>
                    </div>
                    <div
                        class="p-6 md:p-8 text-gray-700 leading-relaxed text-base md:text-lg break-words"
                    >
                        <div
                            v-html="formatearDescripcion(dia.descripcion)"
                            class="prose max-w-none"
                        ></div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center text-gray-400 text-sm pb-8">
                <p>Este itinerario ha sido generado con la IA de VIAJA.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(.prose br + ☀️),
:deep(.prose br + 🌇),
:deep(.prose br + 🌙),
:deep(.prose br + 🏨) {
    display: block;
    margin-top: 1.5rem;
}
</style>
