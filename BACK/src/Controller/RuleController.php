<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

use App\Entity\Rule;
use App\Entity\Folder;
use App\Utils\CustomFireWall;
use App\Entity\UserInOrganization;

class RuleController extends AbstractController
{

    /**
     * @Route("/rules/{id_folder}", name="index_rules", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of rules in a folder",
     *     @OA\JsonContent(
     *          @OA\Property(property="folder",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="organization",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="owner",type="object",
     *                      @OA\Property(property="id",type="integer"),
     *                      @OA\Property(property="firstname",type="string"),
     *                      @OA\Property(property="lastname",type="string"),
     *                      @OA\Property(property="email",type="string"),         
     *                  ),
     *             ),
     *         ),
     *          @OA\Property(property="list",type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="mandatory",type="boolean"),
     *              )       
     *          )
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not allowed",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder not found",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="rules")
     * @Security(name="Bearer")
     */
    public function index(ManagerRegistry $doctrine, int $id_folder, CustomFireWall $customFirewall): Response
    {
        $folder = $doctrine->getRepository(Folder::class)->find($id_folder);
        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);

        $organization = $folder->getIdOrganization();
        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idUser" => $this->getUser()->getId(), "idOrganization" => $organization->getId()]);
        if($customFirewall->isAlreadyInOrganization($this->getUser(), $userInOrganizations)) return $this->json(["msg" => "Access forbidden"], 403);

        $rules = $doctrine->getRepository(Rule::class)->findBy(["idFolder" => $id_folder]);

        $result = array();
        $result["list"] = array();

        foreach ($rules as $rule) {
            array_push($result["list"],array(
                'id' => $rule->getId(),
                'name' => $rule->getName(),
                'type' => $rule->getType(),
                "mandatory" => $rule->isMandatory() 
            ));   
        }

        $result["folder"] = $folder->toString();
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/rule/{id_rule}", name="show_rule", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns a secific rule.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="type",type="string"),
     *          @OA\Property(property="mandatory",type="boolean"),
     *          @OA\Property(property="id_folder",type="integer"),
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not allowed",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="Rule not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Tag(name="rules")
     * @Security(name="Bearer")
     */
    public function show(ManagerRegistry $doctrine, int $id_rule, CustomFireWall $customFirewall): Response
    {
        $rule = $doctrine->getRepository(Rule::class)->find($id_rule);

        if(!$rule) return $this->json(["msg" => "Rule not found"], 404);

        $folder = $rule->getIdFolder();
        $organization = $folder->getIdOrganization();
        $userInOrganizations = $doctrine->getRepository(UserInOrganization::class)->findBy(["idUser" => $this->getUser()->getId(), "idOrganization" => $organization->getId()]);
        if($customFirewall->isAlreadyInOrganization($this->getUser(), $userInOrganizations)) return $this->json(["msg" => "Access forbidden"], 403);


        return $this->json($rule->toString());
    }


    /**
     * @Route("/rule/{id_rule}", name="update_rule", methods={"PUT"})
     * 
     * @OA\RequestBody(
     *   description="Update rule object",
     *   required=true,
     *   @OA\JsonContent(
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="type",type="string"),
     *          @OA\Property(property="mandatory",type="boolean"),
     *   )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Update a rule",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="type",type="string"),
     *          @OA\Property(property="mandatory",type="boolean"),
     *          @OA\Property(property="id_folder",type="integer"),
     *     )
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * 
     * @OA\Response(
     *     response=404,
     *     description="Rule not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="rules")
     * @Security(name="Bearer")
     */
    public function update(int $id_rule, Request $request, ManagerRegistry $doctrine, CustomFireWall $customFirewall): Response
    {
        $entityManager = $doctrine->getManager();

        $rule = $doctrine->getRepository(Rule::class)->find($id_rule);
        $content = json_decode($request->getContent(), true);

        if(!$rule) return $this->json(["msg" => "Rule not found"], 404);
        $folder = $rule->getIdFolder();
        $organization = $folder->getIdOrganization();

        if(!$customFirewall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden"], 403);

        else
        {
            $rule->setName($content['name']);
            $rule->setType($content['type']);
            $rule->setMandatory($content['isMandatory']);

            $entityManager->persist($rule);
            $entityManager->flush();

            return $this->json($rule->toString());
        }
    }
    /**
     * @Route("/rule/{id_folder}", name="create_rule", methods={"POST"})
     * 
     * @OA\RequestBody(
     *   description="Create rule object",
     *   required=true,
     *   @OA\JsonContent(
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="type",type="string"),
     *          @OA\Property(property="mandatory",type="boolean"),
     *   )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Create a rule",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="type",type="string"),
     *          @OA\Property(property="mandatory",type="boolean"),
     *          @OA\Property(property="id_folder",type="integer"),
     *     )
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="rules")
     * @Security(name="Bearer")
     */
    public function create(Request $request, ManagerRegistry $doctrine, $id_folder, CustomFireWall $customFirewall): Response
    {
        $entityManager = $doctrine->getManager();

        $folder = $doctrine->getRepository(Folder::class)->find($id_folder);
        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);
        $organization = $folder->getIdOrganization();

        if(!$customFirewall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden"], 403);

        $content = json_decode($request->getContent(), true);

        $rule = new Rule();

        $rule->setName($content['name']);
        $rule->setType($content['type']);
        $rule->setMandatory($content['isMandatory']);
        $rule->setIdFolder($folder);

        $entityManager->persist($rule);
        $entityManager->flush();

        return $this->json($rule->toString());
    }
    /**
     * @Route("/rule/{id_rule}", name="delete_rule", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Delete a rule",
     *     @OA\JsonContent(
     *          @OA\Property(property="msg",type="string"),
     *     )
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * 
     * @OA\Response(
     *     response=404,
     *     description="Rule not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="rules")
     * @Security(name="Bearer")
     */
    public function delete(int $id_rule, ManagerRegistry $doctrine, CustomFireWall $customFirewall): Response
    {
        $entityManager = $doctrine->getManager();
        $rule = $entityManager->getRepository(Rule::class)->find($id_rule);

        if(!$rule) return $this->json(["msg" => "Rule not found"], 404);
        $folder = $rule->getIdFolder();
        $organization = $folder->getIdOrganization();

        if(!$customFirewall->preventOrganization($this->getUser(), $organization)) return $this->json(["msg" => "Access forbidden"], 403);

        $entityManager->remove($rule);
        $entityManager->flush();
        
        return $this->json(["msg" => "Rule succesfully deleted"], 200);
    }
}
