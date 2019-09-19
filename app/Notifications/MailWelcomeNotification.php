<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;


class MailWelcomeNotification extends ResetPassword
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = config('app.url')."/password/reset/".$this->token;
        return ( new MailMessage )
            ->subject( 'Welcome to '.config('app.name') )
            ->line( "An account has been created for you at ".config('app.name')."'s inschrijfsysteem." )
            ->action( 'Reset Password', $link )
            ->line( "This link will expire in ".config('auth.passwords.users.expire')." minutes" )
            ->line( "You will be able to login to the system using your email adress and newly selected password." )
            ->line("You can request a new link by using the 'reset password' function on the login page.");    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
