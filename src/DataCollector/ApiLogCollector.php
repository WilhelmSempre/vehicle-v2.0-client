<?php

namespace App\DataCollector;

use App\Mappers\ResultMapper;
use App\Services\ApiAdapter;
use App\Services\ApiLogService;
use App\Services\ApiService;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ApiLogCollector
 * @package App\DataCollector
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiLogCollector extends DataCollector
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var ApiLogService
     */
    private ApiLogService $apiLogService;

    /**
     * ApiLogCollector constructor.
     * @param KernelInterface $kernel
     * @param ApiLogService $apiLogService
     */
    public function __construct(KernelInterface $kernel, ApiLogService $apiLogService)
    {
        $this->kernel = $kernel;
        $this->apiLogService = $apiLogService;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function collect(Request $request, Response $response): void
    {
        $apiLogFile = sprintf('%s/logs/%s/api.log', $this->kernel->getProjectDir(), $this->kernel->getEnvironment());

        if (!file_exists($apiLogFile)) {
            return;
        }

        $this->data['log'] = [];

        $apiLogFile = trim(file_get_contents($apiLogFile));

        if (!$apiLogFile) {
            return;
        }

        $apiLogFileLines = explode("\n", $apiLogFile);
        $apiLogFileLines = array_reverse($apiLogFileLines);

        foreach ($apiLogFileLines as $apiLogFileLine) {
            $apiLogFileLineArray = json_decode($apiLogFileLine, true);
            $apiLogFileLineArray = explode('|', $apiLogFileLineArray['message']);

            $this->data['log'][] = [
                'date' => $apiLogFileLineArray[4],
                'path' => $apiLogFileLineArray[0],
                'status' => $apiLogFileLineArray[1],
                'request' => $apiLogFileLineArray[2],
                'response' => $apiLogFileLineArray[3],
            ];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'api-log';
    }

    /**
     *
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getLog(): array
    {
        return $this->data['log'];
    }
}