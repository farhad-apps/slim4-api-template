<?php

namespace App\Libraries;

class HttpClient
{
    private string $baseUrl;
    private array $headers = [];
    private int $timeout = 30;

    public function __construct(string $baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function get(string $endpoint, array $query = []): array
    {
        $url = $this->buildUrl($endpoint, $query);

        $options = [
            'http' => [
                'method' => 'GET',
                'timeout' => $this->timeout,
                'header' => $this->buildHeaders(),
            ]
        ];

        $response = @file_get_contents($url, false, stream_context_create($options));

        if ($response === false) {
            return ['error' => 'Failed to fetch data'];
        }

        return json_decode($response, true) ?? [];
    }

    public function post(string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        $body = json_encode($data);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => $this->buildHeaders() . "Content-Type: application/json\r\n",
                'content' => $body,
                'timeout' => $this->timeout,
            ]
        ];

        $response = @file_get_contents($url, false, stream_context_create($options));

        if ($response === false) {
            return ['error' => 'Failed to send request'];
        }

        return json_decode($response, true) ?? [];
    }

    private function buildUrl(string $endpoint, array $query = []): string
    {
        $url = $this->baseUrl . $endpoint;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    private function buildHeaders(): string
    {
        $headers = "";

        foreach ($this->headers as $key => $value) {
            $headers .= "$key: $value\r\n";
        }

        return $headers;
    }
}
