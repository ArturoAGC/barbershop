<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ReservaConfirmada extends Mailable
{
    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tu reserva fue confirmada — BarberBook');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.reserva-confirmada');
    }
}