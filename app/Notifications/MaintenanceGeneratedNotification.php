<?php

namespace App\Notifications;

use App\Models\MaintenanceBill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bill;

    public function __construct(MaintenanceBill $bill)
    {
        $this->bill = $bill;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Maintenance Bill Generated - {$this->bill->month} {$this->bill->year}")
            ->line("Hello {$notifiable->name},")
            ->line("Your maintenance bill for {$this->bill->month} {$this->bill->year} has been generated.")
            ->line("Amount: " . number_format($this->bill->total_amount, 2))
            ->line("Due Date: " . $this->bill->due_date->format('d-m-Y'))
            ->action('View Bill', url('/user/maintenance'))
            ->line('Thank you for being a part of our society!');
    }

    public function toArray($notifiable): array
    {
        return [
            'bill_id' => $this->bill->id,
            'title' => $this->bill->title,
            'amount' => $this->bill->total_amount,
            'month' => $this->bill->month,
            'year' => $this->bill->year,
            'message' => "Maintenance bill for {$this->bill->month} generated."
        ];
    }
}
