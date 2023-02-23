<?php


namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfCreator
{
    private $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function generatePdf($html){
        $this->dompdf->loadHtml($html);

        $this->dompdf->render();
        return $this->dompdf->stream("pdf.pdf", [
            "Attachment" => false
        ]);
    }

}