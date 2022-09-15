<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\Document;
use App\Entity\Folder;

class DocumentController extends AbstractController
{
    // Obtenir un document
    // #[Route('/Document/{id}', name: 'getDocument', methods: ['GET'])]
    /**
     * @Route("/Document/{id}", name="getDocument", methods={"GET","HEAD"})
     */
    public function getDocument(ManagerRegistry $doctrine, int $id): Response
    {
        $Document = $doctrine->getRepository(Document::class)->find($id);
        $folder = $doctrine->getRepository(Folder::class)->find($Document->getIdFolder());
        return $this->json([
            'msg' => 'Obtention d un document',
            'id' => $Document->getId(),
            'name' => $Document->getName(),
            'type' => $Document->getType(),
            'size' => $Document->getSize(),
            'path' => $Document->getPath(),
            'toIndex' => $Document->isToIndex(),
            'idFolder' => $folder->getId(),
            'folder' => $folder->getName()
        ]);
    }
    // Modifier un document
    // #[Route('/Document/{id}', name: 'modDocument', methods: ['PUT'])]
    /**
     * @Route("/Document/{id}", name="modDocument", methods={"PUT"})
     */
    public function modDocument(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $Document = $doctrine->getRepository(Document::class)->find($id);
        $content = json_decode($request->getContent(), true);
        $folder = $doctrine->getRepository(Folder::class)->find($content['idFolder']);

        if(!$Document)
        {
            return "not Document found for this id : " + $id;
        }
        else
        {
            $Document->setName($content['name']);
            $Document->setType($content['type']);
            $Document->setSize($content['size']);
            $Document->setPath($content['path']);
            $Document->setToIndex($content['toIndex']);
            $Document->setIdFolder($folder);
            $entityManager->persist($Document);
            $entityManager->flush();

            return $this->json([
                "msg" => "L document a bien ete modifie.",
                'id' => $Document->getId(),
                'name' => $Document->getName(),
                'type' => $Document->getType(),
                'size' => $Document->getSize(),
                'path' => $Document->getPath(),
                'toIndex' => $Document->isToIndex(),
                'idFolder' => $folder->getId(),
                'folder' => $folder->getName()
            ]);
        }
    }
    // Ajouter un document
    // #[Route('/Document', name: 'addDocument', methods: ['POST'])]
    /**
     * @Route("/Document", name="addDocument", methods={"POST"})
     */
    public function addDocument(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $Document = new Document($doctrine->getRepository(Document::class)->findBy(array(),array('id'=>'DESC'),1,0));
        $content = json_decode($request->getContent(), true);
        $folder = $doctrine->getRepository(Folder::class)->find($content['idFolder']);
        $Document->setName($content['name']);
        $Document->setType($content['type']);
        $Document->setSize($content['size']);
        $Document->setPath($content['path']);
        $Document->setToIndex($content['toIndex']);
        $Document->setIdFolder($folder);
        $entityManager->persist($Document);
        $entityManager->flush();

        return $this->json([
            "msg" => "L document a bien ajoute",
            'id' => $Document->getId(),
            'name' => $Document->getName(),
            'type' => $Document->getType(),
            'size' => $Document->getSize(),
            'path' => $Document->getPath(),
            'toIndex' => $Document->isToIndex(),
            'idFolder' => $folder->getId(),
            'folder' => $folder->getName()
        ]);
    }
    // Supprimer un document
    // #[Route('/Document/{id}', name: 'delDocument', methods: ['DELETE'])]
    /**
     * @Route("/Document/{id}", name="delDocument", methods={"DELETE"})
     */
    public function delDocument(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $Document = $entityManager->getRepository(Document::class)->find($id);
        $entityManager->remove($Document);
        $entityManager->flush();
        return $this->json("Le document a bien ete supprime");
    }
}
