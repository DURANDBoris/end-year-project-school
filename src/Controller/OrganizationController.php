<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;

use App\Entity\Organization;
use App\Entity\User;

class OrganizationController extends AbstractController
{
    // Obtenir une organisation
    /**
     * @Route("/organization/{id}", name="show_organization", methods={"GET","HEAD"})
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $Organization = $doctrine->getRepository(Organization::class)->find($id);
        $owner = $doctrine->getRepository(User::class)->find($Organization->getOwner());
        return $this->json([
            'id' => $Organization->getId(),
            'name' => $Organization->getName(),
            'owner' => [
                "id" => $owner->getId(),
                "firstname" => $owner->getFirstName(),
                "lastname" => $owner->getLastName(),
                "email" => $owner->getEmail(),
            ],
        ]);
    }

    // Modifier une organisation
    /**
     * @Route("/organization/{id}", name="update_organization", methods={"PUT"})
     */
    public function update(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = $entityManager->getRepository(Organization::class)->find($id);
        $content = json_decode($request->getContent(), true);

        if(!$organization) return "Organization not found wiht id: " + $id;

        $organization->setName($content['name']);
        
        // Check if owner change. If so, set new owner by getting user by it's Id
        $newOwnerId = $content['userId'];
        if ($newOwnerId !== $organization->getOwner()->getId()) {
            // The owner changed
            $newOwner = $entityManager->getRepository(User::class)->find($newOwnerId);
            $organization->setOwner($newOwner);
        }
        
        $entityManager->persist($organization);
        $entityManager->flush();

        return $this->json([
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'owner' => [
                "id" => $newOwner->getId(),
                "firstname" => $newOwner->getFirstName(),
                "lastname" => $newOwner->getLastName(),
                "email" => $newOwner->getEmail(),
            ],
        ]);
        
    }

    // Ajouter une organisation
    /**
     * @Route("/organization", name="create_organization", methods={"POST"})
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = new Organization();
        $content = json_decode($request->getContent(), true);

        $organization->setName($content['name']);
        $organization->setOwner($this->getUser());



        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($organization);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->json([
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'owner' => $organization->getOwner()->getFirstName() . " " . $organization->getOwner()->getLastName()
        ]);
    }

    // Supprimer une organisation
    /**
     * @Route("/organization/{id}", name="remove_organization", methods={"DELETE"})
     */
    public function remove(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = $entityManager->getRepository(Organization::class)->find($id);

        $entityManager->remove($organization);
        $entityManager->flush();
        return $this->json(["message" => "success"]);
    }
}
