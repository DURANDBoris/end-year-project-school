<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Annotations as OA;
use App\Utils\CustomFireWall;
use App\Entity\User;

class ApiLoginController extends AbstractController
{
  /**
    * @Route("/login", name="api_login", methods={"POST"})
    *
    * @OA\RequestBody(                    
    *   description="User credentials",
    *   required=true,
    *   @OA\JsonContent(
    *     @OA\Property(property="username",type="string"),
    *     @OA\Property(property="password",type="string"),
    *   )
    * )
     * @OA\Response(
     *     response=200,
     *     description="Returns token and user info.",
     *     @OA\JsonContent(
     *          @OA\Property(property="token",type="string"),
     *          @OA\Property(property="user",type="object",
     *            @OA\Property(property="id",type="integer"),
     *            @OA\Property(property="firstname",type="string"),
     *            @OA\Property(property="lastname",type="string"),
     *            @OA\Property(property="email",type="string")
     *          ),
     *          @OA\Property(property="organizations",type="array",
     *            @OA\Items(
     *              @OA\Property(property="id",type="integer"),
     *              @OA\Property(property="name",type="string"),
     *              @OA\Property(property="owner",type="object",
     *                @OA\Property(property="id",type="integer"),
     *                @OA\Property(property="firstname",type="string"),
     *                @OA\Property(property="lastname",type="string"),
     *                @OA\Property(property="email",type="string"),
     *              ),
     *            )
     *          )
     *     )
     * )
     * @OA\Response(
     *     response=403,
     *     description="Passord incorrect",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
     * @OA\Response(
     *     response=404,
     *     description="Username not found",
     *     @OA\JsonContent(@OA\Property(property="msg",type="string"))
     * )
    * @OA\Tag(name="users")
  */
  public function login(ManagerRegistry $doctrine, 
  Request $request, 
  UserPasswordHasherInterface $userPasswordHasher, 
  JWTTokenManagerInterface $JWTManager,
  UserInOrganizationController $userInOrganizationController,
  CustomFireWall $customFireWall): JsonResponse
  {

    $entityManager = $doctrine->getManager();
    $user = new User();
    $content = json_decode($request->getContent(), true);

    $user->setEmail($content["username"]);


    $targetUser = $entityManager->getRepository(User::class)->findBy(["email" => $user->getEmail()]);
    if (!$targetUser) return $this->json(["msg" => "Email incorrect",], 404);

    $targetUser = $targetUser[0];
    $password = $content['password'];
  

    if (!$userPasswordHasher->isPasswordValid($targetUser, $password)) return $this->json(["msg" => "password incorrect"], 403);

    $token = $JWTManager->create($user);

    $organizationList = $userInOrganizationController->listFromUser($doctrine, $targetUser->getId(), $customFireWall);


    return $this->json([
      "token" => $token,
      "user" => $targetUser->toString(),
      'organizations' => json_decode($organizationList->getContent()),
    ]);
    }
}

?>