<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\Rule;
use App\Entity\Folder;

class RuleController extends AbstractController
{
    // Obtenir une Regle
    // #[Route('/Rule/{id}', name: 'getRule', methods: ['GET'])]
    /**
     * @Route("/Rule/{id}", name="getRule", methods={"GET","HEAD"})
     */
    public function getRule(ManagerRegistry $doctrine, int $id): Response
    {
        $Rule = $doctrine->getRepository(Rule::class)->find($id);
        $folder = $doctrine->getRepository(Folder::class)->find($Rule->getIdFolder());
        return $this->json([
            'msg' => 'Obtention d une regle',
            'id' => $Rule->getId(),
            'name' => $Rule->getName(),
            'type' => $Rule->getType(),
            'isMandatory' => $Rule->isMandatory(),
            'idFolder' => $folder->getId(),
            'folder' => $folder->getName()
        ]);
    }
    // Modifier une Regle
    // #[Route('/Rule/{id}', name: 'modRule', methods: ['PUT'])]
    /**
     * @Route("/Rule/{id}", name="modRule", methods={"PUT"})
     */
    public function modRule(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $Rule = $doctrine->getRepository(Rule::class)->find($id);
        $content = json_decode($request->getContent(), true);
        $folder = $doctrine->getRepository(Folder::class)->find($content['idFolder']);

        if(!$Rule)
        {
            return "not Rule found for this id : " + $id;
        }
        else
        {
            $Rule->setName($content['name']);
            $Rule->setType($content['type']);
            $Rule->setMandatory($content['isMandatory']);
            $Rule->setIdFolder($folder);
            $entityManager->persist($Rule);
            $entityManager->flush();

            return $this->json([
                "msg" => "La Regle a bien ete modifiee.",
                'id' => $Rule->getId(),
                'name' => $Rule->getName(),
                'type' => $Rule->getType(),
                'isMandatory' => $Rule->isMandatory(),
                'idFolder' => $folder->getId(),
                'folder' => $folder->getName()
            ]);
        }
    }
    // Ajouter une Regle
    // #[Route('/Rule', name: 'addRule', methods: ['POST'])]
    /**
     * @Route("/Rule", name="addRule", methods={"POST"})
     */
    public function addRule(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $Rule = new Rule($doctrine->getRepository(Rule::class)->findBy(array(),array('id'=>'DESC'),1,0));
        $content = json_decode($request->getContent(), true);

        $folder = $doctrine->getRepository(Folder::class);
        if($content['idFolder'] == null)
        {
            $folder = $doctrine->getRepository(Folder::class)->find($Rule->getIdFolder());
        }
        else{$folder = $doctrine->getRepository(Folder::class)->find($content['idFolder']);}
        
        $Rule->setName($content['name']);
        $Rule->setType($content['type']);
        $Rule->setMandatory($content['isMandatory']);
        $Rule->setIdFolder($folder);
        $entityManager->persist($Rule);
        $entityManager->flush();

        return $this->json([
            "msg" => "La Regle a bien ajoutee",
            'id' => $Rule->getId(),
            'name' => $Rule->getName(),
            'type' => $Rule->getType(),
            'isMandatory' => $Rule->isMandatory(),
            'idFolder' => $folder->getId(),
            'folder' => $folder->getName()
        ]);
    }
    // Supprimer une Regle
    // #[Route('/Rule/{id}', name: 'delRule', methods: ['DELETE'])]
    /**
     * @Route("/Rule/{id}", name="delRule", methods={"DELETE"})
     */
    public function delRule(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $Rule = $entityManager->getRepository(Rule::class)->find($id);
        $entityManager->remove($Rule);
        $entityManager->flush();
        return $this->json("La regle a bien ete supprimee");
    }
}
