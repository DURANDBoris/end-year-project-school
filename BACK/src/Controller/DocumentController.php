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

use App\Entity\Document;
use App\Entity\Folder;

class DocumentController extends AbstractController
{
    /**
     * @Route("/documents/{id_folder}", name="index_document", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns document list in a folder.",
     *     @OA\JsonContent(
     * 
     *         @OA\Property(property="folder",type="object",
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
     *         @OA\Property(property="list",type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="name",type="string"),
     *                  @OA\Property(property="type",type="string"),
     *                  @OA\Property(property="size",type="string"),
     *                  @OA\Property(property="path",type="string"),
     *                  @OA\Property(property="toIndex",type="boolean"),
     *              )
     *         )
     *      )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Folder not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function index(ManagerRegistry $doctrine, int $id_folder): Response
    {
        $documents  = $doctrine->getRepository(Document::class)->findBy(["idFolder" => $id_folder]);

        $folder = $doctrine->getRepository(Folder::class)->find($id_folder);
        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);
        
        
        $result = array();

        $result["list"] = array();
        foreach ($documents as $document) { 

            array_push($result["list"], array(
                'id' => $document->getId(),
                'name' => $document->getName(),
                'type' => $document->getType(),
                'size' => $document->getSize(),
                'path' => $document->getPath(),
                'toIndex' => $document->isToIndex(),
            ));
            
        }

        $result["folder"] = $folder->toString();

        return $this->json($result);

    }

    /**
     * @Route("/document/{id_document}", name="show_document", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns document object.",
     *     @OA\JsonContent(
     *      @OA\Property(property="id",type="integer"),
     *      @OA\Property(property="name",type="string"),
     *      @OA\Property(property="type",type="string"),
     *      @OA\Property(property="size",type="string"),
     *      @OA\Property(property="path",type="string"),
     *      @OA\Property(property="toIndex",type="boolean"),
     *      @OA\Property(property="folder",type="object",
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="organization",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                  @OA\Property(property="lastname",type="string"),
     *                  @OA\Property(property="email",type="string"),    
     *              ),
     *          ),
     *      )
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Document not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function show(ManagerRegistry $doctrine, int $id_document): Response
    {
        $document = $doctrine->getRepository(Document::class)->find($id_document);
        
        return $this->json([
            'id' => $document->getId(),
            'name' => $document->getName(),
            'type' => $document->getType(),
            'size' => $document->getSize(),
            'path' => $document->getPath(),
            'toIndex' => $document->isToIndex(),
            'folder' => $document->getIdFolder()->toString()
        ]);
    }

    /**
     * @Route("/document/{id_document}", name="update_document", methods={"PUT"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Update document object.",
     *     @OA\JsonContent(
     *      @OA\Property(property="id",type="integer"),
     *      @OA\Property(property="name",type="string"),
     *      @OA\Property(property="type",type="string"),
     *      @OA\Property(property="size",type="string"),
     *      @OA\Property(property="path",type="string"),
     *      @OA\Property(property="toIndex",type="boolean"),
     *      @OA\Property(property="folder",type="object",
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="name",type="string"),
     *          @OA\Property(property="organization",type="object",
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                  @OA\Property(property="id",type="integer"),
     *                  @OA\Property(property="firstname",type="string"),
     *                  @OA\Property(property="lastname",type="string"),
     *                  @OA\Property(property="email",type="string"),    
     *              ),
     *          ),
     *      )
     *     )
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
     *     description="Document not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function update(int $id_document, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $document = $doctrine->getRepository(Document::class)->find($id_document);

        if(!$document) return $this->json(["msg" => "Document not found"], 404);
        $content = json_decode($request->getContent(), true);

        $document->setName($content["name"]);
        $document->setToIndex($content["toIndex"]);
        
        $folder = $doctrine->getRepository(Folder::class)->find($content["id_folder"]);
        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);

        $document->setIdFolder($folder);

        $entityManager->persist($document);
        $entityManager->flush();

        return $this->json($document->toString(), 200);

    }
    
    /**
     * @Route("/document/{id_document}", name="delete_document", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Delete a document.",
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
     *     description="Document not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * 
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function delete(int $id_document, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $document = $entityManager->getRepository(Document::class)->find($id_document);

        if(!$document) return $this->json(["msg" => "Document not found"]);

        // TODO: Un document peut être supprimé meme s'il a des index ?
        $entityManager->remove($document);
        $entityManager->flush();
        return $this->json(["msg" => "Document deleted succesfully"]);
    }
}
