<?php

namespace App\Services;

use App\Mappers\ResultMapper;
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
    private ApiAdapter $adapter;

    /**
     * @var array
     */
    private array $secretEncrypted;

    /**
     * ApiService constructor.
     * @param ApiAdapter $adapter
     * @param AuthorizationService $authorizationService
     * @throws \Exception
     */
    public function __construct(ApiAdapter $adapter, AuthorizationService $authorizationService)
    {
        $this->adapter = $adapter;

        $this->secretEncrypted = $authorizationService
            ->encrypt($_ENV['APP_SECRET'])
        ;
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
     * @return string|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function checkApiAuthorization(): ?string
    {
        $options = [
            'secret' => $this->secretEncrypted['secret'],
            'iv' => $this->secretEncrypted['iv'],
        ];

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->post('authorize', $options)
        ;

        if (!$response) {
            return null;
        }

        return $response->getContent();
    }

    /**
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function getApiGitLogs(): ?string
    {
        $options = [
            'secret' => $this->secretEncrypted['secret'],
            'iv' => $this->secretEncrypted['iv'],
        ];

        /** @var ResponseInterface $response */
        $response = $this->adapter
            ->post('git/summary', $options)
        ;

        if (!$response) {
            return null;
        }

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