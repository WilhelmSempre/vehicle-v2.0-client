<?php

namespace App\Mappers;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\AccessType;

/**
 * @AccessType("public_method")
 *
 * Class ApiAuthorizationMapper
 * @package App\Mappers
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ApiAuthorizationMapper implements ApiResponseMapperInterface
{

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getStatus",setter="setStatus")
     *
     * @var string|null
     */
    private $status;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getMessage",setter="setMessage")
     *
     * @var string|null
     */
    private $message;

    /**
     * @param string $status
     * @return ApiAuthorizationMapper
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $message
     * @return ApiAuthorizationMapper
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}