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
        $stats = [
            'total_usuarios' => User::count(),
            'total_viajes' => Viaje::count(),
            'viajes_modo_pro' => Viaje::where('modo_pro', true)->count(),
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
        //un admin no puede autoeliminarse
        if (auth()->id() === $user->id) {
            return back();
        }

        $user->delete();
        return back(); //recarga la página
    }

    public function destroyViaje(Viaje $viaje)
    {
        $viaje->delete();
        return back();
    }
}
