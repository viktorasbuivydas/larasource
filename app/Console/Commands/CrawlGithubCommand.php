<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use App\Models\Owner;
use App\Models\License;
use App\Models\CrawlError;
use App\Models\Repository;
use Illuminate\Console\Command;
use App\DTOs\GithubRepositoryDTO;
use App\Services\GithubApiService;
use Illuminate\Support\Facades\Log;

class CrawlGithubCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawl-github {--stars=* : Star ranges to crawl (e.g. 10..20,21..30)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl GitHub repositories based on star ranges';

    public function __construct(
        private readonly GithubApiService $githubApiService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $starRanges = $this->getStarRanges();

        foreach ($starRanges as $range) {
            $this->info("Processing repositories with stars: {$range}");
            $this->crawlRepositories($range);
        }

        return Command::SUCCESS;
    }

    private function crawlRepositories(string $starRange): void
    {
        $page = 1;
        $query = "laravel language:PHP is:public stars:{$starRange}";

        try {
            do {
                $this->info("Fetching page {$page}");

                $response = $this->githubApiService->searchRepositories($query, $page);
                $repositories = GithubRepositoryDTO::fromApiResponse($response->items);

                foreach ($repositories as $repository) {

                    if ($repository->disabled || $repository->archived) {
                        continue;
                    }

                    // attach tags from topics
                    // Create or find owner
                    $owner = Owner::firstOrCreate([
                        'login' => $repository->owner->login,
                        'type' => $repository->owner->type,
                        'avatar_url' => $repository->owner->avatar_url,
                    ]);

                    // Create or find license if exists
                    $license = null;
                    if ($repository->license) {
                        $license = License::firstOrCreate(
                            [
                                'name' => $repository->license->name,
                            ],
                            [
                                'url' => $repository->license->url,
                                'name' => $repository->license->name,
                            ]
                        );
                    }

                    // Create or update repository
                    $repo = Repository::updateOrCreate(
                        ['github_id' => $repository->githubId],
                        $repository->toArray()
                    );

                    $repo->attachTags(json_decode($repository->topics));

                    // Sync relationships
                    $repo->owners()->sync([$owner->id]);
                    if ($license) {
                        $repo->licenses()->sync([$license->id]);
                    }
                }

                $hasNextPage = isset($response->pagination['next']);
                $page++;

                // Respect GitHub API rate limits
                if ($hasNextPage) {
                    sleep(2);
                }
            } while ($hasNextPage);
        } catch (Exception $e) {
            $this->error("Error occurred while processing star range: {$starRange} at page: {$page}");
            Log::error('GitHub crawl failed', [
                'star_range' => $starRange,
                'page' => $page,
                'error' => $e->getMessage()
            ]);

            CrawlError::create([
                'star_range' => $starRange,
                'page' => $page,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function getStarRanges(): array
    {
        $ranges = $this->option('stars');

        if (empty($ranges)) {
            return config('stars');
        }

        return $ranges;
    }
}
