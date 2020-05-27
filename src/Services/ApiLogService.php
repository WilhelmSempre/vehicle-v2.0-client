<?php

namespace App\Services;

use App\Event\ApiRequestEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class ApiLogService
 * @package App\Services
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiLogService
{

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $apiLogger;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * ApiLogService constructor.
     * @param LoggerInterface $apiLogger
     * @param SessionInterface $session
     */
    public function __construct(LoggerInterface $apiLogger, SessionInterface $session)
    {
        $this->apiLogger = $apiLogger;
        $this->session = $session;
    }

    /**
     * @param ApiRequestEvent $event
     */
    public function log(ApiRequestEvent $event): void
    {
        $status = $event->getStatus();
        $path = $event->getPath();
        $response = $event->getResponse();
        $requestData = $event->getRequestData();

        $log = sprintf('[%s] | %d | %s | %s | %s', $path, $status, $requestData, $response, (new \DateTime())->format('d/m/Y H:i:s'));

        $this->apiLogger->info($log);
    }
}