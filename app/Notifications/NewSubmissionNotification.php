<?php

namespace App\Notifications;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubmissionNotification extends Notification
{
    use Queueable;

    protected Dokumen $dokumen;

    protected User $dosen;

    public function __construct(Dokumen $dokumen, User $dosen)
    {
        $this->dokumen = $dokumen;
        $this->dosen = $dosen;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->no_telepon) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("{$this->dosen->name} telah mengumpulkan dokumen: {$this->dokumen->judul}")
            ->action('Lihat Pengumpulan', url('/admin/dokumens/'.$this->dokumen->id.'/submissions'));
    }

    public function toWhatsApp(object $notifiable): array
    {
        $message = "📋 *Pengumpulan Baru*\n\n";
        $message .= "Dosen: {$this->dosen->name}\n";
        $message .= "Dokumen: {$this->dokumen->judul}\n";
        $message .= 'Waktu: '.now()->format('d/m/Y H:i')."\n\n";
        $message .= 'Silakan periksa di aplikasi.';

        return [
            'phone' => $notifiable->no_telepon,
            'message' => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'dokumen_id' => $this->dokumen->id,
            'dosen_id' => $this->dosen->id,
            'dosen_name' => $this->dosen->name,
            'message' => "{$this->dosen->name} telah submit dokumen: {$this->dokumen->judul}",
        ];
    }
}
