<?php

namespace App;

class DailyContribution
{
    /**
     * The service that is used to check and create contributions.
     *
     * @var GitHubContributionsService
     */
    public $service;

    /**
     * The username of the owner of the repository.
     *
     * @var string
     */
    public $username;

    /**
     * The name of the repository, to which a contribution will be added.
     *
     * @var string
     */
    public $repository;

    /**
     * Create a new instance.
     *
     * @param  GitHubContributionsService $service    The service that is used to check and create contributions.
     * @param  string                     $username   The username of the owner of the repository.
     * @param  string                     $repository The name of the repository, to which a contribution will be added.
     *
     * @return void
     */
    public function __construct(GitHubContributionsService $service, $username = 'OneBigOwnage', $repository = 'greener-grass') {
        $this->service = $service;
        $this->username = $username;
        $this->repository = $repository;
    }

    /**
     * Add a contribution, if the user does not have made one themselves already today.
     *
     * @return void
     */
    public function __invoke()
    {
        $contributionsCount = $this->service->getContributions($this->username);

        if ($contributionsCount >= 1) {
            logger()->info("No need to add an automatic contribution, {$this->username} already has already made {$contributionsCount} contribution(s) today.");
            return;
        }

        $this->service->addContribution($this->username, $this->repository);

        logger()->info("Adding a contribution to {$this->username}/{$this->repository}");
    }
}
