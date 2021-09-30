<?php

declare(strict_types=1);

namespace App\Notifications\Users;

use App\Support\Queues;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private null|string $password = null)
    {
        $this->queue = Queues::mail();
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loginInfoLine = $this->password
            ? __('You may login with your email address and the password we have created for you: **:password**', ['password' => $this->password])
            : __('You may login with the email address and password you used to create your account.');

        $message = (new MailMessage)
            ->subject('New user account for ' . appName())
            ->greeting("Hello {$notifiable->name->first}!")
            ->line('A user account has been created for you at [' . url('/') . '](' . url('/') . ').')
            ->line($loginInfoLine)
            ->action(__('Login'), route('login'))
            ->salutation(false);

        if ($this->password) {
            $message->line(__('Please note: We strongly recommend changing your password after you login.'));
        }

        return $message;
    }
}
