<?php

namespace App\Notifications;

use App\Models\Dokumen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionStatusNotification extends Notification
{
    use Queueable;

    protected Dokumen $dokumen;

    protected string $status;

    protected ?string $catatan;

    public function __construct(Dokumen $dokumen, string $status, ?string $catatan = null)
    {
        $this->dokumen = $dokumen;
        $this->status = $status;
        $this->catatan = $catatan;
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
        $statusText = $this->status === 'accepted' ? 'DITERIMA' : 'DITOLAK';
        $message = "Pengumpulan dokumen {$this->dokumen->judul} telah {$statusText}.";

        $mail = (new MailMessage)
            ->line($message);

        if ($this->catatan) {
            $mail->line("Catatan: {$this->catatan}");
        }

        return $mail;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $statusEmoji = $this->status === 'accepted' ? '✅' : '❌';
        $statusText = $this->status === 'accepted' ? 'DITERIMA' : 'DITOLAK';

        $message = "{$statusEmoji} *Pengumpulan {$statusText}*\n\n";
        $message .= "Dokumen: {$this->dokumen->judul}\n\n";

        if ($this->catatan) {
            $message .= "Catatan: {$this->catatan}\n\n";
        }

        $message .= 'Silakan periksa aplikasi untuk detail lebih lanjut.';

        return [
            'phone' => $notifiable->whatsapp_number,
            'message' => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->status === 'accepted' ? 'DITERIMA' : 'DITOLAK';

        return [
            'dokumen_id' => $this->dokumen->id,
            'submission_status' => $this->status,
            'message' => "Pengumpulan dokumen {$this->dokumen->judul} telah {$statusText}.",
            'catatan' => $this->catatan,
        ];
    }
}
