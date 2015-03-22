<?php
namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\User;

class UsersData extends BaseFixture
{
    public function insert($params)
    {
        $user = new User();
        $user->setUsername($params["username"]);
        $user->setEmail($params["email"]);
        $user->setRoles($params["roles"]);
        $user->setPlainPassword($params["plainPassword"]);
        $user->setEnabled($params["enabled"]);

        return $user;
    }
}
