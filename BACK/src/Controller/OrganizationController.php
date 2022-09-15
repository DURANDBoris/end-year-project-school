<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\UserInOrganizationController;

use App\Controller\getContent;
use App\Controller\TokenAuthenticatedController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Utils\CustomFireWall;
use App\Entity\Organization;
use App\Entity\User;

class OrganizationController extends AbstractController
{
    /**
     * @Route("/organizations", name="index_organization", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of organization.",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="owner",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="firstname",type="string"),
     *              @OA\Property(property="lastname",type="string"),
     *              @OA\Property(property="email",type="string"),
     *          )
     *        ),
     *     )
     * )
     * @OA\Tag(name="organization")
     */
    public function index(ManagerRegistry $doctrine) : Response {
        $organization = $doctrine->getRepository(Organization::class)->findAll();
        
        $result = array();

        foreach ($organization as $key => $organization) {
            $owner = $doctrine->getRepository(User::class)->find($organization->getOwner());

            $result[$key]["id"] = $organization->getId();
            $result[$key]["name"] = $organization->getName();
            $result[$key]["owner"]["id"] = $owner->getId();
            $result[$key]["owner"]["firstname"] = $owner->getFirstName();
            $result[$key]["owner"]["lastname"] = $owner->getLastName();
            $result[$key]["owner"]["email"] = $owner->getEmail();
        }    
                
        return $this->json([$result, 200]);
    }

    /**
     * @Route("/organization/{id_organization}", name="show_organization", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of organization.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="owner",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="firstname",type="string"),
     *              @OA\Property(property="lastname",type="string"),
     *              @OA\Property(property="email",type="string"),
     *          )
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="organization")
     */
    public function show(ManagerRegistry $doctrine, int $id_organization): Response
    {
        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);
        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);

        $owner = $doctrine->getRepository(User::class)->find($organization->getOwner());
        if (!$owner) return $this->json(["msg" => "Owner not found",], 404);


        return $this->json([
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'owner' => [
                "id" => $owner->getId(),
                "firstname" => $owner->getFirstName(),
                "lastname" => $owner->getLastName(),
                "email" => $owner->getEmail(),
            ],
        ], 200);
    }

    /**
     * @Route("/organization/{id_organization}", name="update_organization", methods={"PUT"})
     * @OA\Response(
     *     response=200,
     *     description="Update an Organization.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="owner",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="firstname",type="string"),
     *              @OA\Property(property="lastname",type="string"),
     *              @OA\Property(property="email",type="string"),
     *          )
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Organization or new Owner not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\RequestBody(
     *   description="Update an Organization",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="name",type="string"),
     *     @OA\Property(property="newOwnerId",type="integer"),
     *   )
     * )
     * @OA\Tag(name="organization")
     * @Security(name="Bearer")
     */
    public function update(int $id_organization, Request $request, ManagerRegistry $doctrine, CustomFireWall $customFireWall): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = $entityManager->getRepository(Organization::class)->find($id_organization);

        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);

        $content = json_decode($request->getContent(), true);
        $organization->setName($content['name']);

        
        // Check if owner change. If so, set new owner by getting user by it's Id
        $newOwnerId = $content['userId'];
        if ($newOwnerId !== $organization->getOwner()->getId()) {
            if(!$customFireWall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden",], 403);
            // The owner changed
            $newOwner = $entityManager->getRepository(User::class)->find($newOwnerId);

            // TODO: Check if user is in organization
            if (!$newOwner) return $this->json(["msg" => "User not found",], 404);
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
        ], 200);
        
    }

    /**
     * @Route("/organization", name="create_organization", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Create an Organization.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="owner",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="firstname",type="string"),
     *              @OA\Property(property="lastname",type="string"),
     *              @OA\Property(property="email",type="string"),
     *          )
     *     )
     * )
     * @OA\RequestBody(
     *   description="Create an Organization",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="name",type="string"),
     *   )
     * )
     * @OA\Tag(name="organization")
     * @Security(name="Bearer")
     */
    public function create(Request $request, ManagerRegistry $doctrine, UserInOrganizationController $userInOrganizationController): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = new Organization();
        $content = json_decode($request->getContent(), true);

        $organization->setName($content['name']);
        $organization->setOwner($this->getUser());


        $entityManager->persist($organization);
        $entityManager->flush();

        //$userInOrganizationController->join($doctrine, $request, $organization->getId()); //Make user join his organization

        
        return $this->json([
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'owner' => [
                "id" => $this->getUser()->getId(),
                "firstname" => $this->getUser()->getFirstName(),
                "lastname" => $this->getUser()->getLastName(),
                "email" => $this->getUser()->getEmail(),
            ],
        ], 200);
    }

    /**
     * @Route("/organization/{id_organization}", name="remove_organization", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Delete an Organization.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="Organization or new Owner not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="organization")
     * @Security(name="Bearer")
     */
    public function remove(int $id_organization, ManagerRegistry $doctrine, CustomFireWall $customFireWall): Response
    {
        $entityManager = $doctrine->getManager();
        $organization = $entityManager->getRepository(Organization::class)->find($id_organization);

        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);
        if(!$customFireWall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden",], 403);
        
        $entityManager->remove($organization);
        $entityManager->flush();
        return $this->json(["msg" => "success"], 200);
    }
}
