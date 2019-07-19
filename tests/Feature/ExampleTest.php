<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\GitHubContributionsService;

use Weidner\Goutte\GoutteFacade;
use GrahamCampbell\GitHub\GitHubManager;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_can_authenticate_with_github()
    {
        /** @var GitHubManager $github */
        $github = app()->make(GitHubManager::class);

        $issueInformation = $github->issues()->show('SunetiOnlineSolutions', 'bulk-cargo', 12);

        $this->assertEquals('patrickvanwijk', $issueInformation['user']['login']);
    }

    /** @test */
    public function it_can_create_an_issue_on_github()
    {
        /** @var GitHubManager $github */
        $github = app()->make(GitHubManager::class);

        $github->issues()->create('OneBigOwnage', 'greener-grass', [
            'title' => 'Second issue created using code!'     ,
            'body'  => 'This is the body of the second issue.',
        ]);
    }

    /** @test */
    public function it_can_retrieve_the_contributions_of_a_user()
    {
        /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
        $crawler = GoutteFacade::request('GET', 'https://github.com/OneBigOwnage');

        $date = now()->format('Y-m-d');

        $contributions = $crawler->filter("rect[data-date=\"{$date}\"]")->first()->attr('data-count');

        $this->assertEquals(7, $contributions);
    }

    /** @test */
    public function the_service_can_retrieve_contributions_count()
    {
        // Given
        // - We have an instance of the service;
        /** @var GitHubContributionsService $service */
        $service = app()->make(GitHubContributionsService::class);

        // When
        // - We ask the number of contributions for a user;
        $contributions = $service->getContributions('OneBigOwnage');

        // Then
        // - It should return a number;
        $this->assertIsNumeric($contributions);
    }

    /** @test */
    public function it_counts_existing_issues()
    {
        // Given
        // - We have an instance of the service;
        /** @var GitHubContributionsService $service */
        $service = app()->make(GitHubContributionsService::class);

        // When
        // - We ask the number of issues that have been created in a given repository;
        $contributions = $service->getIssueCount('OneBigOwnage', 'greener-grass');

        // Then
        // - It should return a number;
        $this->assertIsNumeric($contributions);
    }

    /** @test */
    public function it_adds_an_automatic_contribution()
    {
        // Given
        // - We have an instance of the service;
        // - We know the initial issue count;
        /** @var GitHubContributionsService $service */
        $service = app()->make(GitHubContributionsService::class);
        $issueCount = $service->getIssueCount('OneBigOwnage', 'greener-grass');

        // When
        // - We ask the service to create an issue;
        $service->addContribution('OneBigOwnage', 'greener-grass');

        // Then
        // - The issue count should be upped by one;
        $this->assertSame($issueCount + 1, $service->getIssueCount('OneBigOwnage', 'greener-grass'));
    }
}
