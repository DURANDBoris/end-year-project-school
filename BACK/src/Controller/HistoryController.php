<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\History;
use App\Entity\Document;
use App\Entity\User;

class HistoryController extends AbstractController
{
    // Obtenir un historique
    // #[Route('/History/{id}', name: 'getHistory', methods: ['GET'])]
    /**
     * @Route("/History/{id}", name="getHistory", methods={"GET","HEAD"})
     */
    public function getHistory(ManagerRegistry $doctrine, int $id): Response
    {
        $history = $doctrine->getRepository(History::class)->find($id);
        $Document = $doctrine->getRepository(Document::class)->find($history->getIdDocument());
        $User = $doctrine->getRepository(User::class)->find($history->getIdUser());
        return $this->json([
            'msg' => 'Obtention d un historique',
            'id' => $history->getId(),
            'action' => $history->getAction(),
            'date' => $history->getDate(),
            'idUser' => $User->getId(),
            'user' => $User->getFirstName()." ".$User->getLastName(),
            'idDocument' => $Document->getId(),
            'document' => $Document->getName()
        ]);
    }
    // Modifier un historique
    // #[Route('/History/{id}', name: 'modHistory', methods: ['PUT'])]
    /**
     * @Route("/History/{id}", name="modHistory", methods={"PUT"})
     */
    public function modHistory(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $history = $doctrine->getRepository(History::class)->find($id);
        $content = json_decode($request->getContent(), true);
        $Document = $doctrine->getRepository(Document::class)->find($content['idDocument']);
        $User = $doctrine->getRepository(User::class)->find($content['idUser']);

        if(!$history)
        {
            return "not History found for this id : " + $id;
        }
        else
        {
            $history->setAction($content['action']);
            $date = new \DateTime('@'.strtotime($content['date']));
            $history->setDate($date);
            $history->setIdDocument($Document);
            $history->setIdUser($User);
            $entityManager->persist($history);
            $entityManager->flush();

            return $this->json([
                "msg" => "L historique a bien ete modifie.",
                'id' => $history->getId(),
                'action' => $history->getAction(),
                'date' => $history->getDate(),
                'idUser' => $User->getId(),
                'user' => $User->getFirstName()." ".$User->getLastName(),
                'idDocument' => $Document->getId(),
                'document' => $Document->getName()
            ]);
        }
    }
    // Ajouter un historique
    // #[Route('/History', name: 'addHistory', methods: ['POST'])]
    /**
     * @Route("/History", name="addHistory", methods={"POST"})
     */
    public function addHistory(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $history = new History($doctrine->getRepository(History::class)->findBy(array(),array('id'=>'DESC'),1,0));
        $content = json_decode($request->getContent(), true);
        
        $Document=$doctrine->getRepository(Document::class);
        if($content['idDocument']==null)
        {$Document = $doctrine->getRepository(Document::class)->find($history->getIdDocument());}
        else{$Document = $doctrine->getRepository(Document::class)->find($content['idDocument']);}
        $User=$doctrine->getRepository(User::class);
        if($content['idUser']==null)
        {$User = $doctrine->getRepository(User::class)->find($history->getIdUser());}
        else{$User = $doctrine->getRepository(User::class)->find($content['idUser']);}
        
        $history->setAction($content['action']);
        $date = new \DateTime('@'.strtotime($content['date']));
        $history->setDate($date);
        $history->setIdDocument($Document);
        $history->setIdUser($User);
        $entityManager->persist($history);
        $entityManager->flush();

        return $this->json([
            "msg" => "L historique a bien ajoute",
            'id' => $history->getId(),
            'action' => $history->getAction(),
            'date' => $history->getDate(),
            'idUser' => $User->getId(),
            'user' => $User->getFirstName()." ".$User->getLastName(),
            'idDocument' => $Document->getId(),
            'document' => $Document->getName()
        ]);
    }
    // Supprimer un historique
    // #[Route('/History/{id}', name: 'delHistory', methods: ['DELETE'])]
    /**
     * @Route("/History/{id}", name="delHistory", methods={"DELETE"})
     */
    public function delHistory(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $history = $entityManager->getRepository(History::class)->find($id);
        $entityManager->remove($history);
        $entityManager->flush();
        return $this->json("L historique a bien ete supprime");
    }
}
