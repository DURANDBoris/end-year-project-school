<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Utils\CustomFireWall;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use App\Entity\UserInOrganization;

class UserController extends AbstractController
{

    /**
     * @Route("/users", name="index_users", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of users.",
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
     * @OA\Tag(name="users")
     */
    public function index(ManagerRegistry $doctrine) : JsonResponse {
        $users = $doctrine->getRepository(User::class)->findAll();
        
        $result = array();

        foreach ($users as $key => $user) {
            $result[$key]["id"] = $user->getId();
            $result[$key]["firstname"] = $user->getFirstName();
            $result[$key]["lastname"] = $user->getLastName();
            $result[$key]["email"] = $user->getEmail();

        }        
                
        return $this->json($result);
    }

    /**
     * @Route("/user/{id_user}", name="show_user", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns specific user info.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="firstname",type="string"),
     *          @OA\Property(property="lastname",type="string"),
     *          @OA\Property(property="email",type="string")))
     * @OA\Response(
     *     response=404,
     *     description="User not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="users")
     */
    public function show(ManagerRegistry $doctrine, $id_user, CustomFireWall $customFireWall): Response
    {
        $user = $doctrine->getRepository(User::class)->find((int) $id_user);
        if (!$user) return $this->json(["msg" => "User not found",], 404);

        return $this->json([
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastName(),
            'email' => $user->getEmail(),
        ], 200);
    }

    /**
     * @Route("/user/{id_user}", name="update_user", methods={"PUT"})
     *   
     * @OA\Response(
     *     response=200,
     *     description="Update specific user info.",
     *     @OA\JsonContent(
     *          @OA\Property(property="id",type="integer"),
     *          @OA\Property(property="firstname",type="string"),
     *          @OA\Property(property="lastname",type="string"),
     *          @OA\Property(property="email",type="string")))
     * @OA\Response(
     *     response=404,
     *     description="User not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\RequestBody(
     *   description="Update user object",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="firstname",type="string"),
     *     @OA\Property(property="lastname",type="string"),
     *     @OA\Property(property="email",type="string"),
     *     @OA\Property(property="password",type="string"),
     *   )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function update(int $id_user, Request $request, ManagerRegistry $doctrine, 
    UserPasswordHasherInterface $userPasswordHasher, CustomFireWall $customFireWall): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id_user);
        if (!$user) return $this->json(["msg" => "User not found",], 404);


        $content = json_decode($request->getContent(), true);
        // Un utilisateur ne peut pas modifier les infos d'un autre
        if(!$customFireWall->preventUser($this->getUser(), $user)) return $this->json(["msg" => "Access forbidden",], 403);

        $user->setFirstName($content['firstname']);
        $user->setLastName($content['lastname']);
        $user->setEmail($content['email']);


        $password = $content['password'];
        $user->setPassword(
            $userPasswordHasher->hashPassword($user,$password)
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "id" => $user->getId(),
            "firstname" => $user->getFirstName(),
            "lastname" => $user->getLastName(),
            "email" => $user->getEmail(),
        ]);

    }

    /**
     * @Route("/register", name="add_user", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Register an user in database",
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
     * @OA\RequestBody(
     *   description="Create user object",
     *   required=true,
     *   @OA\JsonContent(
     *     @OA\Property(property="firstname",type="string"),
     *     @OA\Property(property="lastname",type="string"),
     *     @OA\Property(property="email",type="string"),
     *     @OA\Property(property="password",type="string"),
     *   )
     * )
     * @OA\Tag(name="users")
     */
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $user = new User();

        $content = json_decode($request->getContent(), true);

        $user->setFirstName(trim($content['firstname'], " "));
        $user->setLastName(trim($content['lastname'], " "));
        $user->setEmail($content['email']);
        $user->setPassword($content['password']);

        $password = $content['password'];
        $user->setPassword(
            $userPasswordHasher->hashPassword($user,$password)
        );
        $errors = $validator->validate($user);
        if(count($errors) > 0) return $this->json(["msg" => "Data doesn't match user constraints"],403);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "id" => $user->getId(),
            "firstname" => $user->getFirstName(),
            "lastname" => $user->getLastName(),
            "email" => $user->getEmail()
        ], 200);
    }
     
    /**
     * @Route("/user/{id_user}", name="dremove_user", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Remove an user from database",
     *     @OA\JsonContent(
     *          @OA\Property(property="msg",type="string"),
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=403,
     *     description="User not unauthorized.",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function remove(int $id_user, ManagerRegistry $doctrine, CustomFireWall $customFireWall): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id_user);

        if (!$user) return $this->json(["msg" => "User not found",], 404);
        if(!$customFireWall->preventUser($this->getUser(), $user)) return $this->json(["msg" => "Access forbidden",], 403);

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json(["message" => "ok",]);
    }
}
