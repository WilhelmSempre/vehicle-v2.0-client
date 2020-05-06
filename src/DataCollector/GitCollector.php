<?php

namespace App\DataCollector;

use App\Mappers\ApiAuthorizationMapper;
use App\Mappers\GitMapper;
use App\Services\ApiAdapter;
use App\Services\ApiService;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class GitCollector
 * @package App\DataCollector
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class GitCollector extends DataCollector
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiCollector constructor.
     * @param ApiService $apiService
     * @param SerializerInterface $serializer
     */
    public function __construct(ApiService $apiService, SerializerInterface $serializer)
    {
        $this->apiService = $apiService;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function collect(Request $request, Response $response): void
    {
        $this->data['commit_hash'] = null;
        $this->data['commit_message'] = null;
        $this->data['commit_author'] = null;
        $this->data['commit_date'] = null;
        $this->data['commit_branch'] = null;

        $adapter = $this->apiService
            ->getAdapter()
        ;

        $gitLogs = $this->getApiGitLogs();

        /** @var GitMapper $gitLogs */
        $gitLogs = $adapter->deserialize($gitLogs, GitMapper::class);


        if (!empty($gitLogs->getLastCommitMessage())) {
            $this->data['commit_message'] = $gitLogs->getLastCommitMessage();
        }

        if (!empty($gitLogs->getBranchName())) {
            $this->data['commit_branch'] = $gitLogs->getBranchName();
        }

        if (!empty($gitLogs->getLastCommitAuthor())) {
            $this->data['commit_author'] = $gitLogs->getLastCommitAuthor();
        }

        if (!empty($gitLogs->getLastCommitDate())) {
            $this->data['commit_date'] = $gitLogs->getLastCommitDate();
        }

        if (!empty($gitLogs->getLastCommitHash())) {
            $this->data['commit_hash'] = $gitLogs->getLastCommitHash();
        }
    }

    /**
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getApiGitLogs(): string
    {
        return $this->apiService
            ->getApiGitLogs()
        ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app.git.collector';
    }

    /**
     *
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return string|null
     */
    public function getBranchName(): ?string
    {
        return $this->data['commit_branch'];
    }

    /**
     * @return string|null
     */
    public function getLastCommitAuthor(): ?string
    {
        return $this->data['commit_author'];
    }

    /**
     * @return string|null
     */
    public function getLastCommitDate(): ?string
    {
        return $this->data['commit_date'];
    }

    /**
     * @return string|null
     */
    public function getLastCommitMessage(): ?string
    {
        return $this->data['commit_message'];
    }

    /**
     * @return string|null
     */
    public function getLastCommitHash(): ?string
    {
        return $this->data['commit_hash'];
    }
}