<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class FolderController extends AbstractController
{
    private $folderPath = '\var\outputs';
    private $requestStack;

    public function __construct( RequestStack $requestStack ) {
        $this->requestStack = $requestStack;
    }

    #[Route('/folder/clean', name: 'folder.clean')]
    public function index()
    {
        $pathOriginal_pdf = '../var/original_pdf/';
        $pathOutputs = '../var/outputs/';

        $filesOriginal_PDF = glob($pathOriginal_pdf . '*');
        $filesOutputs = glob($pathOutputs . '*');

        if ($filesOriginal_PDF !== false && count($filesOriginal_PDF) > 0) {

            foreach ($filesOriginal_PDF as $file) {

                if (is_file($file)) {

                    unlink($file);
                }
            }
        }

        if ($filesOutputs !== false && count($filesOutputs) > 0) {

            foreach ($filesOutputs as $file) {

                if (is_file($file)) {

                    unlink($file);
                }
            }
        }

        $referer = $this->requestStack->getCurrentRequest()->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/folder', name: 'folder.open')]
    public function openFolderAction( KernelInterface $kernel )
    {

        $os = php_uname('s');

        $command = '';

        if (strpos($os, 'Windows') !== false) {
            
            $command = 'explorer "' . $kernel->getProjectDir() . $this->folderPath . '"';

            shell_exec($command);

        } elseif (strpos($os, 'Darwin') !== false) {
            
            $command = 'open ' . $this->folderPath;

            shell_exec($command);

        } elseif (strpos($os, 'Linux') !== false) {
            
            $command = 'xdg-open ' . $this->folderPath;

            shell_exec($command);

        }

        $referer = $this->requestStack->getCurrentRequest()->headers->get('referer');

        return $this->redirect($referer);
        
    }
}
