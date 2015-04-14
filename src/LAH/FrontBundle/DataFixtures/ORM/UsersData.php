<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use LAH\FrontBundle\DataFixtures\BaseFixture;
use LAH\FrontBundle\Entity\User;

class UsersData extends BaseFixture
{
    public function insert($params)
    {
        $user = new User();
        $user->setUsername($params['username']);
        $user->setEmail($params['email']);
        $user->setRoles($params['roles']);
        $user->setPlainPassword($params['plainPassword']);
        $user->setEnabled($params['enabled']);

        return $user;
    }
}
