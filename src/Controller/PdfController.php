<?php

namespace App\Controller;

use App\Entity\LOGO;
use App\Entity\PDF;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfController extends AbstractController
{

    #[Route('/pdf', name: 'pdf')]
    public function change_logomark( Request $request, EntityManagerInterface $em, KernelInterface $kernel )
    {

        $form = $this->createFormBuilder()
            ->add('pdf', FileType::class, ['label' => 'pdf'])
            ->add('Enviar', SubmitType::class, ['label' => 'Enviar'])
            ->getForm();

        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() ){

            $pdfFile = $form['pdf']->getData();

            $pdfDirectory = $this->getParameter('kernel.project_dir') . '/var/original_pdf/';
            $pdfFileName = md5(uniqid()) . '.' . $pdfFile->guessExtension();
            $pdfFile->move($pdfDirectory, $pdfFileName);

            $logoPath = $em->getRepository(LOGO::class)->find(1)->getPath();

            $pdf = new Fpdi();

            $pdfPath = '../var/original_pdf/' . $pdfFileName;

            $pageCount = $pdf->setSourceFile( $pdfPath );

            for( $pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++ ) {
                    
                $pdf->AddPage('L', [498, 280]);

                $templateId = $pdf->importPage($pageNumber);

                $pdf->useTemplate($templateId, null, null, 498, 280 );

                $pdf->Image($logoPath, 17, 15.3);
            }

            $outputDirectory = $kernel->getProjectDir() . '/var/outputs/';

            $outputFilePath = $outputDirectory . 'EDITED_' . $pdfFileName;

            $pdf->Output( $outputFilePath, 'F' );

            return $this->render('download/index.html.twig', [
                'pdfPath' => '../var/outputs/EDITED_' . $pdfFileName, 
            ]);
        }
     
        return $this->render('pdf/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}