<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
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
    // Set Carbon language
    Carbon::setLocale('en');

    // Set Malaysia timezone globally
    date_default_timezone_set('Asia/Kuala_Lumpur');


    ResetPassword::toMailUsing(function ($notifiable, $token) {

        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello!')
            ->line('We received a request to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request this, please ignore this email.')
            ->salutation('Regards, Claim and Payment Unikl MIIT (R&I Section) ');
    });
}
}
