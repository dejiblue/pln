<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorReportingsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataError;

    public function __construct($dataError)
    {
        $this->dataError=$dataError;
    }

    public function build()
    {
        return $this
            //->from('contact@whm-cpanel-tutorial.fr','PLN Manager')
            ->view('mail.ErrorReportings');
    }
}
