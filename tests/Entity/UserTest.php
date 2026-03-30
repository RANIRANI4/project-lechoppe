<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testValidEntity(): void
    {
        self::bootKernel();

        $user = new User();
        $user->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('example@gmail.com')
            ->setPassword('password');

        $error = self::getContainer()->get('validator')->validate($user);
        $this->assertEquals(0, $error->count());

    }

    public function testEntityEmptyData(): void
    {
        self::bootKernel();

        $user = new User();
        $user->setFirstName('')
            ->setLastName('')
            ->setEmail('')
            ->setPassword('');

        $error = self::getContainer()->get('validator')->validate($user);
        $this->assertEquals(4, $error->count());
    }
}
