<?php

namespace App\Listener;

use App\Mappers\ApiAuthorizationMapper;
use App\Services\ApiService;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Router;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 *
 * Class RequestListener
 * @package App\Listener
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class RequestListener
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiListener constructor.
     * @param Router $router
     * @param ApiService $apiService
     * @param SerializerInterface $serializer
     */
    public function __construct(Router $router, ApiService $apiService, SerializerInterface $serializer)
    {
        $this->router = $router;
        $this->apiService = $apiService;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Request
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function checkApiStatus(Request $request): Request
    {
        $apiStatus = $this->apiService->checkApiStatus();
        $apiAuthorization = $this->apiService->checkApiAuthorization();

        /** @var ApiAuthorizationMapper $apiAuthorization */
        $apiAuthorization = $this->deserializeData($apiAuthorization, ApiAuthorizationMapper::class);

        if (!$apiStatus || (int) $apiAuthorization->getStatus() !== Response::HTTP_OK) {
            throw new TransportException('Api is offline');
        }

        return $request;
    }

    /**
     * @param string $data
     * @param string $entity
     * @return ApiAuthorizationMapper
     */
    public function deserializeData(string $data, string $entity): ApiAuthorizationMapper
    {
        $format = $_ENV['API_RESPONSE_FORMAT'] ?? 'json';
        $format = in_array($format, ['xml', 'json'], true) ? $format : 'json';

        return $this->serializer
            ->deserialize($data, $entity, $format)
        ;
    }

    /**
     * @param RequestEvent $event
     */
    public function listen(RequestEvent $event): void
    {
        $request = $event->getRequest();

        try {
            $this->checkApiStatus($request);

        } catch (ExceptionInterface $exception) {
            $this->setOfflinePage($event);
        }
    }

    /**
     * @param RequestEvent $event
     * @return RedirectResponse
     */
    public function setOfflinePage(RequestEvent $event): Response
    {
        $offlineRoute = $this->router->generate('offline_index');

        $request = $event->getRequest();

        $currentRoute = $request->attributes
            ->get('_route')
        ;

        if ($currentRoute && !in_array($currentRoute, ['offline_index', '_wdt'], true)) {
            $event->setResponse(new RedirectResponse($offlineRoute, Response::HTTP_MOVED_PERMANENTLY));
        }

        return $event->getResponse();
    }
}