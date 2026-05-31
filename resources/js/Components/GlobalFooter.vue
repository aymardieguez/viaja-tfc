<script setup>
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <footer class="w-full bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-3 md:py-6 px-4 sm:px-6 lg:px-8">
            <div
                class="md:flex md:items-center md:justify-between text-center md:text-left flex flex-col md:flex-row gap-2 md:gap-0"
            >
                <div
                    class="flex justify-center items-center md:justify-start space-x-4 md:space-x-6 md:order-2 text-[10px] md:text-sm text-gray-500"
                >
                    <a
                        href="mailto:viaja.service@gmail.com"
                        class="hover:text-gray-900 transition font-medium"
                    >
                        Soporte
                    </a>
                    <Link
                        :href="route('privacidad')"
                        class="hover:text-gray-900 transition font-medium"
                    >
                        Privacidad
                    </Link>
                    <a
                        href="https://github.com/aymardieguez/viaja-tfc"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-semibold text-gray-700 hover:text-gray-900"
                    >
                        TFC - Aymar Salgado Diéguez
                    </a>
                </div>

                <div class="md:order-1">
                    <p
                        class="text-[10px] md:text-sm text-gray-400 md:text-gray-500"
                    >
                        &copy; 2026 VIAJA. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>
