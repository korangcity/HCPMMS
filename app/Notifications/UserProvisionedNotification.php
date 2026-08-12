<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class UserProvisionedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct()
    {
        $this->onQueue('notifications');
    }

    /**
     * @return array<int,string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('حساب کاربری سامانه پایش بیماران')
            ->greeting("سلام {$notifiable->name}")
            ->line('حساب کاربری شما در سامانه ایجاد شد.')
            ->line('برای ورود از ایمیل ثبت‌شده خود استفاده کنید.')
            ->action(
                'ورود به سامانه',
                url('/login')
            )
            ->line('در صورت عدم درخواست این حساب، با مدیر سامانه تماس بگیرید.');
    }
}
