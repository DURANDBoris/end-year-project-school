<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{

    public function preventUser($currentUser, $tryOnId) {
        if ($currentUser !== $tryOnId) {
            return false;
        }
        return true;
    }

    /**
     * @Route("/users", name="index_users", methods={"GET"})
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

    // Obtenir un utilisateur
    /**
     * @Route("/user/{id}", name="show_user", methods={"GET","HEAD"})
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $User = $doctrine->getRepository(User::class)->find($id);
        // Un utilisateur ne peut pas voir les infos d'un autre
        if (!$this->preventUser($this->getUser()->getId(), $id)) return $this->json(["message" => "Access forbidden or user doesn't exist",]);

        return $this->json([
            'id' => $User->getId(),
            'firstname' => $User->getFirstname(),
            'lastname' => $User->getLastName(),
            'email' => $User->getEmail(),
        ]);
    }
    // Modifier un utilisateur
    /**
     * @Route("/user/{id}", name="update_user", methods={"PUT"})
     */
    public function update(int $id, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $content = json_decode($request->getContent(), true);


        // Un utilisateur ne peut pas voir les infos d'un autre
        if (!$this->preventUser($this->getUser()->getId(), $id)) return $this->json(["message" => "Access forbidden or user doesn't exist",]);

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
    // Ajouter un utilisateur
    /**
     * @Route("/register", name="add_user", methods={"POST"})
     */
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = new User();

        $content = json_decode($request->getContent(), true);

        $user->setFirstName($content['firstname']);
        $user->setLastName($content['lastname']);
        $user->setEmail($content['email']);
        $user->setPassword($content['password']);



        $password = $content['password'];
        $user->setPassword(
            $userPasswordHasher->hashPassword($user,$password)
        );
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->json([
            "id" => $user->getId(),
            "firstname" => $user->getFirstName(),
            "lastname" => $user->getLastName(),
            "email" => $user->getEmail()
        ], 200);
    }
    // Supprimer un utilisateur
    /**
     * @Route("/user/{id}", name="dremove_user", methods={"DELETE"})
     */
    public function remove(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$this->preventUser($this->getUser()->getId(), $id)) return $this->json(["message" => "Access forbidden or user doesn't exist",]);

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json(["message" => "ok",]);
    }
}
