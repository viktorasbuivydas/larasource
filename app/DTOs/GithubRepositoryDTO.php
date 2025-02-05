<?php

declare(strict_types=1);

namespace App\DTOs;

use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class GithubRepositoryDTO
{
    public function __construct(
        public int $githubId,
        public string $name,
        public string $fullName,
        public string $description,
        public string $url,
        public int $stars,
        public string $language,
        public ?string $topics,
        public string $createdRepositoryAt,
        public string $updatedRepositoryAt,
        public stdClass $owner,
        public ?stdClass $license = null,
    ) {}

    public static function fromApiResponse(array $items): Collection
    {
        return collect($items)->map(fn($item) => new self(
            githubId: $item->id,
            name: $item->name,
            fullName: $item->full_name,
            description: $item->description ?? '',
            url: $item->html_url,
            stars: $item->stargazers_count,
            language: $item->language ?? 'Unknown',
            topics: json_encode($item->topics ?? []),
            createdRepositoryAt: Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
            updatedRepositoryAt: Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
            owner: $item->owner,
            license: $item->license
        ));
    }

    public function toArray(): array
    {
        return [
            'github_id' => $this->githubId,
            'name' => $this->name,
            'full_name' => $this->fullName,
            'description' => $this->description,
            'html_url' => $this->url,
            'stars' => $this->stars,
            'language' => $this->language,
            'topics' => $this->topics,
            'created_repository_at' => $this->createdRepositoryAt,
            'updated_repository_at' => $this->updatedRepositoryAt
        ];
    }
}
