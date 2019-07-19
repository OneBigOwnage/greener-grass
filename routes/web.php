<?php

use App\GitHubContributionsService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (GitHubContributionsService $service) {
    return view('main')->with('contributionsCount', $service->getContributions('OneBigOwnage'));
});

Route::get('/add-contribution', function (GitHubContributionsService $service) {
    $service->addContribution('OneBigOwnage', 'greener-grass');

    return redirect('/');
});
