<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileLoaderController extends AbstractController
{
    // #[Route('/file/loader', name: 'app_file_loader')]
    /**
     * @Route("/download/{id}", name="download", methods={"GET","HEAD"})
     */
    public function download($id): Response
    {
        $fichier = "instructions_utilisation.pdf";
        $chemin = "../container/" ;
        //header ("Content-type: application/force-download");
        // header ("Content-type: application/pdf");
        // header ("Content-disposition: filename=$fichier");
         
        readFile($chemin . $fichier);
        $response = new Response();
        $response->setContent(file_get_contents($chemin.$fichier));
        $response->headers->set('Content-Type', 'application/force-download'); //pdf'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', 'filename='. $fichier);

        
        return $response;
    }
}
