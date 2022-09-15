<?php

namespace App\Controller;

use App\Entity\UserInOrganization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Utils\CustomFireWall;
use App\Entity\Organization;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserInOrganizationController extends AbstractController
{
    /**
     * @Route("/organization/list/{id_organization}", name="show_organization_user", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns user list of an organization.",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="firstname",type="string"),
     *              @OA\Property(property="lastname",type="string"),
     *              @OA\Property(property="email",type="string"),
     *          )
     *      )   
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="organization")
     */
    public function listFromOrganization(ManagerRegistry $doctrine, int $id_organization, CustomFireWall $customFireWall): Response
    {

        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);
        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);

        
        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idOrganization" => $id_organization]);

        if(!$customFireWall->preventUserInOrganization($this->getUser(), $userInOrganizations)) {
            return $this->json(["msg" => "Access forbidden"], 403);
        }

        
        $result = array();

        foreach ($userInOrganizations as $row) {
            $user = $doctrine->getRepository(User::class)->find($row->getIdUser());
            array_push($result,$user->toString());
            
        }
        return $this->json(
            $result
        );
    }
    /**
     * @Route("/user/list/{id_user}", name="show_user_organization", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns organization list of an user.",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                  @OA\Property(property="lastname",type="string"),
     *                  @OA\Property(property="email",type="string"),
     *              )
     *          )
     *      )   
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="User not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="users")
     */
    public function listFromUser(ManagerRegistry $doctrine, int $id_user, CustomFireWall $customFireWall): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id_user);
        if (!$user) return $this->json(["msg" => "User not found",], 404);

        if($customFireWall->preventUser($this->getUser(), $user)) return $this->json(["msg" => "Access forbidden"], 403);

        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idUser" => $id_user]);
        $result = array();

        foreach ($userInOrganizations as $row) {
            $organization = $doctrine->getRepository(Organization::class)->find($row->getIdOrganization());
            array_push($result,$organization->toString());
            
        }
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/join/{id_organization}", name="join_user_organization", methods={"POST"})
     * 
     * @OA\RequestBody(
     *   description="Make an user join an Organization",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="email",type="string"),
     *   )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Make user join an organization.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found or Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="organization")
     */
    public function join(ManagerRegistry $doctrine, Request $request, int $id_organization, CustomFireWall $customFireWall, ValidatorInterface $validator)
    {
        $content = json_decode($request->getContent(), true);

        $user = $doctrine->getRepository(User::class)->findBy(["email" => $content["email"]])[0];
        if (!$user) return $this->json(["msg" => "User not found",], 404);

        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);
        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);


        if(!$customFireWall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden"], 403);

        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idUser" => $user->getId(), "idOrganization" => $organization->getId()]);
        if(!$customFireWall->isAlreadyInOrganization($user, $userInOrganizations)) return $this->json(["msg" => "User Already in organization"], 403);


        $entityManager = $doctrine->getManager();
        $userInOrganization = new UserInOrganization();

        $userInOrganization->setIdUser($user);
        $userInOrganization->setIdOrganization($organization);

        $entityManager->persist($userInOrganization);
        $entityManager->flush();

        return $this->json([
            "msg" => "user added succesfully",
        ]);
    }

    /**
     * @Route("/leave/{id_organization}/{id_user}", name="leave_user_organization", methods={"DELETE"})
     * 
     * 
     * @OA\Response(
     *     response=200,
     *     description="Make user leave an organization.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found or Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="organization")
     */
    public function leave(ManagerRegistry $doctrine, int $id_user, int $id_organization, CustomFireWall $customFireWall): Response
    {
        $entityManager = $doctrine->getManager();
        

        $user = $doctrine->getRepository(User::class)->find($id_user);
        if (!$user) return $this->json(["msg" => "User not found",], 404);

        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);
        if (!$organization) return $this->json(["msg" => "Organization not found",], 404);

        if(!$customFireWall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden"], 403);

        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idUser" => $user->getId(), "idOrganization" => $organization->getId()]);
        if($customFireWall->isAlreadyInOrganization($user, $userInOrganizations)) return $this->json(["msg" => "User Already out of organization"], 403);


        $userInOrganization = $entityManager->getRepository(UserInOrganization::class)->findBy(["idUser" => (string) $id_user])[0];
        $entityManager->remove($userInOrganization);
        $entityManager->flush();

        return $this->json([
            "msg" => "User succesfully removed from organization",
        ]);
    }
}
