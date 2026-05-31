<script setup>
import GlobalFooter from "@/Components/GlobalFooter.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Política de Privacidad" />

    <div class="min-h-screen bg-gray-50 pt-10 pb-20 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100"
        >
            <Link
                href="/"
                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 mb-8 transition"
            >
                &larr; Volver a la página principal
            </Link>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">
                Política de Privacidad y Cookies
            </h1>
            <p class="text-sm text-gray-500 mb-8">
                Última actualización: Mayo de 2026
            </p>

            <div class="prose prose-blue text-gray-600 space-y-6">
                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        1. Recopilación de Datos
                    </h2>
                    <p>
                        Recopilamos únicamente los datos necesarios para
                        ofrecerte la generación de itinerarios inteligentes
                        mediante Inteligencia Artificial. Esto incluye tu
                        nombre, correo electrónico y las preferencias de viaje
                        que introduces voluntariamente.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        2. Uso de la Inteligencia Artificial
                    </h2>
                    <p>
                        Los datos sobre tus preferencias de destino, presupuesto
                        y fechas son enviados de forma anónima a la API de
                        Google Gemini para procesar los itinerarios. Ningún dato
                        personal (como tu email o nombre) es compartido con
                        modelos de IA externos.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        3. Política de Cookies
                    </h2>
                    <p>
                        Utilizamos cookies técnicas estrictamente necesarias
                        para mantener tu sesión iniciada de forma segura
                        (proporcionadas por Laravel) y una cookie HTTP de
                        preferencias para recordar tu decisión sobre este mismo
                        aviso de privacidad. Además, almacenamos temporalmente
                        datos técnicos de tu sesión para mantener tus filtros de
                        búsqueda y mejorar tu experiencia de navegación.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        4. Contacto
                    </h2>
                    <p>
                        Si tienes alguna duda sobre el tratamiento de tus datos
                        en esta aplicación, puedes contactar con el servicio de
                        soporte a través del correo electrónico:
                        <a
                            href="mailto:viaja.service@gmail.com"
                            class="text-blue-600 font-medium hover:underline transition-colors"
                            >viaja.service@gmail.com</a
                        >.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>
