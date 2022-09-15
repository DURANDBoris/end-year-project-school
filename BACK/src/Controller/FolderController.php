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

use App\Entity\Folder;
use App\Entity\Organization;

class FolderController extends AbstractController
{

    /**
     * @Route("/folders/{id_organization}", name="index_folder_organization", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of folders in an organization.",
     *     @OA\JsonContent(
     *           @OA\Property(property="organization",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="owner",type="object",
     *                      @OA\Property(property="id",type="integer"),
     *                      @OA\Property(property="firstname",type="string"),
     *                      @OA\Property(property="lastname",type="string"),
     *                      @OA\Property(property="email",type="string"),         
     *                  ),
     *             ),
     *         @OA\Property(property="list",type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="icon",type="integer"),
     *              )
     *         )
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="folders")
     *
     */
    public function index(ManagerRegistry $doctrine, int $id_organization): Response
    {
        $folders = $doctrine->getRepository(Folder::class)->findBy(["idOrganization" => $id_organization]);
        
        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);

        $result = array();

        $result["list"] = array();

        foreach ($folders as $folder) {
            array_push($result["list"], array(
                "id" => $folder->getId(),
                "name" => $folder->getName(),
                "icon" => $folder->getIcon(),
            ));
        }

        $result["organization"] = $organization->toString();

        return $this->json(
            $result
        );
    }
    /**
     * @Route("/folder/{id_folder}", name="show_folder", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns document object.",
     *     @OA\JsonContent(
     *         @OA\Property(property="id",type="integer"),
     *         @OA\Property(property="name",type="string"),
     *         @OA\Property(property="icon",type="integer"),
     *         @OA\Property(property="organization",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                      @OA\Property(property="lastname",type="string"),
     *                      @OA\Property(property="email",type="string"),         
     *              ),
     *         ),
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="folders")
     */
    public function show(ManagerRegistry $doctrine, int $id_folder): Response
    {
        $folder = $doctrine->getRepository(Folder::class)->find($id_folder);

        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);
        $organization = $doctrine->getRepository(Organization::class)->find($folder->getIdOrganization());
        return $this->json([
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            "icon" => $folder->getIcon(),
            'organization' => $organization->toString(),
        ]);
    }


    /**
     * @Route("/folder/{id_folder}", name="update_older", methods={"PUT"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns document object.",
     *     @OA\JsonContent(
     *         @OA\Property(property="id",type="integer"),
     *         @OA\Property(property="name",type="string"),
     *         @OA\Property(property="icon",type="integer"),
     *         @OA\Property(property="organization",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                      @OA\Property(property="lastname",type="string"),
     *                      @OA\Property(property="email",type="string"),         
     *              ),
     *         ),
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="folders")
     */
    public function update(int $id_folder, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $folder = $entityManager->getRepository(Folder::class)->find($id_folder);
        $content = json_decode($request->getContent(), true);

        $organization = $doctrine->getRepository(Organization::class)->find($folder->getIdOrganization());

        $folder->setName($content['name']);
        $folder->setIcon($content['icon']);
        
        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->json([
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            "icon" => $folder->getIcon(),
            'organization' => $organization->toString(),
        ]);
    }
    
    /**
     * @Route("/folder/{id_organization}", name="create_folder", methods={"POST"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns document object.",
     *     @OA\JsonContent(
     *         @OA\Property(property="id",type="integer"),
     *         @OA\Property(property="name",type="string"),
     *         @OA\Property(property="icon",type="integer"),
     *         @OA\Property(property="organization",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                      @OA\Property(property="lastname",type="string"),
     *                      @OA\Property(property="email",type="string"),         
     *              ),
     *         ),
     *      )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Organization not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="folders")
     */
    public function create(Request $request, ManagerRegistry $doctrine, int $id_organization): Response
    {
        $entityManager = $doctrine->getManager();

        $folder = new Folder();
        $content = json_decode($request->getContent(), true);
        
        $organization = $doctrine->getRepository(Organization::class)->find($id_organization);
        
        $folder->setName($content['name']);
        $folder->setIcon($content['icon']);
        $folder->setIdOrganization($organization);

        $entityManager->persist($folder);
        $entityManager->flush();

        return $this->json([
            "id" => $folder->getId(),
            "name" => $folder->getName(),
            "icon" => $folder->getIcon(),
            'organization' => $organization->toString()
        ]);
    }


    /**
     * @Route("/folder/{id_folder}", name="remove_folder", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Delete a folder.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="Folder not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="folders")
     */
    public function remove(int $id_folder, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $folder = $entityManager->getRepository(Folder::class)->find($id_folder);

        $entityManager->remove($folder);
        $entityManager->flush();
        
        return $this->json(["msg" => "Folder removed"]);
    }
}
