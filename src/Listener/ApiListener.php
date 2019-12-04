<?php

namespace App\Listener;

use App\Exception\ApiUrlMissingException;
use App\Services\ApiService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Router;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ApiListener
 * @package App\Listener
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiListener
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
     * @var array
     */
    private $disabledRoutes = [
        'offline_index',
        '_wdt',
    ];

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
     * @param RequestEvent $event
     */
    public function listenApi(RequestEvent $event): void
    {
        try {
            $request = $event->getRequest();
            $currentRoute = $request->attributes->get('_route');

            if (!$currentRoute || in_array($currentRoute, $this->disabledRoutes, true)) {
                return;
            }

            $apiStatus = $this->apiService->checkApiStatus();

            $apiAuthorizationStatusArray = $this->apiService->checkApiAuthorization();

            if (!$apiStatus || $apiAuthorizationStatusArray['status'] !== Response::HTTP_OK) {
                $this->setOfflinePage($event);
            }
        } catch (ApiUrlMissingException $error) {
            $this->setOfflinePage($event);
        } catch (TransportExceptionInterface $error) {
            $this->setOfflinePage($event);
        } catch (ClientExceptionInterface $error) {
            $this->setOfflinePage($event);
        } catch (RedirectionExceptionInterface $error) {
            $this->setOfflinePage($event);
        } catch (ServerExceptionInterface $error) {
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

        $event->setResponse(new RedirectResponse($offlineRoute, 301));

        return $event->getResponse();
    }
}