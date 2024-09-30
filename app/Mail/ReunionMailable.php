<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReunionMailable extends Mailable
{
    use Queueable, SerializesModels;

    //Alamcenamos los datos a enviar
    Public $datosReunion;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($datosReunion, $subject)
    {
        $this->datosReunion = $datosReunion;
        $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $usuario = usuario();
        return new Envelope(
            from: new Address($usuario->email, $usuario->name),
            // from: 'ivan.huchin@comsitec.com.mx',
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizaciones',
            with: ['context' => $this->datosReunion]
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
