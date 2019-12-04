<?php

namespace App\Services;

use App\Exception\ApiUrlMissingException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws ApiUrlMissingException
     * @throws TransportExceptionInterface
     */
    public function checkApiStatus(): bool
    {
        $apiUrl = $this->getApiUrl();

        if (!$apiUrl) {
            throw new ApiUrlMissingException('No api url defined');
        }

        $httpClient = HttpClient::create();

        $response = $httpClient->request(Request::METHOD_GET, $apiUrl);
        $responseStatusCode = $response->getStatusCode();

        return $responseStatusCode === Response::HTTP_OK;
    }

    /**
     * @return array
     * @throws ApiUrlMissingException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function checkApiAuthorization(): array
    {
        $apiUrl = $this->getApiUrl();

        if (!$apiUrl) {
            throw new ApiUrlMissingException('No api url defined');
        }

        $httpClient = HttpClient::create();

        $authorizationRequestUrl = sprintf('%s/authorize/%s', $apiUrl, $_ENV['APP_SECRET']);

        $response = $httpClient->request(Request::METHOD_GET, $authorizationRequestUrl);

        return json_decode($response->getContent(), true);
    }

    /**
     * @return string
     */
    public function getApiUrl(): ?string
    {
        return $_ENV['API_URL_ADDRESS'];
    }
}