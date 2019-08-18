<?php

namespace App;

use Illuminate\Support\Carbon;

use Goutte\Client;
use GrahamCampbell\GitHub\GitHubManager;

class GitHubContributionsService
{
    /**
     * The GitHubManager instance.
     *
     * @var GitHubManager
     */
    public $gitHub;

    /**
     * the Goutte HTTP Client instance.
     *
     * @var Client
     */
    public $httpClient;

    /**
     * The username of the GitHub account that is used to interact with GitHub.
     *
     * @var string
     */
    public $username;

    /**
     * The password of the GitHub account.
     *
     * @var string
     */
    public $password;

    /**
     * The default repository to interact with.
     *
     * @var string
     */
    public $repository;

    /**
     * Create a new instance of the service.
     *
     * @param  GitHubManager $gitHub     An instance of the GitHubManager.
     * @param  Client        $httpClient An instance of the Goutte HTTP Client.
     * @param  string        $username   The username of the GitHub account that is used to interact with GitHub.
     * @param  string        $password   The password of the GitHub account.
     * @param  string        $repository The default repository to interact with.
     *
     * @return void
     */
    public function __construct(
        GitHubManager $gitHub,
        Client $httpClient,
        string $username = null,
        string $password = null,
        string $repository = null
    ) {
        $this->gitHub = $gitHub;
        $this->httpClient = $httpClient;
        $this->repository = $repository;

        $this->username = $username ?? env('GITHUB_USERNAME');
        $this->password = $password ?? env('GITHUB_PASSWORD');
    }

    /**
     * Retrieve the number of contributions on the given date.
     *
     * TODO: If the date is at least a year ago, the URL has to be adjusted.
     *       Example of adjusted URL: https://github.com/OneBigOwnage?tab=overview&from=2018-12-01&to=2018-12-31
     *
     * @param  Carbon $date (Optional) The day for which to fetch the contribution count; defaults to today.
     *
     * @return int The number of contributions of the given user on the given date.
     *             If the contributions could not be retrieved, -1 is returned.
     */
    public function getContributions(Carbon $date = null)
    {
        $domCrawler = $this->httpClient->request('GET', "https://github.com/{$this->username}");

        $date = ($date ?? now())->format('Y-m-d');

        $square = $domCrawler->filter("rect[data-date=\"{$date}\"]")->first();

        if (is_null($square->html(null))) {
            return -1;
        }

        return $square->attr('data-count');
    }

    /**
     * Retrieve the total amount of issues that have been created by this ContributionsBot.
     *
     * @return int The amount of issues with the label 'contributions-bot' that exist in the given repository.
     */
    public function getIssueCount()
    {
        $issues = $this->gitHub->issues()
            ->all($this->username, $this->repository, [ 'state' => 'all', 'labels' => 'contributions-bot' ]);

        return count($issues);
    }

    /**
     * Create an issue in the default repository, which counts as a contribution.
     *
     * @return void
     */
    public function addContribution()
    {
        $issueNumber = $this->getIssueCount($this->username, $this->repository) + 1;
        $title       = "Automatic message to save the day #{$issueNumber}!";
        $labels      = [ 'contributions-bot' ];

        $this->gitHub->issues()->create($this->username, $this->repository, [
            'labels' => $labels, 'title'  => $title
        ]);
    }

    /**
     * Add a contribution, if the GitHub account does not have made one themselves already today.
     *
     * @return void
     */
    public function addContributionIfNonePresent()
    {
        $contributionsCount = $this->getContributions();

        if ($contributionsCount >= 1) {
            logger()->info("No need to add an automatic contribution, {$this->username} already has already made {$contributionsCount} contribution(s) today.");
            return;
        }

        logger()->info("Going to add a contribution to {$this->username}/{$this->repository}");

        $this->addContribution();
    }
}
