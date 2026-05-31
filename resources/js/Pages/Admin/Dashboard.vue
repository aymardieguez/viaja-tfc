<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { ref, computed } from "vue";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

const props = defineProps({
    stats: Object,
    usuarios: Array,
    chartData: Array,
});

const page = usePage();
const authUserId = computed(() => page.props.auth.user.id);

// Configuración de la gráfica de barras
const dataGrafica = {
    labels: props.chartData.map((d) => d.mes),
    datasets: [
        {
            label: "Viajes Generados",
            data: props.chartData.map((d) => d.cantidad),
            backgroundColor: "#8b5cf6",
        },
    ],
};

//vista móvil para mostrar detalles adicionales al hacer click en un usuario
const usuarioExpandido = ref(null);

const toggleDetallesUsuario = (id) => {
    if (usuarioExpandido.value === id) {
        usuarioExpandido.value = null;
    } else {
        usuarioExpandido.value = id;
    }
};

const mostrarModalUsuario = ref(false);
const usuarioIdSeleccionado = ref(null);

const mostrarModalPassword = ref(false);
const usuarioRolSeleccionado = ref(null);
const formRol = useForm({ password: "" });

// Alertas informativas
const mostrarModalAlerta = ref(false);
const mensajeAlerta = ref("");

const confirmarBorradoUsuario = (user) => {
    if (user.id === authUserId.value) {
        mensajeAlerta.value =
            "No puedes eliminar tu propia cuenta desde el panel de administración. Ve a tu perfil si deseas darte de baja.";
        mostrarModalAlerta.value = true;
        return;
    }

    if (user.id === 1) {
        mensajeAlerta.value =
            "La cuenta del administrador principal del sistema no puede ser eliminada.";
        mostrarModalAlerta.value = true;
        return;
    }

    usuarioIdSeleccionado.value = user.id;
    mostrarModalUsuario.value = true;
};

const cancelarBorradoUsuario = () => {
    mostrarModalUsuario.value = false;
    usuarioIdSeleccionado.value = null;
};

const ejecutarBorradoUsuario = () => {
    if (usuarioIdSeleccionado.value) {
        router.delete(
            route("admin.usuarios.destroy", usuarioIdSeleccionado.value),
            {
                preserveScroll: true,
                onSuccess: () => {
                    mostrarModalUsuario.value = false;
                    usuarioIdSeleccionado.value = null;
                },
            },
        );
    }
};

const intentarCambiarRol = (user) => {
    if (user.id === authUserId.value) {
        mensajeAlerta.value =
            "No puedes modificar tus propios permisos de administrador por motivos de seguridad.";
        mostrarModalAlerta.value = true;
        return;
    }

    if (user.id === 1) {
        mensajeAlerta.value =
            "Los privilegios del administrador principal no pueden ser modificados.";
        mostrarModalAlerta.value = true;
        return;
    }

    if (user.role_id === 1) {
        usuarioRolSeleccionado.value = user;
        formRol.clearErrors();
        formRol.password = "";
        mostrarModalPassword.value = true;
    } else {
        formRol.patch(route("admin.usuarios.cambiarRol", user.id), {
            preserveScroll: true,
        });
    }
};

const confirmarDegradacion = () => {
    formRol.patch(
        route("admin.usuarios.cambiarRol", usuarioRolSeleccionado.value.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                mostrarModalPassword.value = false;
                formRol.reset();
            },
        },
    );
};

const cancelarDegradacion = () => {
    mostrarModalPassword.value = false;
    formRol.reset();
    formRol.clearErrors();
};

const cerrarAlerta = () => {
    mostrarModalAlerta.value = false;
    mensajeAlerta.value = "";
};
</script>

