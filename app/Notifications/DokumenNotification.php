<?php

namespace App\Notifications;

use App\Models\Dokumen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DokumenNotification extends Notification
{
    use Queueable;

    protected string $type;

    protected Dokumen $dokumen;

    public function __construct(Dokumen $dokumen, string $type)
    {
        $this->dokumen = $dokumen;
        $this->type = $type;
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
        $message = match ($this->type) {
            'created' => "Dokumen baru telah dibuat: {$this->dokumen->judul}",
            'updated' => "Dokumen telah diupdate: {$this->dokumen->judul}",
            'deleted' => "Dokumen telah dihapus: {$this->dokumen->judul}",
            default => "Ada perubahan pada dokumen: {$this->dokumen->judul}",
        };

        return (new MailMessage)
            ->line($message)
            ->action('Lihat Detail', url('/dokumens'));
    }

    public function toWhatsApp(object $notifiable): array
    {
        $message = match ($this->type) {
            'created' => "📄 *Assalamualaikum Bapak/Ibu, semoga selalu berada dalam lindungan Allah SWT, Terdapat Dokumen Baru*\n\nJudul: {$this->dokumen->judul}\n\nDeadline: {$this->dokumen->tanggal_deadline->format('d/m/Y H:i')}\n\nSilakan login ke aplikasi untuk men-submit dokumen Anda. Terima kasih.",
            'updated' => "📝 *Assalamualaikum Bapak/Ibu, semoga selalu berada dalam lindungan Allah SWT, Terdapat Perubahan Dokumen*\n\nJudul: {$this->dokumen->judul}\n\nDeadline: {$this->dokumen->tanggal_deadline->format('d/m/Y H:i')}\n\nSilakan periksa dokumen terkait. Terima kasih.",
            'deleted' => "🗑️ *Assalamualaikum Bapak/Ibu, semoga selalu berada dalam lindungan Allah SWT, Terdapat Perubahan Dokumen*\n\nDokumen '{$this->dokumen->judul}' telah dihapus oleh admin. Terima kasih.",
            default => "Assalamualaikum Bapak/Ibu, semoga selalu berada dalam lindungan Allah SWT, Ada perubahan pada dokumen: {$this->dokumen->judul}",
        };

        return [
            'phone' => $notifiable->whatsapp_number,
            'message' => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        $message = match ($this->type) {
            'created' => "Admin membuat dokumen baru: {$this->dokumen->judul}",
            'updated' => "Admin mengupdate dokumen: {$this->dokumen->judul}",
            'deleted' => "Admin menghapus dokumen: {$this->dokumen->judul}",
            default => "Ada perubahan pada dokumen: {$this->dokumen->judul}",
        };

        return [
            'dokumen_id' => $this->dokumen->id,
            'type' => $this->type,
            'message' => $message,
        ];
    }
}
