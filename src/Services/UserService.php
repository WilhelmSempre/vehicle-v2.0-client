<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class UserService
 * @package App\Services
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class UserService
{

    /**
     * @var ApiAdapter
     */
    private ApiAdapter $adapter;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserService constructor.
     * @param ApiAdapter $adapter
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ApiAdapter $adapter, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->adapter = $adapter;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @return ResponseInterface
     */
    public function createUser(User $user): ?ResponseInterface
    {
        $options = [
            'email' => $user->getEmail(),
            'password' => $this->passwordEncoder->encodePassword($user, $user->getPassword()),
        ];

        return $this->adapter
            ->post('user/create', $options, true)
        ;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isUser(User $user): bool
    {
        return $this->getUserByEmail($user) instanceof User;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function getUserByEmail(User $user)
    {
        $options = [
            '{email}' => urlencode($user->getEmail()),
        ];

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->get('user/get/email/{email}', $options, true)
        ;

        try {
            if ($response && $response->getStatusCode() === Response::HTTP_OK) {
                return $this->adapter
                    ->deserialize($response->getContent(), User::class);
            }
        } catch (TransportExceptionInterface $error) {
            return false;
        }
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function loginUser(array $credentials): bool
    {

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->post('user/login', $credentials, true)
        ;

        try {
            return $response && $response->getStatusCode() === Response::HTTP_OK;
        } catch (TransportExceptionInterface $error) {
            return false;
        }
    }
}