<?php

namespace App\Mail;

use App\Models\Score;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScoreNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Score $score;

    /**
     * Create a new message instance.
     */
    public function __construct(Score $score)
    {
        $this->score = $score;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Score Has Been Published - ' . $this->score->submission->registration->competition->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.score-notification',
            with: [
                'score' => $this->score,
                'submission' => $this->score->submission,
                'registration' => $this->score->submission->registration,
                'user' => $this->score->submission->registration->user,
                'competition' => $this->score->submission->registration->competition,
            ],
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
