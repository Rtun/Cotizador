<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    protected $rutaArchivo;
    public $nombreArchivo;
    /**
     * Create a new message instance.
     */
    public function __construct($mail, $rutaArchivo, $nombreArchivo)
    {
        $this->mail = $mail;
        $this->rutaArchivo = $rutaArchivo; // La ruta del archivo adjunto
        $this->nombreArchivo = $nombreArchivo;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $usuario = usuario();
        return $this->view('emails.cotizaciones')
                    ->from($usuario->email, $usuario->name)
                    ->subject('Cotizacion')
                    ->attach($this->rutaArchivo, [
                        'as' => $this->nombreArchivo.'.xlsx', // El nombre del archivo adjunto
                        'withMime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
