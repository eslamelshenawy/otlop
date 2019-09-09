<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use DouglasResende\FCM\Messages\FirebaseMessage;

class MyNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {

        return (new FirebaseMessage())->setContent('Test Notification', 'This is a Test');

    }
}