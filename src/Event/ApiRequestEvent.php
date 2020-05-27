<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ApiRequestEvent
 * @package App\Event
 */
class ApiRequestEvent extends Event
{

    const NAME = 'vehicle.api.request.event';

    /**
     * @var string|null
     */
    private ?string $requestData;

    /**
     * @var string|
     */
    private ?string $response;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var int
     */
    private int $status;

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $requestData|null
     * @return $this
     */
    public function setRequestData(?string $requestData): self
    {
        $this->requestData = $requestData;

        return $this;
    }

    /**
     * @param string $response|null
     * @return $this
     */
    public function setResponse(string $response = null): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getRequestData(): ?string
    {
        return $this->requestData;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}