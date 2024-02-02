<?php

namespace App\Controller;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Entity\LOGO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoController extends AbstractController
{

    #[Route('/logo', name: 'logo')]
    public function index( EntityManagerInterface $em, Request $request ): Response
    {

        $DB_LOGO = new LOGO();

        $form = $this->createFormBuilder()
        ->add('logo', FileType::class, ['label' => 'logo'])
        ->add('Enviar', SubmitType::class, ['label' => 'Enviar'])
        ->getForm();

        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() ){

            $logoFile = $form['logo']->getData();
            
            $logos = $em->getRepository( LOGO::class )->findAll();

            if( count($logos) >= 1 ){
                $this->truncateTable( $em, LOGO::class );
            }

            $this->clearFolder( '../var/new_logo/' );

            $logoDirectory = $this->getParameter('kernel.project_dir') . './var/new_logo';
            $logoFileName = md5(uniqid()) . '.' . $logoFile->guessExtension();
            $logoFile->move($logoDirectory, $logoFileName);

            $logoPath = '../var/new_logo/' . $logoFileName;
            
            // $manager = new ImageManager( new Driver() );
            
            // $image = $manager->read($logoPath);

            // $image->resize(174, 180);

            // $newLogoPath = '../var/new_logo/' . 'RESIZED' . $logoFileName;

            // $image->save( $newLogoPath );

            // unlink($logoPath);

            // $DB_LOGO->setPath( $newLogoPath );
            $DB_LOGO->setPath( $logoPath );

            $em->persist($DB_LOGO);
            $em->flush();

            return $this->redirectToRoute('pdf');
        }

        return $this->render('logo/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function truncateTable( EntityManagerInterface $em, string $entityClass )
    {
        $conn = $em->getConnection();
        $platform = $conn->getDatabasePlatform();
        $conn->executeStatement( $platform->getTruncateTableSQL(
            $em->getClassMetadata( $entityClass )->getTableName(),
            true
        ));
    }

    private function clearFolder( $folderPath )
    {        
        $files = glob($folderPath . '*');

        if ($files !== false && count($files) > 0) {

            foreach ($files as $file) {

                if (is_file($file)) {

                    unlink($file);
                }
            }
        }
    }
}
