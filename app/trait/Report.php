<?php

namespace app\trait;

use Dompdf\Dompdf;
use Dompdf\Options;

use Psr\Http\Message\ResponseInterface as Response;

trait Report
{
    public function printer(string $html, string $filename = 'documento.pdf'): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('debugKeepTemp', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', realpath(__DIR__ . '/../public'));
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $pdfOutput);
        rewind($stream);
        $body = new \Slim\Psr7\Stream($stream);
        $response = new \Slim\Psr7\Response(200);
        $response = $response->withHeader(
            'Content-Type',
            'application/pdf'
        );
        $response = $response->withHeader(
            'Content-Disposition',
            'inline; filename="' . basename($filename) . '"'
        );
        $response = $response->withHeader(
            'Cache-Control',
            'private, max-age=0, must-revalidate'
        );
        $response = $response->withHeader('Pragma', 'public');
        $response = $response->withBody($body);
        return $response;
    }
}