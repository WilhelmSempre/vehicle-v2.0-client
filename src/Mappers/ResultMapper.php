<?php

namespace App\Mappers;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\AccessType;

/**
 * @AccessType("public_method")
 *
 * Class ResultMapper
 * @package App\Mappers
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class ResultMapper implements ApiResponseMapperInterface
{

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getStatus",setter="setStatus")
     *
     * @var string|null
     */
    private ?string $status;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getMessage",setter="setMessage")
     *
     * @var string|null
     */
    private ?string $message;

    /**
     * @param string $status
     * @return ResultMapper
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $message
     * @return ResultMapper
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