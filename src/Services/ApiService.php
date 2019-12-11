<?php

namespace App\Services;

use App\Mappers\ApiAuthorizationMapper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ApiService
 * @package App\Services
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiService
{

    /**
     * @var ApiAdapter
     */
    private $adapter;

    /**
     * ApiService constructor.
     * @param ApiAdapter $adapter
     */
    public function __construct(ApiAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function checkApiStatus(): bool
    {

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->getStatus()
        ;

        if (!$response) {
            return false;
        }

        $responseStatusCode = $response->getStatusCode();

        return $responseStatusCode === Response::HTTP_OK;
    }

    /**
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function checkApiAuthorization(): string
    {

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->get('authorize/{secret}', [
                '{secret}' => $_ENV['APP_SECRET'],
            ])
        ;

        return $response->getContent();
    }

    /**
     * @return ApiAdapter
     */
    public function getAdapter(): ApiAdapter
    {
        return $this->adapter;
    }
}