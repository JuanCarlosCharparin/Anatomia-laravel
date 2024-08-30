<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstudioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfPath;

    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->view('estudios.email_estudio') 
                    ->attach($this->pdfPath, [
                        'as' => 'informe.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->subject('Informe de Anatomía Patológica HU.');
    }
}
