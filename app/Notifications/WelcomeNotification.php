<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class WelcomeNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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

       if($this->user->active.""=="1"){
        return (new MailMessage)
            ->greeting(__('Hello ').$this->user->name)
            ->subject(__('Thanks for registeriing on ').env('APP_NAME',""))
            ->action(__('Visit')." ".env('APP_NAME',""), url(env('APP_URL',"")))
            ->line(__('We are happy to have you onboard.'));;
       }else{
        return (new MailMessage)
            ->greeting(__('Hello ').$this->user->name)
            ->subject(__('Thanks for registeriing on ').env('APP_NAME',""))
            ->action(__('Visit')." ".env('APP_NAME',""), url(env('APP_URL',"")))
            ->line(__('Soon as admin approves your account, we will let you know.'));;
       }
        
    }

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
