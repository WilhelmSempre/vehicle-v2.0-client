<?php

namespace App\Security;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package App\Security
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class UserProvider implements UserProviderInterface
{

    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param string $email
     * @return bool|UserInterface
     */
    public function loadUserByUsername($email): UserInterface
    {
        $user = new User();

        $user->setEmail($email);

        return $this->userService
            ->getUserByEmail($user)
        ;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getEmail());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}