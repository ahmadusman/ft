<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Restorant;

class RestaurantCreated extends Notification
{
    use Queueable;

    protected $password;
    protected $restaurant;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password,$restaurant,$user)
    {
        $this->password = $password;
        $this->restaurant = $restaurant;
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
        return (new MailMessage)
                    ->greeting(__('Hello ').$this->user->name)
                    ->subject(__('Account created in ').env('APP_NAME',""))
                    ->line(__('We have create a restaurant owner account for')." ".$this->restaurant->name)
                    ->action('Login', url(env('APP_URL',"")."/login"))
                    ->line(__('Username').": ".$this->user->email)
                    ->line(__('Password').": ".$this->password)
                    ->line(__('You can reset your initial password:'))
                    ->line(__('Thank you for using our service!'));
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
