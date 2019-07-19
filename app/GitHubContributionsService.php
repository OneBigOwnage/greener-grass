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
     * The Client instance.
     *
     * @var Client
     */
    public $httpClient;

    /**
     * Create a new instance of the service.
     *
     * @param  GitHubManager $gitHub An instance of the GitHubManager.
     * @param  Client        $httpClient An instance of the Client.
     */
    public function __construct(GitHubManager $gitHub, Client $httpClient) {
        $this->gitHub = $gitHub;
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieve the number of contributions for the given user on the given date.
     *
     * TODO: If the date is at least a year ago, the URL has to be adjusted.
     *       Example of adjusted URL: https://github.com/OneBigOwnage?tab=overview&from=2018-12-01&to=2018-12-31
     *
     * @param  string $username The user whose contributions to fetch.
     * @param  Carbon $date     (Optional) The day for which to fetch the contribution count. Defaults to today;
     *
     * @return int The number of contributions of the given user on the given date.
     *             If the contributions could not be retrieved, -1 is returned.
     */
    public function getContributions(string $username, Carbon $date = null)
    {
        $domCrawler = $this->httpClient->request('GET', "https://github.com/{$username}");

        $date = ($date ?? now())->format('Y-m-d');

        $square = $domCrawler->filter("rect[data-date=\"{$date}\"]")->first();

        if (is_null($square->html(null))) {
            return -1;
        }

        return $square->attr('data-count');
    }

    /**
     * Retrieve the total amount of issues that have been created by the ContributionsBot in the given repository.
     *
     * @param  string $username   The user to whom the repository belongs.
     * @param  string $repository The repository on which to count issues.
     *
     * @return int The amount of issues that exist in the given repository.
     */
    public function getIssueCount(string $username, string $repository)
    {
        $issues = $this->gitHub->issues()
            ->all($username, $repository, [ 'state' => 'all', 'labels' => 'contributions-bot' ]);

        return count($issues);
    }

    /**
     * Create an issue on the given repository, which counts as a contribution.
     *
     * @param  string $username   The user to whom the repository belongs.
     * @param  string $repository The repository on which to create an issue.
     *
     * @return void
     */
    public function addContribution(string $username, string $repository)
    {
        $issueNumber = $this->getIssueCount($username, $repository) + 1;
        $title       = "Automatic message to save the day #{$issueNumber}!";
        $labels      = [ 'contributions-bot' ];

        $this->gitHub->issues()->create($username, $repository, [
            'labels' => $labels, 'title'  => $title
        ]);
    }
}
