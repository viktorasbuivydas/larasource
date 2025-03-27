<?php

namespace App\Console\Commands;

use App\Models\Repository;
use Illuminate\Console\Command;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

class ApproveInterestingRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:approve-interesting-repositories
                            {--repository= : Process a specific repository by ID}
                            {--limit= : Limit the number of repositories to process}
                            {--dry-run : Run without making actual approvals}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve repositories that are suitable for open-source contributions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repositoryId = $this->option('repository');
        $limit = $this->option('limit');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in dry-run mode. No repositories will be approved.');
        }

        if ($repositoryId) {
            $repository = Repository::findOrFail($repositoryId);
            $this->processRepository($repository, $dryRun);
        } else {
            $query = Repository::whereNull('approved_at');

            if ($limit) {
                $query = $query->limit((int) $limit);
            }

            $repositories = $query->get();
            $this->info("Processing {$repositories->count()} unapproved repositories...");

            foreach ($repositories as $repository) {
                $this->processRepository($repository, $dryRun);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Process a single repository for approval.
     */
    private function processRepository(Repository $repository, bool $dryRun = false): void
    {
        $branches = ['main', 'master'];

        // get readme
        $readme = null;
        foreach ($branches as $branch) {
            $url = str_replace(
                ['{owner}', '{repo}', '{branch}'],
                [$repository->owners->first()->login, $repository->name, $branch],
                'https://raw.githubusercontent.com/{owner}/{repo}/{branch}/README.md'
            );

            $response = Http::get($url);

            if ($response->successful()) {
                $readme = $response->body();
                break;
            }
        }

        // Ensure proper UTF-8 encoding for the readme content
        if ($readme !== null) {
            $readme = mb_convert_encoding($readme, 'UTF-8', 'UTF-8');
        }

        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => '
                        You are an AI assistant acting as an experienced software developer and open-source project evaluator. Your task is to determine if a given GitHub repository represents a suitable opportunity for someone looking to make meaningful open-source contributions.

                        **Input:**

                        1.  **README Content:**
                            ```markdown
                            ' . ($readme ?? 'No README found') . '
                            ```

                        2.  **Repository Metadata (JSON):** Contains details like `name`, `description`, `fork` (boolean), `archived` (boolean), `stargazers_count`, `open_issues_count`, `language`, `updated_at`, `license`, etc.
                            ```json
                            ' . json_encode($repository, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '
                            ```

                        **Evaluation Criteria:**

                        Assess the repository based on the following factors, using information from BOTH the README and the repository metadata:

                        1.  **Project Nature & Scope:**
                            *   Is it explicitly identified as an open-source project? (Check README, `license` field).
                            *   Does it appear to be a substantial or real-world project, rather than a small personal experiment, tutorial result, boilerplate, coding challenge solution, or test assignment? (Consider `description`, `stargazers_count`, README content).
                            *   Is it a fork (`fork: true`)? If yes, does it seem to be adding significant value or just a personal copy? Simple forks are generally *not* suitable.
                            *   Is the project archived (`archived: true`)? Archived projects are *not* suitable.

                        2.  **Contribution Readiness:**
                            *   Does the README mention contributions, link to a `CONTRIBUTING.md` file, list open issues, or otherwise indicate openness to external contributors?
                            *   Are there open issues (`open_issues_count > 0`) suggesting active development or areas needing help?
                            *   Does the project show signs of recent activity? (Check `updated_at`, mentions of recent releases or active development in the README). Currently it is ' . now()->format('Y-m-d') . '

                        3.  **Red Flags:**
                            *   Explicit mentions like "test task," "assignment," "learning project," "personal backup," "demo," "example code.", "portfolio", "school project", "test your skills", "roadmap"
                            *   Lack of a clear description, purpose, or documentation in the README.
                            *   Very low activity (`updated_at` is very old) combined with low `stargazers_count` and `open_issues_count`.
                            *   Readme clearly states they do not accept any contributions, project is a test.

                        **Output:**

                        Provide your assessment strictly in the following JSON format. Be objective and base your reasoning on the provided data.

                        ```json
                        {
                        "suitable": true|false,
                        "reason": "A concise explanation based on the evaluation criteria. Mention specific evidence from the README or metadata (e.g., Project is archived, README explicitly welcomes contributors and links to contribution guidelines, Appears to be a small personal project based on description and low activity, Identified as a test task in README).",
                        "description": "A meaningful description about project"
                        }'
                ],
            ],
        ]);

        $response = $result->choices[0]->message->content;
        $this->info("Evaluation for {$repository->full_name}:");
        $this->line('');

        try {
            $evaluation = str_replace('```json', '', $response);
            $evaluation = str_replace('```', '', $evaluation);
            $evaluation = json_decode($evaluation, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            if (isset($evaluation['suitable']) && $evaluation['suitable'] === true) {
                if (!$dryRun) {
                    $repository->approve();
                    $repository->update([
                        'reason' => $evaluation['reason'] ?? null,
                        'description' => $evaluation['description'] ?? null
                    ]);
                    $this->info("Repository {$repository->full_name} has been approved.");
                } else {
                    $this->info("Repository {$repository->full_name} would be approved (dry run).");
                }
            } else {
                $this->warn("Repository {$repository->full_name} was not approved: " . ($evaluation['reason'] ?? 'No reason provided'));
                $repository->delete();
            }

            sleep(2);
        } catch (\Exception $e) {
            $this->error("Failed to process evaluation response for {$repository->full_name}: " . $e->getMessage());
        }
    }
}
