<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {

        $valoracionMedia = Viaje::whereNotNull('valoracion')->avg('valoracion');

        $stats = [
            'total_usuarios' => User::count(),
            'total_viajes' => Viaje::count(),
            'viajes_modo_pro' => Viaje::where('modo_pro', true)->count(),
            'valoracion_media' => $valoracionMedia ? round($valoracionMedia, 1) : 'N/A',
            'total_viajes_valorados' => Viaje::whereNotNull('valoracion')->count(),
        ];

        //gráfica de viajes por mes (últimos 6 meses)
        $viajesPorMes = Viaje::selectRaw('COUNT(id) as cantidad, MONTH(created_at) as mes_num')
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('mes_num', 'asc')
            ->take(6)
            ->get()
            ->map(function ($item) {
                $nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                return [
                    'mes' => $nombresMeses[$item->mes_num - 1] ?? 'Desconocido',
                    'cantidad' => $item->cantidad
                ];
            });

        $usuarios = User::withCount('viajes')->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'viajes' => Viaje::with('user')->latest()->take(5)->get(),
            'usuarios' => $usuarios,
            'chartData' => $viajesPorMes
        ]);
    }
    public function usuarioViajes(User $user)
    {
        //obtenemos los viajes de un determinado usuario
        $viajes = $user->viajes()->latest()->get();

        return Inertia::render('Admin/UsuarioViajes', [
            'usuario' => $user,
            'viajes' => $viajes
        ]);
    }
    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde el panel de administración.');
        }

        if ($user->id === 1) {
            return back()->with('error', 'La cuenta del administrador principal no puede ser eliminada.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function destroyViaje(Viaje $viaje)
    {
        $viaje->delete();
        return back();
    }

    public function cambiarRol(Request $request, User $user)
    {
        // evitar que un admin se quite el rol a sí mismo
        if (auth()->id() === $user->id) {
            return back()->with('error', 'No puedes modificar tus propios permisos.');
        }

        if ($user->id === 1) {
            return back()->with('error', 'El administrador principal no puede ser modificado.');
        }

        if ($user->role_id === 1) {
            $request->validate([
                'password' => ['required', 'current_password'],
            ], [
                'password.required' => 'La contraseña es obligatoria.',
                'password.current_password' => 'La contraseña es incorrecta.'
            ]);

            $user->update(['role_id' => 2]);
            return back()->with('success', 'Permisos de administrador retirados.');
        }
        else {
            $user->update(['role_id' => 1]);
            return back()->with('success', 'Usuario promovido a administrador.');
        }
    }
}
