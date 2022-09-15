<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

use App\Service\Uploader;
use App\Entity\Document;
use App\Entity\Folder;
use App\Entity\User;
use App\Entity\Organization;
use App\Entity\UserInOrganization;

class FileLoaderController extends AbstractController
{


    public $chemin = "../container/";

    /**
     * @Route("/download/{id_document}", name="download", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Return pdf binary base on its ID",
     *     @OA\MediaType(
     *         mediaType="application/pdf",
     *         @OA\Schema(
     *            type="string",
     *            format="binary"
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Document not found",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function download(Request $request, ManagerRegistry $doctrine, int $id_document): Response
    {

        $document = $doctrine->getRepository(Document::class)->find($id_document);
        if(!$document) return $this->json(["msg" => "Document not found"], 404);

        $userInOrganization = $doctrine->getRepository(UserInOrganization::class)->findBy([
            "idUser" => $this->getUser()->getId(),
            "idOrganization" => $document->getIdFolder()->getIdOrganization()->getId(),
        ]);

        if(count($userInOrganization) === 0) 
            return $this->json(["msg" => "User isn't allowed to access to this ressource"], 403);

            
        // Envoie du fichier
            $response = new Response();
            $response->setContent(file_get_contents("../container/" . $id_document . ".pdf"));
            $response->headers->set('Content-Type', 'application/force-download'); //pdf'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
            $response->headers->set('Content-disposition', 'filename='. $document->getName());

            
            return $response;
    }


    /**
     * @Route("/upload/{id_folder}", name="upload", methods={"POST"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the document properties",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="firstname",type="string"),
     *          @OA\Property(property="lastname",type="string"),
     *          @OA\Property(property="email",type="string"),
     *        )
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Folder not found",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @Security(name="Bearer")
     * @OA\Tag(name="documents")
     */
    public function upload(Request $request, ManagerRegistry $doctrine, int $id_folder): Response
    {    

        $entityManager = $doctrine->getManager();
        $uploadedFile = $request->files->get("file"); // Recupération des données du fichier
        
        $folder = $doctrine->getRepository(Folder::class)->find($id_folder);
        if(!$folder) return $this->json(["msg" => "Folder not found"], 404);

        $userInOrganization = $doctrine->getRepository(UserInOrganization::class)->findBy([
            "idUser" => $this->getUser()->getId(),
            "idOrganization" => $folder->getIdOrganization()->getId(),
        ]);

        if(count($userInOrganization) === 0) 
            return $this->json(["msg" => "User isn't in the organization which contains with id " . $id_folder]);


        $document = new Document();
        $document->setName("Temp_" . $uploadedFile->getClientOriginalName());
        $document->setType($uploadedFile->guessExtension());
        $document->setSize($uploadedFile->getSize());
        $document->setPath($this->chemin);
        $document->setToIndex(true);
        $document->setIdFolder($folder); 
        $entityManager->persist($document);
        $entityManager->flush();

        $document->setPath($this->chemin);
        $document->setName($uploadedFile->getClientOriginalName());

        $entityManager->persist($document);
        $entityManager->flush();
        

        $uploadedFile->move($document->getPath(), $document->getId() . "." . $document->getType());
        return $this->json([
            "id" => $document->getId(),
            "name" => $document->getName(),
            "type" => $document->getType(),
            "size" => $document->getSize(),
            "path" => $document->getPath(),
            "toIndex" => $document->isToIndex(),
            "id_folder" => $document->getIdFolder()->getId()
        ]);
        $uploadedFile->move($document->getPath(), $document->getName() . "." . $document->getType());
        return $this->json($document->toString());

    }
}
