<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\Folder;
use App\Entity\Organizations;

class FolderController extends AbstractController
{
    // Obtenir un repertoire
    // #[Route('/Folder/{id}', name: 'getFolder', methods: ['GET'])]
    /**
     * @Route("/Folder/{id}", name="getFolder", methods={"GET","HEAD"})
     */
    public function getFolder(ManagerRegistry $doctrine, int $id): Response
    {
        $folder = $doctrine->getRepository(Folder::class)->find($id);
        $organization = $doctrine->getRepository(Organizations::class)->find($folder->getIdOrganization());
        return $this->json([
            'msg' => 'Obtention d un repertoir',
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            'Organization Id' => $organization->getId(),
            'Organization' => $organization->getName()
        ]);
    }
    // Modifier un repertoire
    // #[Route('/Folder/{id}', name: 'modFolder', methods: ['PUT'])]
    /**
     * @Route("/Folder/{id}", name="modFolder", methods={"PUT"})
     */
    public function modFolder(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $folder = $entityManager->getRepository(Folder::class)->find($id);
        $content = json_decode($request->getContent(), true);
        $organization = $doctrine->getRepository(Organizations::class)->find($content['idOrganization']);

        if(!$folder)
        {
            return "not folder found for this id : " + $id;
        }
        else
        {
            $folder->setName($content['name']);
            $folder->setIdOrganization($organization);
            $entityManager->persist($folder);
            $entityManager->flush();

            return $this->json([
                "msg" => "Le repertoire a bien ete modifie.",
                'id' => $folder->getId(),
                'name' => $folder->getName(),
                'Organization Id' => $organization->getId(),
                'Organization' => $organization->getName()
            ]);
        }
    }
    // Ajouter un repertoire
    // #[Route('/Folder', name: 'addFolder', methods: ['POST'])]
    /**
     * @Route("/Folder/{id}", name="addFolder", methods={"POST"})
     */
    public function addFolder(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $folder = new Folder($doctrine->getRepository(Folder::class)->findBy(array(),array('id'=>'DESC'),1,0));
        $content = json_decode($request->getContent(), true);
        $organization = $doctrine->getRepository(Organizations::class)->find($content['idOrganization']);
        $folder->setName($content['name']);
        $folder->setIdOrganization($organization);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($folder);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($this->json([
            "msg" => "Le repertoire a bien ajoute",
            "id" => $folder->getId(),
            "name" => $folder->getName(),
            'Organization Id' => $organization->getId(),
            'Organization' => $organization->getName()
        ]));
    }
    // Supprimer un repertoire
    // #[Route('/Folder/{id}', name: 'delFolder', methods: ['DELETE'])]
    /**
     * @Route("/Folder/{id}", name="delFolder", methods={"DELETE"})
     */
    public function delFolder(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $folder = $entityManager->getRepository(Folder::class)->find($id);
        $entityManager->remove($folder);
        $entityManager->flush();
        return $this->json("Le repertoire a bien ete supprime");
    }
}
