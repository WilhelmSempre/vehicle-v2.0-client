<?php

namespace App\Services;

use App\Event\ApiRequestEvent;
use App\Listener\ApiLogListener;
use App\Mappers\ApiResponseMapperInterface;
use App\Type\ApiResponseType;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
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
    private SerializerInterface $serializer;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var ApiLogListener
     */
    private ApiLogListener $apiLogListener;

    /**
     * ApiAdapter constructor.
     * @param SerializerInterface $serializer
     * @param EventDispatcherInterface $eventDispatcher
     * @param ApiLogListener $apiLogListener
     */
    public function __construct(SerializerInterface $serializer, EventDispatcherInterface $eventDispatcher, ApiLogListener $apiLogListener)
    {
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->apiLogListener = $apiLogListener;
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
        $format = $_ENV['API_RESPONSE_FORMAT'] ?? ApiResponseType::JSON;
        $format = in_array($format, [ApiResponseType::XML, ApiResponseType::JSON], true) ? $format : ApiResponseType::JSON;

        return $format;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @param bool $log
     * @return ResponseInterface|null
     */
    private function call(string $method, string $endpoint, array $options = null, $log = true): ?ResponseInterface
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
            $response = $httpClient->request($method, $apiUrl, [
                'body' => $options
            ]);

            if ($log) {
                $apiRequestEvent = new ApiRequestEvent();

                $apiRequestEvent
                    ->setPath($endpoint)
                    ->setRequestData(json_encode($options))
                    ->setStatus((int) $response->getStatusCode())
                    ->setResponse(json_encode($response->getContent()))
                ;

                $this->eventDispatcher->addListener(ApiRequestEvent::NAME, [$this->apiLogListener, 'log']);
                $this->eventDispatcher->dispatch($apiRequestEvent, ApiRequestEvent::NAME);
            }

            return $response;
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
     * @param bool $log
     * @return ResponseInterface|null
     */
    public function get(string $endpoint, array $options = null, $log = false): ?ResponseInterface
    {
        return $this->call(Request::METHOD_GET, $endpoint, $options, $log);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @param bool $log
     * @return ResponseInterface|null
     */
    public function post(string $endpoint, array $options = null, $log = true): ?ResponseInterface
    {
        return $this->call(Request::METHOD_POST, $endpoint, $options, $log);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @param bool $log
     * @return ResponseInterface|null
     */
    public function delete(string $endpoint, array $options = null, $log = false): ?ResponseInterface
    {
        return $this->call(Request::METHOD_DELETE, $endpoint, $options, $log);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @param bool $log
     * @return ResponseInterface|null
     */
    public function put(string $endpoint, array $options, $log = false): ?ResponseInterface
    {
        return $this->call(Request::METHOD_PUT, $endpoint, $options, $log);
    }

    /**
     * @param string $data
     * @param string $entity
     * @return mixed
     */
    public function deserialize(string $data, string $entity): ApiResponseMapperInterface
    {
        $format = $this->getFormat();

        return $this->serializer
            ->deserialize($data, $entity, $format)
        ;
    }
}