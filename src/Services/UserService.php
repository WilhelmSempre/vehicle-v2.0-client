<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
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
     * UserService constructor.
     * @param ApiAdapter $adapter
     */
    public function __construct(ApiAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param User $user
     * @return ResponseInterface
     */
    public function createUser(User $user): ?ResponseInterface
    {
        $options = [
            'email' => $user->getEmail(),
            'password' => sha1($user->getPassword()),
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
        $options = [
            '{email}' => urlencode($user->getEmail()),
        ];

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->get('user/get/email/{email}', $options, true)
        ;

        try {
            return $response && $response->getStatusCode() === Response::HTTP_OK;
        } catch (TransportExceptionInterface $error) {
            return false;
        }
    }
}