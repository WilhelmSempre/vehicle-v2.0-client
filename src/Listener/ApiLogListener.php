<?php

namespace App\Listener;

use App\Event\ApiRequestEvent;
use App\Services\ApiLogService;

/**
 * Class ApiLogListener
 * @package App\Listener
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiLogListener
{

    /**
     * @var ApiLogService
     */
    private ApiLogService $apiLogService;

    /**
     * ApiLogListener constructor.
     * @param ApiLogService $apiLogService
     */
    public function __construct(ApiLogService $apiLogService)
    {
        $this->apiLogService = $apiLogService;
    }

    /**
     * @param ApiRequestEvent $event
     */
    public function log(ApiRequestEvent $event): void
    {
        $this->apiLogService->log($event);
    }
}