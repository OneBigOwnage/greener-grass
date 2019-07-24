<?php

namespace App;

class DailyContribution
{
    public function __invoke(GitHubContributionsService $service)
    {
        if ($service->getContributions('OneBigOwnage') < 1) {
            logger()->info('[MOCK] Adding contribution.');
            // $service->addContribution('OneBigOwnage', 'greener-grass');
        } else {
            logger()->info('No need to add a contribution');
        }
    }
}