<template>
    <Head title="Administración" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-purple-800">
                Panel de Control
            </h2>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div
                        class="bg-white p-4 sm:p-6 rounded-lg shadow border-l-4 border-blue-500 flex flex-col justify-center"
                    >
                        <p
                            class="text-gray-500 text-[10px] sm:text-sm font-bold uppercase truncate"
                        >
                            Usuarios
                        </p>
                        <p class="text-2xl sm:text-3xl font-black">
                            {{ stats.total_usuarios }}
                        </p>
                    </div>

                    <div
                        class="bg-white p-4 sm:p-6 rounded-lg shadow border-l-4 border-purple-500 flex flex-col justify-center"
                    >
                        <p
                            class="text-gray-500 text-[10px] sm:text-sm font-bold uppercase truncate"
                        >
                            Itinerarios
                        </p>
                        <p class="text-2xl sm:text-3xl font-black">
                            {{ stats.total_viajes }}
                        </p>
                    </div>

                    <div
                        class="bg-white p-4 sm:p-6 rounded-lg shadow border-l-4 border-yellow-400 flex flex-col justify-center overflow-hidden"
                    >
                        <p
                            class="text-gray-500 text-[10px] sm:text-sm font-bold uppercase truncate"
                        >
                            Valoración Media
                        </p>
                        <div class="flex items-end gap-1 sm:gap-2">
                            <p
                                class="text-2xl sm:text-3xl font-black text-gray-900"
                            >
                                {{
                                    stats.valoracion_media
                                        ? stats.valoracion_media
                                        : "-"
                                }}
                            </p>
                            <span
                                class="text-yellow-400 text-lg sm:text-2xl mb-0.5 sm:mb-1 pb-0.5"
                                >⭐</span
                            >
                        </div>
                        <p
                            class="text-[9px] sm:text-xs text-gray-400 font-medium mt-1 truncate"
                        >
                            Basado en {{ stats.total_viajes_valorados }} reseñas
                        </p>
                    </div>

                    <div
                        class="bg-white p-4 sm:p-6 rounded-lg shadow border-l-4 border-green-500 flex flex-col justify-center"
                    >
                        <p
                            class="text-gray-500 text-[10px] sm:text-sm font-bold uppercase truncate"
                        >
                            Tasa Modo Pro
                        </p>
                        <p class="text-2xl sm:text-3xl font-black">
                            {{
                                stats.total_viajes > 0
                                    ? (
                                          (stats.viajes_modo_pro /
                                              stats.total_viajes) *
                                          100
                                      ).toFixed(1)
                                    : 0
                            }}%
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-bold text-gray-700 mb-4">
                            Crecimiento de Itinerarios
                        </h3>
                        <Bar :data="dataGrafica" />
                    </div>

                    <div
                        class="bg-white p-6 rounded-lg shadow overflow-hidden w-full"
                    >
                        <h3 class="font-bold text-gray-700 mb-4">
                            Gestión de Usuarios
                        </h3>

                        <div
                            v-if="$page.props.flash?.error"
                            class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm"
                        >
                            {{ $page.props.flash.error }}
                        </div>
                        <div
                            v-if="$page.props.flash?.success"
                            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm"
                        >
                            {{ $page.props.flash.success }}
                        </div>

                        <div class="w-full">
                            <div
                                class="block sm:hidden divide-y divide-gray-100 border-t border-gray-100"
                            >
                                <div
                                    v-for="user in usuarios"
                                    :key="'mob-user-' + user.id"
                                    class="bg-white"
                                >
                                    <button
                                        @click="toggleDetallesUsuario(user.id)"
                                        class="w-full text-left px-4 py-4 flex justify-between items-center focus:outline-none transition-colors hover:bg-gray-50"
                                    >
                                        <div
                                            class="flex flex-col truncate pr-4"
                                        >
                                            <span
                                                class="font-bold text-gray-900 truncate"
                                                >{{ user.name }}</span
                                            >
                                            <span
                                                class="text-xs text-gray-400 truncate"
                                                >{{ user.email }}</span
                                            >
                                        </div>
                                        <svg
                                            :class="{
                                                'rotate-180':
                                                    usuarioExpandido ===
                                                    user.id,
                                            }"
                                            class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </button>

                                    <div
                                        v-show="usuarioExpandido === user.id"
                                        class="px-4 pb-5 pt-1 bg-gray-50"
                                    >
                                        <div
                                            class="space-y-3 text-sm text-gray-600 mb-5 border-t border-gray-200 pt-4"
                                        >
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="font-bold text-gray-700"
                                                    >Rol:</span
                                                >
                                                <span
                                                    v-if="user.role_id === 1"
                                                    class="px-2 py-1 text-[10px] font-bold text-green-700 bg-green-100 rounded-full uppercase tracking-wide"
                                                    >Admin</span
                                                >
                                                <span
                                                    v-else
                                                    class="px-2 py-1 text-[10px] font-bold text-gray-700 bg-gray-200 rounded-full uppercase tracking-wide"
                                                    >Usuario</span
                                                >
                                            </div>
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="font-bold text-gray-700"
                                                    >Itinerarios:</span
                                                >
                                                <span
                                                    class="font-bold text-gray-900"
                                                    >{{
                                                        user.viajes_count
                                                    }}</span
                                                >
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2">
                                            <button
                                                v-if="user.id !== 1"
                                                @click="
                                                    intentarCambiarRol(user)
                                                "
                                                type="button"
                                                :class="
                                                    user.role_id === 1
                                                        ? 'bg-orange-50 text-orange-600 hover:bg-orange-100 border-orange-100'
                                                        : 'bg-purple-50 text-purple-600 hover:bg-purple-100 border-purple-100'
                                                "
                                                class="w-full font-bold py-2.5 rounded-lg text-xs transition-colors border shadow-sm"
                                            >
                                                {{
                                                    user.role_id === 1
                                                        ? "Quitar Administrador"
                                                        : "Hacer Administrador"
                                                }}
                                            </button>

                                            <Link
                                                :href="
                                                    route(
                                                        'admin.usuarios.viajes',
                                                        user.id,
                                                    )
                                                "
                                                class="w-full text-center bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-100 font-bold py-2.5 rounded-lg text-xs transition-colors shadow-sm"
                                            >
                                                Ver Viajes
                                            </Link>

                                            <button
                                                v-if="user.id !== 1"
                                                @click="
                                                    confirmarBorradoUsuario(
                                                        user,
                                                    )
                                                "
                                                type="button"
                                                class="w-full bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 font-bold py-2.5 rounded-lg text-xs transition-colors shadow-sm"
                                            >
                                                Eliminar Usuario
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="usuarios.length === 0"
                                    class="px-6 py-10 text-center text-gray-500 text-sm"
                                >
                                    No hay usuarios en la plataforma.
                                </div>
                            </div>

                            <div class="hidden sm:block overflow-x-auto w-full">
                                <table
                                    class="min-w-full text-sm whitespace-nowrap"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left">
                                                Nombre
                                            </th>
                                            <th class="px-4 py-2 text-left">
                                                Rol
                                            </th>
                                            <th class="px-4 py-2 text-center">
                                                Viajes
                                            </th>
                                            <th class="px-4 py-2 text-center">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="user in usuarios"
                                            :key="user.id"
                                            class="border-t hover:bg-gray-50 transition-colors"
                                        >
                                            <td class="px-4 py-3">
                                                <p
                                                    class="font-bold text-gray-900"
                                                >
                                                    {{ user.name }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{ user.email }}
                                                </p>
                                            </td>

                                            <td class="px-4 py-3">
                                                <span
                                                    v-if="user.role_id === 1"
                                                    class="px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full"
                                                >
                                                    Admin
                                                </span>
                                                <span
                                                    v-else
                                                    class="px-2 py-1 text-xs font-bold text-gray-700 bg-gray-100 rounded-full"
                                                >
                                                    Usuario
                                                </span>
                                            </td>

                                            <td
                                                class="px-4 py-3 text-center font-medium"
                                            >
                                                {{ user.viajes_count }}
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <div
                                                    v-if="user.id !== 1"
                                                    class="flex items-center justify-center space-x-4"
                                                >
                                                    <button
                                                        @click="
                                                            intentarCambiarRol(
                                                                user,
                                                            )
                                                        "
                                                        type="button"
                                                        :class="
                                                            user.role_id === 1
                                                                ? 'text-orange-500 hover:text-orange-700'
                                                                : 'text-purple-600 hover:text-purple-900'
                                                        "
                                                        class="font-bold text-xs transition-colors"
                                                    >
                                                        {{
                                                            user.role_id === 1
                                                                ? "Quitar Admin"
                                                                : "Hacer Admin"
                                                        }}
                                                    </button>

                                                    <Link
                                                        :href="
                                                            route(
                                                                'admin.usuarios.viajes',
                                                                user.id,
                                                            )
                                                        "
                                                        class="text-blue-500 hover:text-blue-800 font-bold text-xs transition-colors"
                                                    >
                                                        Ver Viajes
                                                    </Link>

                                                    <button
                                                        @click="
                                                            confirmarBorradoUsuario(
                                                                user,
                                                            )
                                                        "
                                                        type="button"
                                                        class="text-red-500 hover:text-red-800 text-xs font-bold transition-colors"
                                                    >
                                                        Eliminar
                                                    </button>
                                                </div>
                                                <span
                                                    v-else
                                                    class="text-gray-300 font-bold"
                                                    >-</span
                                                >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="mostrarModalUsuario"
            title="¿Borrar usuario?"
            message="Se borrará permanentemente este usuario y TODOS sus viajes asociados. Esta acción no se puede deshacer."
            confirmText="Sí, borrar usuario"
            @close="cancelarBorradoUsuario"
            @confirm="ejecutarBorradoUsuario"
        />

        <ConfirmModal
            :show="mostrarModalPassword"
            title="Confirmación de Seguridad"
            confirmText="Quitar Privilegios"
            confirmColor="bg-orange-600 hover:bg-orange-700 shadow-orange-200"
            :processing="formRol.processing"
            @close="cancelarDegradacion"
            @confirm="confirmarDegradacion"
        >
            <template #icon>
                <div
                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-6"
                >
                    <span class="text-orange-600 text-2xl">🔒</span>
                </div>
            </template>

            <p class="text-sm text-gray-500 mb-5 font-medium">
                Para quitarle los permisos de administrador a
                <span class="font-bold text-gray-800">{{
                    usuarioRolSeleccionado?.name
                }}</span
                >, introduce tu contraseña actual.
            </p>

            <input
                v-model="formRol.password"
                type="password"
                placeholder="Tu contraseña de administrador"
                class="w-full mb-1 px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300"
                @keyup.enter="confirmarDegradacion"
            />
            <p
                v-if="formRol.errors.password"
                class="text-red-500 text-xs mb-2 text-left font-bold"
            >
                {{ formRol.errors.password }}
            </p>
        </ConfirmModal>

        <ConfirmModal
            :show="mostrarModalAlerta"
            title="Acción no permitida"
            :message="mensajeAlerta"
            confirmText="Entendido"
            confirmColor="bg-gray-800 hover:bg-gray-900 shadow-gray-200"
            iconColor="text-gray-600 bg-gray-100"
            @close="cerrarAlerta"
            @confirm="cerrarAlerta"
        >
            <template #icon>
                <div
                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-6"
                >
                    <svg
                        class="h-8 w-8 text-gray-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
            </template>
        </ConfirmModal>
    </AuthenticatedLayout>
</template>
