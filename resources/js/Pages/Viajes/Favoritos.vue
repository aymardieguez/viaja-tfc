<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    viajes: Array,
});

// Función para quitar de favoritos con un solo clic
const quitarFavorito = (id) => {
    router.post(
        route("viajes.favorito.toggle", id),
        {},
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <Head title="Mis Favoritos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2"
                >
                    <svg
                        class="w-6 h-6 text-red-500"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                        />
                    </svg>
                    Mis Viajes Favoritos
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    v-if="viajes.length === 0"
                    class="text-center bg-white p-12 rounded-xl shadow-sm border border-gray-100"
                >
                    <div class="text-5xl mb-4">❤️</div>
                    <h3 class="text-xl font-medium text-gray-900">
                        Aún no tienes favoritos
                    </h3>
                    <p class="text-gray-500 mt-2">
                        Dale al corazón en los itinerarios que más te gusten
                        para guardarlos aquí.
                    </p>
                    <Link
                        :href="route('dashboard')"
                        class="mt-6 inline-block text-blue-600 font-semibold hover:underline"
                    >
                        Explorar mis viajes &rarr;
                    </Link>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="viaje in viajes"
                        :key="viaje.id"
                        class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 group"
                    >
                        <div class="relative h-48 overflow-hidden">
                            <img
                                :src="
                                    viaje.imagenes
                                        ? viaje.imagenes[0]
                                        : 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80&w=2070'
                                "
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                alt="Imagen del destino"
                            />
                            <div
                                class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-gray-800 shadow-sm"
                            >
                                🏨 {{ viaje.noches }} noches
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-blue-600"
                                    >{{ viaje.destino }}</span
                                >
                                <span class="text-xs text-gray-400">{{
                                    new Date(
                                        viaje.created_at,
                                    ).toLocaleDateString()
                                }}</span>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 mb-4 line-clamp-1 italic"
                            >
                                {{ viaje.titulo }}
                            </h3>

                            <div class="flex items-center justify-between mt-6">
                                <Link
                                    :href="route('viajes.show', viaje.id)"
                                    class="text-sm font-bold text-gray-900 hover:text-blue-600 transition-colors"
                                >
                                    Ver itinerario &rarr;
                                </Link>

                                <div class="flex gap-2">
                                    <button
                                        @click="quitarFavorito(viaje.id)"
                                        type="button"
                                        title="Quitar de favoritos"
                                        class="text-red-500 hover:text-gray-400 transition-colors focus:outline-none"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
