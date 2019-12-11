<?php

namespace App\Services;

use App\Mappers\ApiAuthorizationMapper;
use App\Mappers\ApiResponseMapperInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ApiAdapter
 * @package App\Services
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiAdapter
{

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiAdapter constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getApiUrl(): ?string
    {
        return $_ENV['API_URL_ADDRESS'];
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        $format = $_ENV['API_RESPONSE_FORMAT'] ?? 'json';
        $format = in_array($format, ['xml', 'json'], true) ? $format : 'json';

        return $format;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return ResponseInterface|null
     */
    private function call(string $method, string $endpoint, array $options = null): ?ResponseInterface
    {
        $apiUrl = $this->getApiUrl();

        if (is_array($options)) {
            $endpoint = strtr($endpoint, $options);
        }

        if (!$apiUrl) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'No api url defined');
        }

        $httpClient = HttpClient::create();

        $format = $this->getFormat();

        $apiUrl = sprintf('%s/%s?format=%s', $apiUrl, $endpoint, $format);

        try {
            return $httpClient->request($method, $apiUrl);
        } catch (ExceptionInterface $error) {}

        return null;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getStatus(): ?ResponseInterface
    {
        $apiUrl = $this->getApiUrl();

        if (!$apiUrl) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'No api url defined');
        }

        $httpClient = HttpClient::create();

        try {
            return $httpClient->request(Request::METHOD_GET, $apiUrl);
        } catch (TransportExceptionInterface $error) {}

        return null;
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @return ResponseInterface|null
     */
    public function get(string $endpoint, array $options = null): ?ResponseInterface
    {
        return $this->call(Request::METHOD_GET, $endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @return ResponseInterface|null
     */
    public function post(string $endpoint, array $options = null): ?ResponseInterface
    {
        return $this->call(Request::METHOD_POST, $endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @return ResponseInterface|null
     */
    public function delete(string $endpoint, array $options = null): ?ResponseInterface
    {
        return $this->call(Request::METHOD_DELETE, $endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @return ResponseInterface|null
     */
    public function put(string $endpoint, array $options): ?ResponseInterface
    {
        return $this->call(Request::METHOD_PUT, $endpoint, $options);
    }

    /**
     * @param string $data
     * @param string $entity
     * @return mixed
     */
    public function deserialize(string $data, string $entity): ApiAuthorizationMapper
    {
        $format = $this->getFormat();

        return $this->serializer
            ->deserialize($data, $entity, $format);
    }
}