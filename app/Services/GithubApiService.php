<?php

namespace App\Services;

class GithubApiService
{
    public function searchRepositories(string $query, int $page = 1): object
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.github.com/search/repositories', [
            'query' => [
                'q' => $query,
                'page' => $page,
                'per_page' => 100,
                'sort' => 'stars',
                'order' => 'desc',
            ],
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'Bearer ' . config('services.github.personal_access_token'),
                'X-GitHub-Api-Version' => '2022-11-28'
            ]
        ]);

        $data = json_decode($response->getBody()->getContents());

        // Add pagination information
        $data->pagination = $this->extractPaginationLinks($response);

        return $data;
    }

    private function extractPaginationLinks($response): array
    {
        $links = [];

        if (!$response->hasHeader('Link')) {
            return $links;
        }

        foreach (explode(',', $response->getHeader('Link')[0]) as $link) {
            preg_match('/<(.+)>;\s*rel="([^"]+)"/', $link, $matches);
            if (isset($matches[2])) {
                $links[$matches[2]] = $matches[1];
            }
        }

        return $links;
    }
}
