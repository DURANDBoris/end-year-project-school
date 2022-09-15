<?php

namespace App\test\unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase {


    public function getEntity(): User // Retroune un User valide
    {
        return (new User())
        ->setFirstname("John")
        ->setLastname("Doe")
        ->setEmail("john.doe@gmail.com")
        ->setPassword("123");
    }

    public function assertValidation(USer $user, int $nb_error){
        self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($user);
        
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        
        
        $this->assertCount($nb_error, $errors, implode(PHP_EOL, $messages));
    }

    public function testValidEntity() { // Test si objet user valide
        $this->assertValidation($this->getEntity(),0);
    }

    public function testInvalidBlankEntity() {// Test si objet user non valide

        $this->assertValidation($this->getEntity()->setFirstname(""), 1);
        $this->assertValidation($this->getEntity()->setLastname(""), 1);
        $this->assertValidation($this->getEntity()->setEmail(""), 1);
        $this->assertValidation($this->getEntity()->setPassword(""), 1);

    }

    public function testInvalidFormatEntity() {

        $this->assertValidation($this->getEntity()->setFirstname("13"), 0);
        $this->assertValidation($this->getEntity()->setLastname("10"), 0);
        $this->assertValidation($this->getEntity()->setEmail("azazaz"), 1);

    }

}
?>