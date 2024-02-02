<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    #[Route('/download', name: 'download')]
    public function index( Request $request ): Response
    {
        $pdfFilePath = $request->query->get('pdfFilePath');

        if ($pdfFilePath) {
            return $this->downloadPdf( $pdfFilePath );
        }

        return $this->render('download/index.html.twig');
    }

    private function downloadPdf($pdfFilePath)
    {
        if (!file_exists($pdfFilePath)) {
            throw $this->createNotFoundException('O arquivo PDF não foi encontrado.');
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($pdfFilePath) . '"');
        $response->setContent(file_get_contents($pdfFilePath));

        return $response;
    }
}
