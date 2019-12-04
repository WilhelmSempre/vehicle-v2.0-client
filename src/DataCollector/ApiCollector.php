<?php

namespace App\DataCollector;

use App\Exception\ApiUrlMissingException;
use App\Services\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
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
    protected $data = [];

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * ApiCollector constructor.
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function collect(Request $request, Response $response): void
    {
        $this->data['status'] = 'No connected';
        $this->data['message'] = null;

        $apiStatus = $this->checkApiStatus();

        $this->data['url'] = $this->getApiUrl();

        $apiAuthorization = $this->checkApiAuthorization();

        if ($apiStatus && $apiAuthorization['status'] === Response::HTTP_OK) {
            $this->data['status'] = 'Connected';
        }

        if (!empty($apiAuthorization['message'])) {
            $this->data['message'] = $apiAuthorization['message'];
        }
    }

    /**
     * @return array
     */
    private function checkApiAuthorization(): array
    {
        try {
            return $this->apiService
                ->checkApiAuthorization();
        } catch (ApiUrlMissingException $error) {
            return [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => $error->getMessage(),
            ];
        } catch (ClientExceptionInterface $error) {
            return [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => $error->getMessage(),
            ];
        } catch (RedirectionExceptionInterface $error) {
            return [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => $error->getMessage(),
            ];
        } catch (ServerExceptionInterface $error) {
            return [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => $error->getMessage(),
            ];
        } catch (TransportExceptionInterface $error) {
            return [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => $error->getMessage(),
            ];
        }
    }

    /**
     * @return bool
     */
    private function checkApiStatus(): bool
    {
        try {
            return $this->apiService
                ->checkApiStatus();
        } catch (ApiUrlMissingException $error) {
            return false;
        } catch (TransportExceptionInterface $error) {
            return false;
        }
    }

    /**
     * @return string|null
     */
    private function getApiUrl(): ?string
    {
        return $this->apiService
            ->getApiUrl();
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
}