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

use App\Entity\Indices;
use App\Entity\Rule;
use App\Entity\Document;
use App\Entity\Folder;

class IndicesController extends AbstractController
{

    /**
     * @Route("/index/{id_document}", name="index_index", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns indexes for a give document.",
     *     @OA\JsonContent(
     * 
     *              @OA\Property(property="document",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="size",type="string"),
     *                  @OA\Property(property="path",type="string"),
     *                  @OA\Property(property="toIndex",type="boolean"),
     *                  @OA\Property(property="id_folder",type="integer"),
     *              ),
     * 
     *          @OA\Property(property="list",type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="value",type="string"),
     *                  @OA\Property(property="rule",type="object",
     *                      @OA\Property(property="id",type="integer"),
     *                      @OA\Property(property="name",type="string"),
     *                      @OA\Property(property="type",type="string"),
     *                      @OA\Property(property="mandatory",type="boolean"),
     *                      @OA\Property(property="id_folder",type="integer"),
     *                  ),
     *              )
     *          )
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Document not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Tag(name="index")
     * @Security(name="Bearer")
     */
    public function index(ManagerRegistry $doctrine, int $id_document): Response
    {
        $document = $doctrine->getRepository(Document::class)->find($id_document);
        if(!$document) return $this->json(["msg" => "Document not found"], 404);

        $indexes = $doctrine->getRepository(Indices::class)->findBy(["idDocument" => $id_document]);

        

        $result = array();
        $result["list"] = array();

        foreach ($indexes as $index) {
            array_push($result["list"],array(
                "id" => $index->getId(),
                "value" => $index->getValue(),
                "rule" => $index->getIdRule()->toString(),
            ));
            
        }

        $result["document"] = $document->toString();

        return $this->json(
            $result
        );
    }

    /**
     * @Route("/index/{id_index}", name="update_index", methods={"PUT"})
     * 
     * @OA\RequestBody(
     *   description="Update an Index",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="value",type="string"),
     *   )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns indexes for a give document.",
     *     @OA\JsonContent(
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="value",type="string"),
     *              @OA\Property(property="rule",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="mandatory",type="boolean"),
     *                  @OA\Property(property="id_folder",type="integer"),
     *              ),
     *              @OA\Property(property="document",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="size",type="string"),
     *                  @OA\Property(property="path",type="string"),
     *                  @OA\Property(property="toIndex",type="boolean"),
     *                  @OA\Property(property="id_folder",type="integer"),
     *              )
     *    ))
     * 
     * @OA\Response(
     *     response=404,
     *     description="Index not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Tag(name="index")
     * @Security(name="Bearer")
     */
    public function update(int $id_index, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $index = $doctrine->getRepository(Indices::class)->find($id_index);

        if(!$index) return $this->json(["msg" => "index not found"]);

        $content = json_decode($request->getContent(), true);
 
        $index->setValue($content["value"]);

        $entityManager->persist($index);
        $entityManager->flush();

        return $this->json($index->toString());
    }

    /**
     * @Route("/index/{id_folder}", name="create_index", methods={"POST"})
     * 
     * @OA\RequestBody(
     *   description="Create index object",
     *   required=true,
     *   @OA\JsonContent(
     *          @OA\Property(property="value",type="string"),
     *          @OA\Property(property="id_rule",type="integer"),
     *          @OA\Property(property="id_document",type="integer"),
     *   )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns indexes for a give document.",
     *     @OA\JsonContent(
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="value",type="string"),
     *              @OA\Property(property="rule",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="mandatory",type="boolean"),
     *                  @OA\Property(property="id_folder",type="integer"),
     *              ),
     *              @OA\Property(property="document",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="size",type="string"),
     *                  @OA\Property(property="path",type="string"),
     *                  @OA\Property(property="toIndex",type="boolean"),
     *                  @OA\Property(property="id_folder",type="integer"),
     *              )
     *    ))
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder or Rule or Document not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Tag(name="index")
     * @Security(name="Bearer")
     */
    public function create(Request $request, ManagerRegistry $doctrine, int $id_folder): Response
    {
        $entityManager = $doctrine->getManager();

        $folder = $entityManager->getRepository(Folder::class)->find($id_folder);
        if(!$folder) return $this->json(["msg" => "Folder not found"]);

        $index = new Indices();
        $content = json_decode($request->getContent(), true);

        $index->setValue($content["value"]);

        $rule = $entityManager->getRepository(Rule::class)->find($content["id_rule"]);
        if(!$rule) return $this->json(["msg" => "Rule not found"]);

        $document = $entityManager->getRepository(Document::class)->find($content["id_document"]);
        if(!$document) return $this->json(["msg" => "Document not found"]);
        
        $index->setIdRule($rule);
        $index->setIdDocument($document);

        $entityManager->persist($index);
        $entityManager->flush();

        return $this->json(
            $index->toString()
        );
    }

    /**
     * @Route("/index/{id_index}", name="delete_index", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Delete an index",
     *     @OA\JsonContent(
     *          @OA\Property(property="msg",type="string"),
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Index not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="User not allowed.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @OA\Tag(name="index")
     * @Security(name="Bearer")
     */
    public function delete(int $id_index, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $index = $entityManager->getRepository(Indices::class)->find($id_index);
        if(!$index) return $this->json(["msg" => "Index not found"]);

        $entityManager->remove($index);
        $entityManager->flush();
        return $this->json(["msg" => "Index successfully deleted"]);
    }
}
