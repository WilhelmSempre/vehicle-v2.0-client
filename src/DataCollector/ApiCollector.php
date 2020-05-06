<?php

namespace App\DataCollector;

use App\Mappers\ApiAuthorizationMapper;
use App\Services\ApiAdapter;
use App\Services\ApiService;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ApiCollector
 * @package App\DataCollector
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiCollector extends DataCollector
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiCollector constructor.
     * @param ApiService $apiService
     * @param SerializerInterface $serializer
     */
    public function __construct(ApiService $apiService, SerializerInterface $serializer)
    {
        $this->apiService = $apiService;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function collect(Request $request, Response $response): void
    {
        $this->data['status'] = 'No connected';
        $this->data['message'] = null;

        $adapter = $this->apiService
            ->getAdapter()
        ;

        $apiStatus = $this->checkApiStatus();

        $this->data['url'] = $adapter->getApiUrl();

        $apiAuthorization = $this->checkApiAuthorization();

        /** @var ApiAuthorizationMapper $apiAuthorization */
        $apiAuthorization = $adapter->deserialize($apiAuthorization, ApiAuthorizationMapper::class);

        $this->data['format'] = $adapter->getFormat();

        if ($apiStatus && (int) $apiAuthorization->getStatus() === Response::HTTP_OK) {
            $this->data['status'] = 'Connected';
        }

        if (!empty($apiAuthorization->getMessage())) {
            $this->data['message'] = $apiAuthorization->getMessage();
        }
    }

    /**
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function checkApiAuthorization(): string
    {
        return $this->apiService
            ->checkApiAuthorization()
        ;
    }

    /**
     * @return bool
     * @throws TransportExceptionInterface
     */
    private function checkApiStatus(): bool
    {
        try {
            return $this->apiService
                ->checkApiStatus()
            ;
        } catch (HttpException $error) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app.api.collector';
    }

    /**
     *
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->data['status'];
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->data['message'];
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->data['url'];
    }

    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->data['format'];
    }
}