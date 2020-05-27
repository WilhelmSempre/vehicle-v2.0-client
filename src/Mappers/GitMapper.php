<?php

namespace App\Mappers;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\AccessType;

/**
 * Class GitMapper
 * @package App\Mappers
 */
class GitMapper implements ApiResponseMapperInterface
{
    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getBranchName",setter="setBranchName")
     *
     * @var string|null
     */
    private ?string $branchName;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getLastCommitMessage",setter="setLastCommitMessage")
     *
     * @var string|null
     */
    private ?string $lastCommitMessage;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getLastCommitAuthor",setter="setLastCommitAuthor")
     *
     * @var string|null
     */
    private ?string $lastCommitAuthor;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getLastCommitDate",setter="setLastCommitDate")
     *
     * @var string|null
     */
    private ?string $lastCommitDate;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getLastCommitHash",setter="setLastCommitHash")
     *
     * @var string|null
     */
    private ?string $lastCommitHash;

    /**
     * @return string|null
     */
    public function getBranchName(): ?string
    {
        return $this->branchName;
    }

    /**
     * @param string|null $branchName
     * @return GitMapper
     */
    public function setBranchName(?string $branchName): self
    {
        $this->branchName = $branchName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastCommitAuthor(): ?string
    {
        return $this->lastCommitAuthor;
    }

    /**
     * @param string|null $lastCommitAuthor
     * @return GitMapper
     */
    public function setLastCommitAuthor(?string $lastCommitAuthor): self
    {
        $this->lastCommitAuthor = $lastCommitAuthor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastCommitDate(): ?string
    {
        return $this->lastCommitDate;
    }

    /**
     * @param string|null $lastCommitDate
     * @return GitMapper
     */
    public function setLastCommitDate(?string $lastCommitDate): self
    {
        $this->lastCommitDate = $lastCommitDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastCommitMessage(): ?string
    {
        return $this->lastCommitMessage;
    }

    /**
     * @param string|null $lastCommitMessage
     * @return GitMapper
     */
    public function setLastCommitMessage(?string $lastCommitMessage): self
    {
        $this->lastCommitMessage = $lastCommitMessage;

        return $this;
    }

    /**
     * @param string|null $lastCommitHash
     * @return GitMapper
     */
    public function setLastCommitHash(?string $lastCommitHash): self
    {
        $this->lastCommitHash = $lastCommitHash;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastCommitHash(): ?string
    {
        return $this->lastCommitHash;
    }
}