<?php

namespace ApiBundle\Model;

class UserModel
{
    private $_em;
    private $_userManager;
    private $_container;

    public function __construct( $entityManager, $fosUserManager, $container )
    {
        $this->_em = $entityManager;
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null);

        $this->_userManager = $fosUserManager;
        $this->_container   = $container;
    }

    public function create( \ApiBundle\Entity\User $user)
    {
        $user->addRole('ROLE_USER');
        $user->setEnabled(true);

        $this->_userManager->updateUser($user);

        return $user;
    }

    public function update( \ApiBundle\Entity\User $user)
    {
        $this->_userManager->updateUser($user);

        return $user;
    }

}
