<?php

namespace App\Listener;

use App\Mappers\ResultMapper;
use App\Services\ApiAdapter;
use App\Services\ApiService;
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
    private Router $router;

    /**
     * @var ApiService
     */
    private ApiService $apiService;

    /**
     * ApiListener constructor.
     * @param Router $router
     * @param ApiService $apiService
     */
    public function __construct(Router $router, ApiService $apiService)
    {
        $this->router = $router;
        $this->apiService = $apiService;
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

        $adapter = $this->apiService
            ->getAdapter()
        ;

        $apiStatus = $this->apiService
            ->checkApiStatus()
        ;

        $apiAuthorization = $this->apiService
            ->checkApiAuthorization()
        ;

        if (!$apiAuthorization) {
            return $request;
        }

        /** @var ResultMapper $apiAuthorization */
        $apiAuthorization = $adapter->deserialize($apiAuthorization, ResultMapper::class);

        if (!$apiStatus || (int) $apiAuthorization->getStatus() !== Response::HTTP_OK) {
            throw new TransportException('Api is offline');
        }

        return $request;
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
    public function setOfflinePage(RequestEvent $event): ?Response
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