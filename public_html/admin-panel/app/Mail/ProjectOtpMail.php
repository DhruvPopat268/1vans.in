<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $project;
    public $client;
    public $otp;
    public $requestedBy;

    public function __construct($project, $client, $otp, $requestedBy)
    {
        $this->project = $project;
        $this->client = $client;
        $this->otp = $otp;
        $this->requestedBy = $requestedBy;
    }

    public function build()
    {
    return $this->subject("Action Required – Confirm Deletion of {$this->project->project_name} with OTP Verification")
                    ->markdown('email.project_otp');
    }

    /**
     * Get the message envelope.
     */


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
