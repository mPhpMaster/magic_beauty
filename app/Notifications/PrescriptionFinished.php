<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrescriptionFinished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $description;
    /**
     * @var \App\Models\Prescription
     */
    protected Prescription $prescription;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Prescription $prescription
     * @param                          $title
     * @param                          $description
     */
    public function __construct(Prescription $prescription, $title, $description)
    {
        //
        $this->title = $title;
        $this->description = $description;
        $this->prescription = $prescription;
        $this->prescription->sendFirebase($this->title, $this->description);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
