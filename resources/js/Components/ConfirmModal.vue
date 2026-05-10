<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "¿Estás seguro?",
    },
    message: {
        type: String,
        default: "Esta acción no se puede deshacer.",
    },
    confirmText: {
        type: String,
        default: "Sí, confirmar",
    },
    cancelText: {
        type: String,
        default: "Cancelar",
    },
});

defineEmits(["close", "confirm"]);
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity"
    >
        <div
            class="bg-white rounded-2xl p-8 max-w-sm w-full mx-auto shadow-2xl transform transition-all"
        >
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6"
                >
                    <svg
                        class="h-10 w-10 text-red-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 mb-2">
                    {{ title }}
                </h3>
                <p class="text-sm text-gray-500 mb-8 font-medium">
                    {{ message }}
                </p>

                <div class="flex gap-3 justify-center">
                    <button
                        @click="$emit('close')"
                        class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-colors"
                    >
                        {{ cancelText }}
                    </button>
                    <button
                        @click="$emit('confirm')"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-md shadow-red-200"
                    >
                        {{ confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
