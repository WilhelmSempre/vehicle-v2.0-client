<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ApiService
 * @package App\Services
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiService
{

    /**
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function checkApiStatus(): bool
    {
        $apiUrl = $this->getApiUrl();

        if (!$apiUrl) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'No api url defined');
        }

        $httpClient = HttpClient::create();

        $response = $httpClient->request(Request::METHOD_GET, $apiUrl);
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
        $apiUrl = $this->getApiUrl();

        if (!$apiUrl) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'No api url defined');
        }

        $httpClient = HttpClient::create();

        $format = $_ENV['API_RESPONSE_FORMAT'] ?? 'json';

        $authorizationRequestUrl = sprintf('%s/authorize/%s?format=%s', $apiUrl, $_ENV['APP_SECRET'], $format);

        $response = $httpClient->request(Request::METHOD_GET, $authorizationRequestUrl);

        return $response->getContent();
    }

    /**
     * @return string
     */
    public function getApiUrl(): ?string
    {
        return $_ENV['API_URL_ADDRESS'];
    }
}