<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;

class UserControllerTest extends WebTestCase {

    public function getUser(): User //Which user will be tested ?
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        return $userRepository->findOneByEmail('christian.leseurre@gmail.com');
    }

    public function getUserTest() {
        $user = array(
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "john.doe@gmail.com",
            "password" => "123456"
        );
        return $user;
    }

    public function setUserTest($field, $value) {
        $user = array(
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "john.doe@gmail.com",
            "password" => "123456"
        );

        $user[$field] = $value;
        return $user;
    }

    public function assertStatusCode($client, $method, $uri, $parameters, $expectedCode, $message){ // Launch test
        $client->jsonRequest(method: $method, uri: $uri, parameters: $parameters);
        $this->assertResponseStatusCodeSame(expectedCode: $expectedCode, message: $message);
    }

    public function assertStatusCodeWOBody($client, $method, $uri, $expectedCode, $message){
        $client->request($method, $uri);
        $this->assertResponseStatusCodeSame(expectedCode: $expectedCode, message: $message);
    }

    public function testRegister() {
        $client = static::createClient();

        // Firstname Tests
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("firstname", ""), 403, "Firstname error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("firstname", "  "), 403, "Firstname error");

        // Lastname Tests
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("lastname", ""), 403, "Lastname error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("lastname", "  "), 403, "Lastname error");

        // Email Tests
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("email", ""), 403, "email error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("email", "azazaz"), 403, "email error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("email", "zedzdzd@"), 403, "email error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("email", "zdzdzd@.fr"), 403, "email error");
        $this->assertStatusCode($client, 'POST', '/register', $this->setUserTest("email", "@."), 403, "email error");

        // Perfect Case
        $this->assertStatusCode($client, 'POST', '/register', $this->getUserTest(), 200, "Form incorrect");

    }

    public function testLogin() {
        $client = static::createClient();
        
        $testUser = $this->getUser();

        $this->assertStatusCode($client, 'POST', '/login', array("username" => "unkown@gmail.com", "password" => "aaaaaa"), 404, "Email error");
        $this->assertStatusCode($client, 'POST', '/login', array("username" => $testUser->getEmail(), "password" => "aaaaa"), 403, "Password error");
        
        // Perfect Case
        $this->assertStatusCode($client, 'POST', '/login', array("username" => $testUser->getEmail(), "password" => "123AZEqsd"), 200, "Credentials error");
    }

    public function testShowUser_id_existing() {
        $client = static::createClient();
        $testUser = $this->getUser();

        $client->loginUser($testUser);

        $client->request('GET', '/user/' . $testUser->getId());
        $this->assertResponseStatusCodeSame(expectedCode: 200, message: "show user id existing");  
    }

    public function testShowUser_id_missing() {
        $client = static::createClient();
        $testUser = $this->getUser();

        $client->loginUser($testUser);

        $client->request('GET', '/user/');
        $this->assertResponseStatusCodeSame(expectedCode: 404, message: "show user id missing");  
    }

    public function testShowUser_id_is_string() {
        $client = static::createClient();
        $testUser = $this->getUser();

        $client->loginUser($testUser);

        $client->request('GET', '/user/az');
        $this->assertResponseStatusCodeSame(expectedCode: 404, message: "show user id string");  
    }

    public function testShowUser_id_not_exist() {
        $client = static::createClient();
        $testUser = $this->getUser();

        $client->loginUser($testUser);

        $client->request('GET', '/user/0');
        $this->assertResponseStatusCodeSame(expectedCode: 404, message: "show user id string");  
    }
}
?>