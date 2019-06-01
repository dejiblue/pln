<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Maatwebsite\Excel\Facades\Excel;

class CreateCompteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataSuccess;

    public function __construct($dataSuccess)
    {
        $this->dataSuccess=$dataSuccess;
    }


    public function build()
    {
        $count = array() ; 
        $wordpress = array();

        foreach ($this->dataSuccess as $key => $temp) {
            $orgs = array() ; 
            foreach ($temp as $key => $value) {
                if ($key!=='wordpress') {
                    $orgs[$key]=$value;
                }
                else{
                   $wordpress = $value ; 
                }
            }
            foreach ($wordpress as $key => $value) {
                $orgs["- wp ".$key]=$value;
            }
            $count[]=$orgs;
        }
        $this->excelData = Excel::create('report', function($excel) use($count){
            $excel->setTitle('Cpanel Compte');
            $excel->setDescription('Cpanel Compte');
            $excel->sheet('principale', function($sheet) use($count){
                $sheet->fromArray($count);
            });
        });

        $attacheData = $this->excelData->store("csv",public_path('rapport'),true)['full'] ;
        
        return $this
            //->from('contact@whm-cpanel-tutorial.fr','PLN Manager')
            ->view('mail.CreateConte')
            ->subject('Account creation report')
            ->attach($attacheData);
    }
    
}
