<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    /**
     * Delivery channel
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Store in database
     */
   public function toDatabase($notifiable)
{
    return [
        'submission_id' => $this->submission->id,
        'status' => $this->submission->status,

        'message' => match ($this->submission->status) {
            'pending_admin' => 'Your application is pending admin review',
            'verified_admin' => 'Your application has been verified by Admin',
            'rejected_admin' => 'Your application has been rejected by Admin',
            'sent_to_head' => 'Your application is now under Head review',
            'approved_head' => 'Your application has been recommended by Head',
            'rejected_head' => 'Your application has been rejected by Head',
            'approved' => '🎉 Your application has been fully approved!',
            default => 'Your application status has been updated',
        },

        'type' => match ($this->submission->status) {
            'approved', 'approved_head', 'verified_admin' => 'success',
            'rejected_admin', 'rejected_head' => 'danger',
            'sent_to_head' => 'info',
            default => 'secondary',
        },
    ];
}
}
