<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu cuenta en Viaja')
                ->greeting('¡Hola!')
                ->line('Gracias por registrarte. Para empezar a crear tus itinerarios con IA, haz clic en el botón de abajo para verificar tu correo electrónico.')
                ->action('Verificar mi cuenta', $url)
                ->line('Si no creaste esta cuenta, no es necesario realizar ninguna acción.')
                ->salutation('Un saludo, el equipo de Viaja');
        });
    }
}
