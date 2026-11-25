<?php

namespace App\Mail;

use App\Models\Trip;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TripEventMail extends Mailable
{
    /** @param array<int,array{0:string,1:string}> $files [tên, nội dung] */
    public function __construct(
        public Trip $trip,
        public string $mailSubject,
        public string $headline,
        public ?string $note,
        public array $files = [],
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trip-event', with: [
            'trip' => $this->trip, 'headline' => $this->headline, 'note' => $this->note,
            'url' => route('trips.show', $this->trip),
        ]);
    }

    public function attachments(): array
    {
        return array_map(
            fn ($f) => Attachment::fromData(fn () => $f[1], $f[0])
                ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            $this->files
        );
    }
}
