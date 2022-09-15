<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\Indices;
use App\Entity\Rule;
use App\Entity\Document;

class IndicesController extends AbstractController
{
    // Obtenir un indice
    // #[Route('/Indices/{id}', name: 'getIndices', methods: ['GET'])]
    /**
     * @Route("/Indices/{id}", name="getIndices", methods={"GET","HEAD"})
     */
    public function getIndices(ManagerRegistry $doctrine, int $id): Response
    {
        $indices = $doctrine->getRepository(Indices::class)->find($id);
        $Rule = $doctrine->getRepository(Rule::class)->find($indices->getIdRule());
        $Document = $doctrine->getRepository(Document::class)->find($indices->getIdDocument());
        return $this->json([
            'msg' => 'Obtention d un indice',
            'id' => $indices->getId(),
            'value' => $indices->getValue(),
            'Document' => $Document->getName(),
            'idDocument' => $Document->getId(),
            'Rule' => $Rule->getName(),
            'idRule' => $Rule->getId()
        ]);
    }
    // Modifier un indice
    // #[Route('/Indices/{id}', name: 'modIndices', methods: ['PUT'])]
    /**
     * @Route("/Indices/{id}", name="modIndices", methods={"PUT"})
     */
    public function modIndices(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $indices = $doctrine->getRepository(Indices::class)->find($id);
        $content = json_decode($request->getContent(), true);
        $Rule = $doctrine->getRepository(Rule::class)->find($content['idRule']);
        $Document = $doctrine->getRepository(Document::class)->find($content['idDocument']);

        if(!$indices)
        {
            return "not Indices found for this id : " + $id;
        }
        else
        {
            $indices->setValue($content['value']);
            $indices->setIdRule($Rule);
            $indices->setIdDocument($Document);
            $entityManager->persist($indices);
            $entityManager->flush();

            return $this->json([
                "msg" => "L indice a bien ete modifie.",
                'id' => $indices->getId(),
                'value' => $indices->getValue(),
                'Document' => $Document->getName(),
                'idDocument' => $Document->getId(),
                'Rule' => $Rule->getName(),
                'idRule' => $Rule->getId()
            ]);
        }
    }
    // Ajouter un indice
    // #[Route('/Indices', name: 'addIndices', methods: ['POST'])]
    /**
     * @Route("/Indices", name="addIndices", methods={"POST"})
     */
    public function addIndices(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $indices = new Indices($doctrine->getRepository(Indices::class)->findBy(array(),array('id'=>'DESC'),1,0));
        $content = json_decode($request->getContent(), true);
        $Rule = $doctrine->getRepository(Rule::class)->find($content['idRule']);
        $Document = $doctrine->getRepository(Document::class)->find($content['idDocument']);
        $indices->setValue($content['value']);
        $indices->setIdRule($Rule);
        $indices->setIdDocument($Document);
        $entityManager->persist($indices);
        $entityManager->flush();

        return $this->json([
            "msg" => "L indice a bien ajoute",
            'id' => $indices->getId(),
            'value' => $indices->getValue(),
            'Document' => $Document->getName(),
            'idDocument' => $Document->getId(),
            'Rule' => $Rule->getName(),
            'idRule' => $Rule->getId()
        ]);
    }
    // Supprimer un indice
    // #[Route('/Indices/{id}', name: 'delIndices', methods: ['DELETE'])]
    /**
     * @Route("/Indices/{id}", name="delIndices", methods={"DELETE"})
     */
    public function delIndices(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $indices = $entityManager->getRepository(Indices::class)->find($id);
        $entityManager->remove($indices);
        $entityManager->flush();
        return $this->json("L indice a bien ete supprime");
    }
}
